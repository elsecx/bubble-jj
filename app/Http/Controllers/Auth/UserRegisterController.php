<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserRegisterController extends Controller
{
    // Register view for 'user' role
    public function registerForm(Request $request)
    {
        if (!$request->filled(['username', 'no_telp'])) {
            return redirect()->route('login');
        }

        return spaRender($request, 'pages.auth.register');
    }

    // Register logic for 'user' role
    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:profiles,username_1',
            'no_telp'  => 'required|string|numeric|unique:profiles,no_telp',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => Role::where('name', 'user')->first()->id,
        ]);

        Profile::create([
            'username_1' => $request->username,
            'no_telp'    => $request->no_telp,
            'user_id'    => $user->id,
        ]);

        // Trigger event so Laravel sends verification email
        event(new Registered($user));

        Auth::login($user);

        return response()->json([
            'status'  => 'success',
            'message' => 'Registrasi berhasil! Silakan cek email untuk verifikasi.',
            'redirect' => route('verification.notice'),
        ]);
    }
}
