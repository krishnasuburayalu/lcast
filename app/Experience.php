<?php namespace LCast;

use Illuminate\Database\Eloquent\Model;

class Experience extends Model {

	protected $table = 'bc_user_experience';

	protected $fillable = ['name'];
}
