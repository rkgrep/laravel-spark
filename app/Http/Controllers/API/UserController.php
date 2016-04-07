<?php

namespace App\Http\Controllers\API;

use App\Spark;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Contracts\Repositories\UserRepository;

class UserController extends Controller
{
	/**
	 * The user repository instance.
	 *
	 * @var \App\Contracts\Repositories\UserRepository
	 */
	protected $users;

	/**
	 * Create a new controller instance.
	 *
	 * @param  \App\Contracts\Repositories\UserRepository  $users
	 * @return void
	 */
	public function __construct(UserRepository $users)
	{
		$this->users = $users;

		$this->middleware('auth');
	}

	/**
	 * Get the current user of the application.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getCurrentUser()
	{
		return $this->users->getCurrentUser();
	}
}
