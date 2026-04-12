<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin() { return view('auth.login'); }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            return match($user->role) {
                'admin'  => redirect()->route('admin.dashboard'),
                'dokter' => redirect()->route('dokter.dashboard'),
                default  => redirect()->route('pasien.dashboard'),
            };
        }
        return back()->withErrors(['email' => 'Email atau Password Salah!']);
    }

    public function showRegister() { return view('auth.register'); }

    public function register(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|confirmed',
            'alamat' => 'required',
            'no_ktp' => 'required',
            'no_hp' => 'required',
        ]);

        User::create([
            'name' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'alamat' => $request->alamat,
            'no_ktp' => $request->no_ktp,
            'no_hp' => $request->no_hp,
            'role' => 'pasien',
        ]);

        return redirect()->route('login');
    }

    public function logout() { Auth::logout(); return redirect()->route('login'); }
}