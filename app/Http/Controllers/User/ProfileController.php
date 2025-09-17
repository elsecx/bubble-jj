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
        $data['videos'] = DataJJ::where('user_id', Auth::id())
            ->where('sts_active', true)
            ->get()
            ->groupBy('display_type');

        return spaRender($request, 'pages.user.profile', $data);
    }

    public function update(Request $request)
    {
        $user = User::findOrFail(Auth::id());
        $oldEmail = $user->email;

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'no_telp' => 'nullable|string|max:15',
            'username_1' => 'nullable|string|max:50',
            'username_2' => 'nullable|string|max:50',
            'picture_1' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'picture_2' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'picture_3' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'picture_4' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        $profile = $user->profile;
        $profile->no_telp = $request->no_telp;
        $profile->username_1 = $request->username_1;
        $profile->username_2 = $request->username_2;

        for ($i = 1; $i <= 4; $i++) {
            $field = "picture_$i";

            if ($request->hasFile($field) && $request->file($field)->isValid()) {
                $file = $request->$field;
                $baseName = preg_replace('/[^a-zA-Z0-9-_]/', '_', $profile->username_1);
                $fileName = "{$baseName}-slot{$i}." . $file->getClientOriginalExtension();

                if (
                    $profile->$field && $profile->$field !== 'default.jpg' &&
                    Storage::disk('public')->exists('profiles/' . $profile->$field)
                ) {
                    Storage::disk('public')->delete('profiles/' . $profile->$field);
                }

                $this->compressImage($file->getPathname(), $fileName, 75);

                $profile->$field = $fileName;
            }
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

    public function updatePicture(Request $request, $slot)
    {
        if (!in_array($slot, [1, 2, 3, 4])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Slot foto tidak valid.',
            ], 400);
        }

        $request->validate([
            'picture' => 'required|image|mimes:jpeg,png,jpg,gif',
        ]);

        $profile = Auth::user()->profile;
        $column = 'picture_' . $slot;

        $baseName = preg_replace('/[^a-zA-Z0-9-_]/', '_', $profile->username_1);

        if (
            $profile->$column && $profile->$column !== 'default.jpg' &&
            Storage::disk('public')->exists('profiles/' . $profile->$column)
        ) {
            Storage::disk('public')->delete('profiles/' . $profile->$column);
        }

        $file = $request->file('picture');
        $fileName = "{$baseName}-slot{$slot}." . $file->getClientOriginalExtension();

        $this->compressImage($file->getPathname(), $fileName, 75);

        $profile->$column = $fileName;
        $profile->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Foto profil berhasil diperbarui.',
        ]);
    }

    private function compressImage($source, $filename, $quality)
    {
        $info = getimagesize($source);
        if (!$info) {
            return false;
        }

        $tmpFile = tempnam(sys_get_temp_dir(), 'img_');
        $image = null;

        switch ($info['mime']) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($source);
                imagejpeg($image, $tmpFile, $quality);
                break;

            case 'image/png':
                $image = imagecreatefrompng($source);
                $pngQuality = (int)((100 - $quality) / 10);
                imagepng($image, $tmpFile, $pngQuality);
                break;

            case 'image/gif':
                $image = imagecreatefromgif($source);
                imagegif($image, $tmpFile);
                break;

            default:
                return false;
        }

        if ($image instanceof \GdImage) {
            imagedestroy($image);
        }

        $data = file_get_contents($tmpFile);
        if ($data === false) {
            @unlink($tmpFile);
            return false;
        }

        $result = Storage::disk('public')->put('profiles/' . $filename, $data);

        @unlink($tmpFile);
        return $result;
    }

    public function destroyVideoJJ($type)
    {
        $video = DataJJ::where('user_id', Auth::id())->where('display_type', $type)->where('sts_active', true)->firstOrFail();

        Storage::disk('public')->delete('videojj/' . $video->filename);
        $video->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Video berhasil dihapus.'
        ]);
    }
}
