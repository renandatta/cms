<?php

namespace App\Http\Controllers\Admin;

use App\Models\UserAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthRepository
{
    protected $user, $userAuth;
    public function __construct(UserRepository $user, UserAuth $userAuth)
    {
        $this->user = $user;
        $this->userAuth = $userAuth;
    }

    public function login(Request $request)
    {
        $user = $this->user->find($request->input('email'), 'email');
        if (empty($user)) return false;
        if (!Hash::check($request->input('password'), $user->password)) return false;
        $user->auth = $this->userAuth->create([
            'user_id' => $user->id,
            'auth' => 'login',
            'token' => Str::random(32),
        ]);
        return $user;
    }

    public function logout($token)
    {
        $this->userAuth->where('token', $token)->update(['auth' => 'logout']);
    }

}
