<?php namespace LCast;

use Illuminate\Database\Eloquent\Model;

class Appearance extends Model {

	protected $table = 'bc_user_appearance';

	protected $fillable = ['name'];
}
