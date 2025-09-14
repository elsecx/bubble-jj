<?php

namespace App\Services\Order;

use App\Models\DataJJ;
use getID3;
use App\Models\UploadCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class UploadFreeService
{
    public static function handle(Request $request, UploadCategory $category)
    {
        try {
            $request->validate([
                'file' => 'required|mimetypes:video/mp4,video/avi,video/mpeg,video/quicktime,video/x-ms-wmv|max:2560', // max 2.5MB
                'display_type' => 'nullable|in:10,20,30,99',
            ]);

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $getID3 = new getID3();
                $fileInfo = $getID3->analyze($file->getPathname());
                $duration = $fileInfo['playtime_seconds'] ?? 0;

                if ($duration > 60) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Durasi video melebihi 60 detik.',
                    ], 422);
                }

                $path = $file->store('videojj', 'public');
                $filename = basename($path);

                $user = Auth::user();

                $existing = DataJJ::where('user_id', $user->id)->where('display_type', $request->display_type)->first();
                if ($existing) {
                    // concise: description for not existing video in data_jj
                    $existing->update([
                        'filename' => $filename,
                        'duration' => round($duration),
                        'size' => $file->getSize(),
                        'sts_active' => true,
                    ]);
                } else {
                    DataJJ::create([
                        'user_id' => $user->id,
                        'username_1' => $user->profile->username_1,
                        'username_2' => $user->profile->username_2,
                        'display_type' => $request->display_type ?? 10,
                        'filename' => $filename,
                        'duration' => round($duration),
                        'size' => $file->getSize(),
                        'sts_active' => true,
                    ]);
                }
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Video berhasil diupload!',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => implode(', ', $e->validator->errors()->all()),
            ], 422);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage() ?? 'Terjadi kesalahan',
            ], 500);
        }
    }
}
