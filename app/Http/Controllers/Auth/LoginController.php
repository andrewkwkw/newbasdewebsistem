<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // $user = Auth::user();

            // if (!$user->approve) {
            //     Auth::logout();
            //     return redirect()->back()->withErrors(['email' => 'Akun Anda belum disetujui oleh admin.']);
            // }

            // if ($user->role == 1) {
            //     return redirect()->intended('/admin/dashboard');
            // } elseif ($user->role == 2) {
            //     return redirect()->intended('/user/dashboard');
            // }
        }

        return redirect()->back()->withErrors(['email' => 'Email atau password salah.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
