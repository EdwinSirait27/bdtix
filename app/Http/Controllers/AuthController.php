<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;


class AuthController extends Controller
{
    private const LOCKOUT_SECONDS = 900;
    private const MAX_ATTEMPTS_PAIR = 5;
    private const MAX_ATTEMPTS_USER = 20;
    private const MAX_ATTEMPTS_IP = 50;

    public function loginPage()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:100',
            'password' => 'required|string|max:255',
        ]);

        $username = Str::lower(trim($request->input('username')));
        $ip = $request->ip();

        // Per pasangan username+IP, per username (botnet ke satu akun),
        // dan per IP (satu sumber mencoba banyak username).
        $limits = [
            'login:pair:' . sha1($username . '|' . $ip) => self::MAX_ATTEMPTS_PAIR,
            'login:user:' . sha1($username) => self::MAX_ATTEMPTS_USER,
            'login:ip:' . sha1($ip) => self::MAX_ATTEMPTS_IP,
        ];

        foreach ($limits as $key => $maxAttempts) {
            if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
                $seconds = RateLimiter::availableIn($key);
                Log::warning('Login rate limited', ['username' => $username, 'ip' => $ip]);

                return back()->with('error', "Too many failed attempts. Try again in {$seconds} seconds.");
            }
        }

        // Dicek sebelum Auth::attempt: jawabannya sama untuk akun yang ada maupun tidak,
        // jadi pesan ini tidak membocorkan akun mana yang masih memakai password default.
        if (Str::lower(trim($request->input('password'))) === $username) {
            foreach (array_keys($limits) as $key) {
                RateLimiter::hit($key, self::LOCKOUT_SECONDS);
            }
            Log::warning('Login blocked: password same as username', ['username' => $username, 'ip' => $ip]);

            return back()->with('error', 'Password tidak boleh sama dengan username. Silakan ganti password terlebih dahulu.');
        }

        if (! Auth::attempt($request->only('username', 'password'))) {
            foreach (array_keys($limits) as $key) {
                RateLimiter::hit($key, self::LOCKOUT_SECONDS);
            }
            Log::info('Login failed', ['username' => $username, 'ip' => $ip]);

            return back()->with('error', 'Wrong username or password');
        }

        // Penghitung IP sengaja tidak dihapus: penyerang yang punya satu akun
        // valid tidak boleh bisa me-reset limit IP-nya dengan login sendiri.
        RateLimiter::clear('login:pair:' . sha1($username . '|' . $ip));
        RateLimiter::clear('login:user:' . sha1($username));

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

        Log::info('Login successful', ['username' => $username, 'ip' => $ip]);

        return redirect()
            ->intended(route('dashboard'))
            ->with('success', 'Login successful');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
