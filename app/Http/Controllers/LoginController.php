<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return Auth::check() 
            ? redirect('/pay')
            : view('login');
    }

    public function logIn(Request $request)
    {
        if (Auth::check()) {
            return redirect('/pay');
        }

        $valid = $request->validate([
            'email' => ['required', 'email', 'max:100'],
            'password' => ['required', 'string', 'min:8', 'max:20'],
        ]);

        if (Auth::attempt($valid)) {
            $request->session()->regenerate();
            return redirect()->intended('/pay');
        }

        return back()->withErrors([
            'password' => 'Email or password invalid.',
        ]);
    }

    public function logOut(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
