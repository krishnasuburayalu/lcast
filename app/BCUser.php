<?php namespace LCast;

use Illuminate\Database\Eloquent\Model;

class BCUser extends Model {

	protected $table = 'bc_users';
    protected $primaryKey = 'id';

    /**
    * Each album can be associated with one or more user.
    *
    */
    public function albums()
    {
    	return $this->hasMany('LCast\Album', 'user_id','username');
    }

    /**
    * Each album can be associated with one or more user.
    *
    */
    public function appearances()
    {
    	return $this->hasOne('LCast\Appearance', 'user_id','username');
    }

    /**
    * Each album can be associated with one or more user.
    *
    */
    public function experiences()
    {
    	return $this->hasMany('LCast\Experience', 'user_id','username');
    }

    /**
    * Each album can be associated with one or more user.
    *
    */
    public function profile()
    {
    	return $this->hasMany('LCast\Profile', 'user_id','username');
    }

    /**
    * Each album can be associated with one or more user.
    *
    */
    public function qualifications()
    {
    	return $this->hasMany('LCast\Qualification', 'user_id','username');
    }

}
