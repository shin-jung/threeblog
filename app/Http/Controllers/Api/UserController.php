<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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

		if ($showUser) {
			return response()->json([
				'success' => true,
				'message' => 'Success.',
				'data' => $showUser,
			], 200);
		} else {
			return response()->json([
				'success' => false,
				'message' => 'Sorry, you can not see users.',
				'data' => '',
			], 500);
		}
	}
}
