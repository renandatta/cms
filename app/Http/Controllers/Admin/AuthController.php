<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected $auth, $user;
    public function __construct(AuthRepository $auth, UserRepository $user)
    {
        $this->auth = $auth;
        $this->user = $user;
    }

    public function login()
    {
        if (Auth::check()) return redirect()->route('/');
        return view('admin.auth.login');
    }

    public function login_proses(Request $request)
    {
        $request->validate([
            'email' => 'required|min:4|max:255',
            'password' => 'required|min:4|max:255',
        ]);

        $auth = $this->auth->login(new Request($request->only(['email', 'password'])));
        if ($auth == false) return redirect()->route('login')
            ->with('error', 'Username atau password salah !');
        $user = $this->user->find($auth->id);
        Auth::login($user, $request->has('remember'));
        return redirect()->route('admin');
    }

    public function logout()
    {
        $token = Auth::user()->last_login->token ?? '';
        $this->auth->logout($token);
        Auth::logout();
        return redirect()->route('login');
    }
}
