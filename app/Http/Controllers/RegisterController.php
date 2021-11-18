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
        // Just some simple validation, not too realistic
        $validatedDetails = $request->validate([
            'name' => ['required', 'min:1', 'max:30'],
            'email' => ['required', 'email', 'max:100'],
            'password' => ['required', 'min:8', 'max:20'],
        ]);

        $user = new User();
        $user->name = $validatedDetails['name'];
        $user->email = $validatedDetails['email'];
        $user->password = Hash::make($validatedDetails['password']);

        $user->save();

        return redirect('/login');
    }
}
