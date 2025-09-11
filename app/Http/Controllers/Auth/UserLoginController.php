<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserLoginController extends Controller
{
    // Login view for 'user' role
    public function loginForm(Request $request)
    {
        return spaRender($request, 'pages.auth.user');
    }

    // Login logic for 'user' role by automatically creating a dummy user account
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'no_telp'  => 'required|string|numeric',
        ]);

        $profile = Profile::where('username_1', $request->username)->where('no_telp', $request->no_telp)->first();

        // Validation: Username exists but phone number does not match validation
        if (Profile::where('username_1', $request->username)->where('no_telp', '!=', $request->no_telp)->exists()) {
            return response()->json([
                'status' => 'invalid',
                'message' => 'Nomor Whatsapp tidak cocok dengan username ini.',
            ]);
        }

        // Validation: No_telp exists but username does not match
        if (Profile::where('no_telp', $request->no_telp)->where('username_1', '!=', $request->username)->exists()) {
            return response()->json([
                'status' => 'invalid',
                'message' => 'Username tidak cocok dengan Nomor Whatsapp ini.',
            ]);
        }

        if (!$profile) {
            return response()->json([
                'status' => 'setpassword_required',
                'message' => 'Akun belum terdaftar! Silahkan lengkapi data',
                'redirect' => route('password.set.view', [
                    'username' => $request->username,
                    'no_telp' => $request->no_telp,
                ]),
            ]);
        } else {
            $user = $profile->user;
            Auth::login($user);

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil login!',
                'redirect' => route($user->role->direct ?? 'user.dashboard'),
            ]);
        }
    }

    // Login logic for 'user' role with register and email verification
    public function loginOther(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'no_telp'  => 'required|string|numeric',
        ]);

        $profile = Profile::where('username_1', $request->username)->where('no_telp', $request->no_telp)->first();

        if (!$profile) {
            return response()->json([
                'status' => 'register_required',
                'message' => 'Akun belum terdaftar! Silahkan lengkapi data',
                'redirect' => route('register.view', [
                    'username' => $request->username,
                    'no_telp' => $request->no_telp,
                ]),
            ]);
        } else {
            $user = $profile->user;
            Auth::login($user);

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil login!',
                'redirect' => route($user->role->direct ?? 'user.dashboard'),
            ]);
        }
    }
}
