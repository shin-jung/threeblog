<?php

namespace App\Repositories;

use App\User;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Request;

class AuthRepository
{
	public function register(Request $request)
	{
		return User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
	}
}

