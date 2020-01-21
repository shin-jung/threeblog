<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class UserService
{
	protected $userRepository;

	public function __construct(UserRepository $userRepository)
	{
		$this->userRepository = $userRepository;
	}

	public function indexUser()
	{
		return $this->userRepository->indexUser();
	}

}
