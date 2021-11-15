<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            return redirect('/pay');
        }

        return view('register');
    }

    public function register(Request $request)
    {
        $validatedDetails = $request->validate([
            'name' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = new User();
        $user->name = $validatedDetails['name'];
        $user->email = $validatedDetails['email'];
        $user->password = Hash::make($validatedDetails['password']);

        $user->save();

        return redirect('/login');
    }
}
