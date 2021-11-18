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
        // Just some simple validation, not too realistic
        $validatedCreds = $request->validate([
            'email' => ['required', 'email', 'max:100'],
            'password' => ['required', 'min:8', 'max:20'],
        ]);

        if (Auth::attempt($validatedCreds)) {
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
