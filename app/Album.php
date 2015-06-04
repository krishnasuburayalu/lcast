<?php namespace LCast;

use Illuminate\Database\Eloquent\Model;

class Album extends Model {

	protected $table = 'bc_user_album';

	protected $fillable = ['name'];
}
