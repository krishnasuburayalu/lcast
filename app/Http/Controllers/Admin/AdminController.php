<?php namespace LCast\Http\Controllers\Admin;

use LCast\Http\Requests;
use LCast\Http\Controllers\Controller;
use LCast\User;

use Illuminate\Http\Request;

class AdminController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function dashboard()
	{
		return view('admin_template');
	}
}
