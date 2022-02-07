<?php

namespace App\Repositories\Api;

use App\Models\User;

class UserRepository
{
    public function indexUser()
    {
        return User::all();
    }

    public function register($request)
    {
        return User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
    }

    public function getUserById($userId)
    {
        return User::where('id', $userId)->first();
    }

    public function updateUserToken($userId, $token)
    {
        return User::where('id', $userId)
                    ->update([
                        'token' => $token
                    ]);
    }
}
