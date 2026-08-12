<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate(['email' => 'required|email', 'password' => 'required']);
        if (Auth::attempt($data, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended('/cursos');
        }

return back()->withErrors(['email' => 'Las credenciales no son válidas.']);
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate(['name' => 'required|string|max:100', 'email' => 'required|email|unique:users', 'password' => 'required|min:8|confirmed']);
        $user = User::create($data);
        Auth::login($user);

        return redirect('/cursos');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();

        return redirect('/cursos');
    }
}
