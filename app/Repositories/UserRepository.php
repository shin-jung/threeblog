<?php

namespace App\Repositories;

use App\User;
use Illuminate\Http\Request;

class UserRepository
{

	public function indexUser()
	{
		return User::all();
	}

}


