<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    // Tampilkan Form Register
    public function showRegister()
    {
        return view('auth.register');
    }

    // Proses Register (Kirim data ke API /api/register)
    public function register(Request $request)
    {
        $response = Http::post('http://localhost:8000/api/...', [
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'password_confirmation' => $request->password_confirmation,
        ]);

        if ($response->successful()) {
            return redirect()->route('login')->with('success', 'Registrasi berhasil, silakan login.');
        }

        return back()->withErrors($response->json('errors'))->withInput();
    }

    // Tampilkan Form Login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Proses Login (Kirim data ke API /api/login)
    public function login(Request $request)
    {
        $response = Http::post('http://127.0.0.1:8000/api/login', [
            'email' => $request->email,
            'password' => $request->password,
        ]);

        if ($response->successful()) {
            $data = $response->json();
            
            // Simpan Bearer Token ke Session agar bisa dipakai di halaman lain
            session(['api_token' => $data['token']]);

            return redirect()->route('todos.index')->with('success', 'Login berhasil!');
        }

        return back()->with('error', 'Email atau password salah.')->withInput();
    }

    // Proses Logout
    public function logout(Request $request)
    {
        $token = session('api_token');

        if ($token) {
            Http::withToken($token)->post('http://127.0.0.1:8000/api/logout');
            session()->forget('api_token');
        }

        return redirect()->route('login')->with('success', 'Berhasil logout.');
    }
}
?>