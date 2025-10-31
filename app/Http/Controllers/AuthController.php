<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Employee;

class AuthController extends Controller
{
    /**
     * 🔹 Tampilkan halaman login
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * 🔹 Proses login untuk Employee
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:3',
        ]);

        // Gunakan guard default "web" yang sudah diarahkan ke model Employee di auth.php
        if (Auth::guard('web')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard')
                ->with('success', 'Selamat datang kembali, ' . Auth::user()->name . '!');
        }

        // Jika gagal, bisa bantu debug password salah
        $employee = Employee::where('email', $request->email)->first();
        if ($employee && !Hash::check($request->password, $employee->password)) {
            $error = 'Password salah.';
        } else {
            $error = 'Email tidak ditemukan.';
        }

        return back()->withErrors(['email' => $error])->withInput();
    }

    /**
     * 🔹 Logout Employee
     */
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Anda telah logout.');
    }

    /**
     * 🔹 (Opsional) Registrasi Employee baru
     */
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email',
            'password' => 'required|min:6|confirmed',
        ]);

        $employee = Employee::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'position' => 'admin',
            'status' => 'active',
        ]);

        Auth::login($employee);

        return redirect('/dashboard')->with('success', 'Akun berhasil dibuat dan login otomatis.');
    }
}
