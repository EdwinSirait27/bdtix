<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function loginPage()
    {
        return view('auth.login');
    }
    
public function login(Request $request)
{
    $request->validate([
        'username' => 'required',
        'password' => 'required',
    ]);

    Log::info('Login attempt', ['username' => $request->username]);

    $user = User::with('employee')
        ->where('username', $request->username)
        ->first();

    if (!$user) {
        return back()->with('error', 'Wrong username or password');
    }

    if (
        !$user->employee ||
        !in_array($user->employee->status, ['Active', 'Pending', 'Mutation'])
    ) {
        return back()->with('error', 'Account is inactive');
    }

    if (!Auth::attempt($request->only('username', 'password'))) {
        return back()->with('error', 'Wrong username or password');
    }

    $request->session()->regenerate();

    return redirect()
        ->intended(route('dashboard'))
        ->with('success', 'Login successful');
}


    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
