<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DataJJ;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DataController extends Controller
{
    public function index(Request $request)
    {
        $allowedOrigins = [
            'https://livetok.online',
            'https://jedagjedug.com',
        ];
        $origin = $request->headers->get('Origin');

        if (!in_array($origin, $allowedOrigins)) {
            return response()->json([
                'status'  => 'error',
                'code'    => 403,
                'message' => 'Forbidden: Invalid Origin',
            ], 403);
        }

        // --- Ambil data lokal ---
        $role_user = Role::where('name', 'user')->first();
        $users = User::with(['profile', 'videos'])->where('role_id', $role_user->id)->get();

        $localData = $users->map(function ($user) {
            $profile = $user->profile;

            return [
                'source'      => 'jedagjedug.com',
                'username_1'  => $profile->username_1,
                'username_2'  => $profile->username_2,
                'gift_count'  => $profile->gift_count ?? 0,
                'photos'      => [
                    'picture_1' => $profile->picture_1 ? asset('storage/profiles/' . $profile->picture_1) : null,
                    'picture_2' => $profile->picture_2 ? asset('storage/profiles/' . $profile->picture_2) : null,
                    'picture_3' => $profile->picture_3 ? asset('storage/profiles/' . $profile->picture_3) : null,
                    'picture_4' => $profile->picture_4 ? asset('storage/profiles/' . $profile->picture_4) : null,
                ],
                'videos'      => collect(DataJJ::DISPLAY_TYPES)->mapWithKeys(function ($label, $slot) use ($user) {
                    $video = $user->videos->firstWhere('display_type', $slot);
                    return [
                        $slot => [
                            'label' => $label,
                            'url'   => $video ? url('storage/videojj/' . $video->filename) : null,
                        ]
                    ];
                }),
            ];
        })->values();

        // --- Hit API eksternal ---
        $externalResponse = Http::withHeaders([
            'Origin' => 'https://livetok.online',
            'Content-Type' => 'application/json',
        ])->post('https://play.livetok.online/api/get_video_jj', [
            // payload kalau ada, misalnya:
            // 'param1' => 'value1',
        ]);
        $externalData = collect();

        if ($externalResponse->successful()) {
            $json = $externalResponse->json();

            if (!empty($json['sts'])) {
                $externalData = collect($json['sts'])->map(function ($item) {
                    return [
                        'source'     => 'livetok.online',
                        'username'   => $item['username'],
                        'videos'     => collect(DataJJ::DISPLAY_TYPES)->mapWithKeys(function ($label, $slot) use ($item) {
                            // default: simpan ke slot 10, lainnya null
                            $videoUrl = null;
                            if ($slot == 10 && !empty($item['videos'])) {
                                $videoFile = collect($item['videos'])->first(); // ambil pertama
                                $videoUrl  = $videoFile ? url('storage/videojj/' . $videoFile) : null;
                            }

                            return [
                                $slot => [
                                    'label' => $label,
                                    'url'   => $videoUrl,
                                ]
                            ];
                        }),
                    ];
                });
            }
        }

        // --- Gabungkan hasil lokal + eksternal ---
        $finalData = $localData->merge($externalData);

        return response()->json([
            'status'  => 'success',
            'code'    => 200,
            'message' => 'Get data successfully',
            'data'    => $finalData,
        ]);
    }
}
