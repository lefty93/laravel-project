<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    //
    public function login(Request $request)
    {
        $loginInfo = $request->validate([
            'loginemail' => ['required',],
            'loginpassword' => ['required',]
        ]);
        if (auth()->attempt(['email' => $loginInfo['loginemail'], 'password' => $loginInfo['loginpassword']])) {
            $request->session()->regenerate();
        }
        return redirect('/');
    }

    public function register(Request $request)
    {
        $registerInfo = $request->validate([
            'name' => ['required', Rule::unique('users', 'name')],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'min:8']
        ]);

        $user = User::create([
            'name' => $registerInfo['name'],
            'email' => $registerInfo['email'],
            'password' => Hash::make($registerInfo['password']), // Hash the password
        ]);


        auth()->login($user);

        return redirect('/');
    }

    public function logout()
    {
        auth()->logout();
        return redirect('/login');
    }
}
