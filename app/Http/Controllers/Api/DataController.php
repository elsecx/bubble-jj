<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DataJJ;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class DataController extends Controller
{
    public function index()
    {
        $role_user = Role::where('name', 'user')->first();
        $users = User::with(['profile', 'videos'])->where('role_id', $role_user->id)->get();

        $data = $users->map(function ($user) {
            $profile = $user->profile;

            return [
                'username_1' => $profile?->username_1,
                'username_2' => $profile?->username_2,
                'photos' => [
                    'picture_1' => $user->profile->picture_1 ? asset('storage/profiles/' . $user->profile->picture_1) : null,
                    'picture_2' => $user->profile->picture_2 ? asset('storage/profiles/' . $user->profile->picture_2) : null,
                    'picture_3' => $user->profile->picture_3 ? asset('storage/profiles/' . $user->profile->picture_3) : null,
                    'picture_4' => $user->profile->picture_4 ? asset('storage/profiles/' . $user->profile->picture_4) : null,
                ],
                'videos' => collect(DataJJ::DISPLAY_TYPES)->map(function ($label, $slot) use ($user) {
                    $video = $user->videos->firstWhere('display_type', $slot);
                    return [
                        'label' => $label,
                        'url'   => $video ? url('storage/videojj/' . $video->filename) : null,
                    ];
                }),

            ];
        })->values();

        return response()->json([
            'status'  => 'success',
            'code'    => 200,
            'message' => 'Get data successfully',
            'data'    => $data,
        ]);
    }
}
