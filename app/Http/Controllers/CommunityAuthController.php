<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class CommunityAuthController extends Controller
{
    public function form(Request $request)
    {
        return view('community.auth', ['register' => $request->routeIs('community.register')]);
    }

    public function login(Request $request)
    {
        $data = $request->validate(['email' => ['required', 'email', 'max:255'], 'password' => ['required', 'string', 'max:72']]);

        if (! Auth::attempt(['email' => Str::lower($data['email']), 'password' => $data['password'], 'is_admin' => false])) {
            throw ValidationException::withMessages(['email' => 'El correo o la contraseña son incorrectos.']);
        }

        $request->session()->regenerate();

        return redirect()->route('community.index');
    }

    public function register(Request $request)
    {
        $request->merge(['email' => Str::lower($request->string('email')->toString())]);
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', 'max:72', Password::min(10)],
            'privacy' => ['accepted'],
        ]);

        $user = new User;
        $user->forceFill([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'community_privacy_at' => now(),
            'community_privacy_version' => config('caith.privacy_version'),
        ])->save();
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('community.index');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
