<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserService
{
    /**
     * @param $data
     * @return User
     */
    public function create($data)
    {
        $user = new User();
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->name = $data['name'];
        $user->save();

        return $user;
    }

    /**
     * @param $data
     * @return bool
     */
    public function createAndLogin($data)
    {
        $this->create($data);

        return Auth::attempt([
            'email' => $data['email'],
            'password' => $data['password']
        ]);
    }
}
