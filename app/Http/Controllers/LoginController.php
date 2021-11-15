<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            return redirect('/pay');
        }

        return view('login');
    }

    public function logIn(Request $request)
    {
        $validatedCreds = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($validatedCreds)) {
            $request->session()->regenerate();
            return redirect()->intended('/pay');
        }

        return back()->withErrors([
            'email' => 'Email or password invalid.',
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
