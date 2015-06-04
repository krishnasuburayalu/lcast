<?php namespace LCast;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model {

	protected $table = 'bc_user_profile';

	protected $fillable = ['name'];
}
