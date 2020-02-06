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

	public function register(Request $request)
	{
		return User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
	}

}


