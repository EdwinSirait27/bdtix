<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;


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

    $key = 'login-attempt:' . strtolower($request->username) . '|' . $request->ip();

    if (RateLimiter::tooManyAttempts($key, 5)) {
        $seconds = RateLimiter::availableIn($key);
        Log::warning('Login rate limited', ['username' => $request->username, 'ip' => $request->ip()]);
        return back()->with('error', "Too many failed attempts. Try again in {$seconds} seconds.");
    }

    if (! Auth::attempt($request->only('username', 'password'))) {
        RateLimiter::hit($key, 60); // lockout 60 detik setelah gagal
        Log::info('Login failed', ['username' => $request->username, 'ip' => $request->ip()]);
        return back()->with('error', 'Wrong username or password');
    }

    RateLimiter::clear($key);

    /** @var \App\Models\User $user */
        $user = Auth::user();

    $user = $user->load('employee');

    if (
        !$user->employee ||
        !in_array($user->employee->status, ['Active', 'Pending', 'Mutation'])
    ) {
        Auth::logout();
        return back()->with('error', 'Account is inactive');
    }

    if (! $user->hasAnyRole(['human', 'admin', 'executor'])) {
        Auth::logout();
        return back()->with('error', 'Account has no access role. Please contact Edwin Sirait.');
    }

    $request->session()->regenerate();

    Log::info('Login successful', ['username' => $request->username, 'ip' => $request->ip()]);

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
