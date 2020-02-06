<?php

namespace App\Services;

use App\Repositories\AuthRepository;
use Illuminate\Http\Request;

class AuthService
{
	protected $authRepository;

	public function __construct(AuthRepository $authRepository) //
	{
		$this->authRepository = $authRepository;
	}

	public function register(Request $request)
	{
		return $this->authRepository->register($request);
	}
}