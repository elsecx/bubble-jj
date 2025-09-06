<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\DataJJ;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $data['videos'] = DataJJ::where('user_id', Auth::id())->where('sts_active', true)->get()->groupBy('display_type');
        return spaRender($request, 'pages.user.profile', $data);
    }

    public function update(Request $request)
    {
        $user = User::findOrFail(Auth::id());

        $oldEmail = $user->email;

        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'no_telp' => 'nullable|string|max:15',
            'username_1' => 'nullable|string|max:50',
            'username_2' => 'nullable|string|max:50',
            'picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        $profile = $user->profile;
        $profile->no_telp = $request->no_telp;
        $profile->username_1 = $request->username_1;
        $profile->username_2 = $request->username_2;

        if ($request->hasFile('picture') && $request->file('picture')->isValid()) {
            $fileExtension = $request->picture->extension();
            $fileName = preg_replace('/[^a-zA-Z0-9-_]/', '_', $user->name) . '.' . $fileExtension;

            if (
                $profile->picture
                && $profile->picture !== 'default.jpg'
                && Storage::disk('public')->exists('profiles/' . $profile->picture)
            ) {
                Storage::disk('public')->delete('profiles/' . $profile->picture);
            }

            $request->picture->storeAs('profiles', $fileName, 'public');
            $profile->picture = $fileName;
        }

        $profile->save();

        if ($oldEmail !== $request->email) {
            return response()->json([
                'status'  => 'success',
                'message' => 'Profil diperbarui! Email baru memerlukan verifikasi. Silakan cek email Anda.',
                'redirect' => route('verification.notice'),
            ]);
        } else {
            return response()->json([
                'status'  => 'success',
                'message' => 'Profil berhasil diperbarui!',
                'redirect' => route('user.profile.view'),
            ]);
        }
    }
}
