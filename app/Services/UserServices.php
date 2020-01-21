<?php

namespace App\Services;

use App\Repositories\UserRepositories;//y
use Illuminate\Http\Request;

class UserServices
{
	protected $user;

	public function __construct(UserRepositories $user)
	{
		$this->user = $user;
	}

	public function indexUser()
	{
		return $this->user->indexUser();
	}

}
