<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    public function loginForm(Request $request)
    {
        return spaRender($request, 'pages.auth.admin');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $role = Role::where('name', 'admin')->first();
        $admin = User::where('email', $request->email)->where('role_id', $role->id)->first();

        if (!$admin) {
            return response()->json([
                'status' => 'error',
                'message' => 'Username tidak ditemukan',
            ]);
        }

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Password salah!',
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil login!',
            'redirect' => route($admin->role->direct ?? 'admin.dashboard'),
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
