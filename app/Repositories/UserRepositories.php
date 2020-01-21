<?php

namespace App\Repositories;

use App\User;
use Illuminate\Http\Request;

class UserRepositories
{

	public function indexUser()
	{
		return User::all();
	}

}


