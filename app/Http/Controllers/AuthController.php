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
    
    // public function login(Request $request)
    // {
    //     $credentials = $request->only('username', 'password');

    //     Log::info('Login attempt', [
    //         'username' => $credentials['username'] ?? null,
    //         'password_filled' => isset($credentials['password']) ? true : false,
    //     ]);

    //     if (Auth::attempt($credentials)) {
    //         Log::info('Login success', ['username' => $credentials['username']]);
    //         return redirect()->route('dashboard')->with('success', 'login successfull');;
    //     }

    //     Log::warning('Login failed', ['username' => $credentials['username'] ?? null]);
    //     return back()->with('error', 'Wrong username or password');
    // }
  public function login(Request $request)
{
    $request->validate([
        'username' => 'required',
        'password' => 'required',
    ]);

    Log::info('Login attempt', [
        'username' => $request->username,
    ]);

    $user = User::with('employee')
        ->where('username', $request->username)
        ->first();

    if (!$user) {
        Log::warning('Login failed - user not found', [
            'username' => $request->username
        ]);

        return back()->with('error', 'Wrong username or password');
    }

    // ❗ Cek relasi employee
    if (
        !$user->employee ||
        !in_array($user->employee->status, ['Active', 'Pending', 'Mutation'])
    ) {
        Log::warning('Login blocked - employee status', [
            'username' => $request->username,
            'status' => optional($user->employee)->status
        ]);

        return back()->with('error', 'Account is inactive');
    }

    // 🔐 Baru cek password
    if (!Auth::attempt($request->only('username', 'password'))) {
        Log::warning('Login failed - wrong password', [
            'username' => $request->username
        ]);

        return back()->with('error', 'Wrong username or password');
    }

    Log::info('Login success', [
        'username' => $request->username
    ]);

    return redirect()
        ->route('dashboard')
        ->with('success', 'Login successful');
}

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
