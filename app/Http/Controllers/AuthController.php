<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\UserModel;
use Illuminate\Routing\Middleware\ThrottleRequests;

class AuthController extends Controller
{
    public function auth(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);


        if (Auth::attempt($credentials)) {
            if (Auth::user()->soft_deleted == 1) {
                Auth::logout();
                return back()->withInput()->withErrors([
                    'email' => 'The email does not match our records',
                    'password' => 'The password is incorrect',
                ]);
            }
            $request->session()->regenerate();
            return redirect('/');
        }

        return back()->withInput()->withErrors([
            'email' => 'The email does not match our records',
            'password' => 'The password is incorrect',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        return redirect('/login');
    }
}
