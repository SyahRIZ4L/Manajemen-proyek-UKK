<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    // Show login form
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Handle login
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('home')->with('success', 'Login berhasil! Selamat datang kembali.');
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->withInput($request->only('username'));
    }

    // Show register form
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Handle registration
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'full_name' => $request->full_name,
            'email' => $request->email,
            'role' => 'member', // Selalu member saat register
            'current_task_status' => 'idle'
        ]);

        Auth::login($user);

        return redirect('home')->with('success', 'Registrasi berhasil! Selamat bergabung.');
    }

    // Handle logout
    public function logout(Request $request)
    {
        try {
            // Get user name before logout
            $userName = Auth::user() ? Auth::user()->name : 'User';

            // Logout user
            Auth::logout();

            // Invalidate session
            $request->session()->invalidate();

            // Regenerate CSRF token
            $request->session()->regenerateToken();

            // Clear any remaining session data
            Session::flush();

            return redirect('/login')->with('success', "Logout berhasil! Terima kasih {$userName}, sampai jumpa lagi.");

        } catch (\Exception $e) {
            // If there's any error, still redirect to login
            return redirect('/login')->with('success', 'Logout berhasil!');
        }
    }
}
