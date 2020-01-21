<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\UserService;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
	{
		$this->userService = $userService;
	}

	public function index()
	{
		
		$showUser = $this->userService->indexUser(); 

    	return view('/user')
    	    ->with('user', $showUser);
	}
}
