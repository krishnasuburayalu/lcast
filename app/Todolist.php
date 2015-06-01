<?php namespace LCast;

use Illuminate\Database\Eloquent\Model;

class Todolist extends Model {

    protected $fillable = ['name', 'description'];

    private $rules = [
        'name' => 'required',
        'description' => 'required'
    ];

    public function validate() 
    {

        $v = \Validator::make($this->attributes, $this->rules);
        if ($v->passes()) return true;
        $this->errors = $v->messages();
        return false;

    }

    /**
    * Each list is owned by a registered user.
    *
    */
    public function user()
    {
      return $this->belongsTo('LCast\User');
    }

    /**
    * Each list can be associated with one or more tasks.
    *
    */
    public function tasks()
    {
    return $this->hasMany('LCast\Task');
    }

    /**
     * Every list belongs to a Category
     * 
     */
    public function Category()
    {
      return $this->belongsTo('LCast\Category');
    }

    /**
    * Calculate the number of incomplete tasks
    *
    */
    public function remainingTasks()
    {

        $completed = $this->tasks()->where('done', '=', 1)->count();
        $total = $this->tasks()->count();

        return $total - $completed;

    }

}
