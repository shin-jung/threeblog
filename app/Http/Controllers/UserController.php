<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\UserServices;  //no s

class UserController extends Controller
{
    protected $user;

    public function __construct(UserServices $user)
	{
		$this->user = $user;
	}

	public function index()
	{
		
		$showUser = $this->user->indexUser(); 

    	return view('/user')
    	    ->with('user', $showUser);
	}
}
