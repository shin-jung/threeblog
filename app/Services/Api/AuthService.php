<?php

namespace App\Services\Api;

use App\Repositories\Api\UserRepository;
use Illuminate\Http\Request;

class AuthService
{
	protected $userRepository;

	public function __construct(UserRepository $userRepository)
	{
		$this->userRepository = $userRepository;
	}

	public function register(Request $request)
	{
		return $this->userRepository->register($request);
	}
}