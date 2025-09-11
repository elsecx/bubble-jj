<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserPasswordController extends Controller
{
    // Set password view for 'user' role
    public function setForm(Request $request)
    {
        if (!$request->filled(['username', 'no_telp'])) {
            return redirect()->route('login');
        }

        return spaRender($request, 'pages.auth.setpassword');
    }

    // Set password logic for 'user' role
    public function set(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:profiles,username_1',
            'no_telp'  => 'required|string|numeric|unique:profiles,no_telp',
            'password' => 'required|string|min:6',
        ]);

        $dummyEmail = Str::random(10) . '@dummy.com';

        $user = User::create([
            'name' => $request->username,
            'email' => $dummyEmail,
            'password' => Hash::make($request->password),
            'email_verified_at' => now(),
            'role_id' => Role::where('name', 'user')->first()->id,
        ]);

        Profile::create([
            'username_1' => $request->username,
            'no_telp'    => $request->no_telp,
            'user_id'    => $user->id,
        ]);

        Auth::login($user);

        return response()->json([
            'status'  => 'success',
            'message' => 'Akun berhasil dibuat!',
            'redirect' => route($user->role->direct ?? 'user.dashboard'),
        ]);
    }

    public function check(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        $user = Auth::user();

        if (Hash::check($request->password, $user->password)) {
            $request->session()->put('password_confirmed_at', now());
            return response()->json([
                'status'  => 'success',
                'message' => 'Password benar.',
            ]);
        } else {
            return response()->json([
                'status'  => 'error',
                'message' => 'Password salah.',
            ], 422);
        }
    }

    public function status()
    {
        $confirmedAt = session('password_confirmed_at');
        $confirmed = $confirmedAt && $confirmedAt->diffInMinutes(now()) <= 5;

        return response()->json([
            'confirmed' => $confirmed
        ]);
    }
}
