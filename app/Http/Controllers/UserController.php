<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;
use JWTAuth;

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

		return $showUser;//json
	}
}
