<?php namespace LCast;

use Illuminate\Database\Eloquent\Model;

class Qualification extends Model {

	protected $table = 'bc_user_qualification';

	protected $fillable = ['name'];
}
