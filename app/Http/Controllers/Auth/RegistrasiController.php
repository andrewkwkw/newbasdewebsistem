<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class RegistrasiController extends Controller
{
    public function showRegistrationForm()
    {
        $admins = User::where('role', 1)->get(); // role 1 = admin
        return view('auth.register', compact('admins'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'no_telpon' => 'required',
            'tempat' => 'required',
            'tanggal_lahir' => 'required|date',
            'fullname' => 'required',
            'admin_id' => 'required|exists:users,id',
        ]);

        User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'no_telpon' => $request->no_telpon,
            'tempat' => $request->tempat,
            'tanggal_lahir' => $request->tanggal_lahir,
            'fullname' => $request->fullname,
            'role' => 2, 
            'approve' => false, 
            'admin_id' => $request->admin_id,
            
            // <--- 2. TAMBAHKAN BARIS INI
            'qr_code' => 'KSP-' . strtoupper(Str::random(10)), 
        ]);

        return redirect('/login')->with('status', 'Registration successful! Please login.');
    }

}