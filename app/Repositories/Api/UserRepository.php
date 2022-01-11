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
