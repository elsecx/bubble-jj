<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\DataJJ;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\JpegEncoder;
use Intervention\Image\ImageManager;

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
        $profile = $user->profile;

        $checkUsername = function ($attribute, $value, $fail) use ($profile) {
            if (!filled($value)) return;

            $exists = DB::table('profiles')
                ->where(function ($q) use ($value) {
                    $q->where('username_1', $value)
                        ->orWhere('username_2', $value);
                })
                ->where('id', '<>', $profile->id)
                ->exists();

            if ($exists) {
                $fail("{$attribute} sudah digunakan.");
            }
        };

        $request->validate([
            'name' => 'required|string|max:255',
            'no_telp' => [
                'nullable',
                'string',
                'max:15',
                Rule::unique('profiles', 'no_telp')->ignore($profile->id),
            ],
            'username_1' => [
                'nullable',
                'string',
                'max:50',
                $checkUsername,
            ],
            'username_2' => [
                'nullable',
                'string',
                'max:50',
                $checkUsername,
            ],
        ]);

        $user->update([
            'name' => $request->name,
        ]);

        $profile->update([
            'no_telp'    => $request->no_telp,
            'username_1' => $request->username_1,
            'username_2' => $request->username_2,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Profil berhasil diperbarui!',
            'redirect' => route('user.profile.view'),
        ]);
    }

    public function updatePicture(Request $request, $slot)
    {
        $user = Auth::user();
        $profile = $user->profile;

        if (!in_array($slot, [1, 2, 3, 4])) {
            return response()->json(['status' => 'error', 'message' => 'Slot tidak valid.'], 422);
        }

        $request->validate([
            'picture' => 'required|image'
        ]);

        $column = 'picture_' . $slot;
        $oldPicture = $profile->{$column};

        if ($oldPicture && Storage::disk('public')->exists('profiles/' . $oldPicture)) {
            Storage::disk('public')->delete('profiles/' . $oldPicture);
        }

        $manager = new ImageManager(new Driver());
        $image = $manager->read($request->file('picture'))->encode(new JpegEncoder(quality: 75));

        $filename = uniqid() . '.jpg';
        Storage::disk('public')->put('profiles/' . $filename, (string) $image);

        $profile->{$column} = $filename;
        $profile->save();

        return response()->json([
            'status'  => 'success',
            'message' => 'Foto berhasil diperbarui',
            'url'     => asset('storage/profiles/' . $filename),
        ]);
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
