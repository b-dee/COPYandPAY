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
        return Auth::check()
            ? redirect('/pay')
            : view('register');
    }

    public function register(Request $request)
    {
        if (Auth::check()) {
            return redirect('/pay');
        }
        
        $valid = $request->validate([
            'name' => ['required', 'string', 'min:1', 'max:30'],
            'email' => ['required', 'email', 'max:100'],
            'password' => ['required', 'string', 'min:8', 'max:20'],
        ]);

        $user = new User();
        $user->name = $valid['name'];
        $user->email = $valid['email'];
        $user->password = Hash::make($valid['password']);

        $user->save();

        return redirect('/login');
    }
}
