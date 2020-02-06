<?php

namespace App\Services\Api;

use App\Repositories\Api\UserRepository;
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
