<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
       
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('username', 'password');

        
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); 
            $user = Auth::user();

            
            if ($user->role === 'dean' || $user->role === 'accounts') {
                return redirect()->intended('/dashboard');
            } 
           
            elseif ($user->role === 'admin') {
                return redirect()->intended('/admin/dashboard');
            } 
            
            else {
                return redirect('/');
            }
        }

        return back()->withErrors([
            'username' => 'Invalid credentials provided.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
