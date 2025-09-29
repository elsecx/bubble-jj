<?php

namespace App\Services\Order;

use App\Models\DataJJ;
use getID3;
use App\Models\UploadCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Throwable;

class UploadFreeService
{
    public static function handle(Request $request, UploadCategory $category)
    {
        try {
            $request->validate([
                'file' => 'required|mimetypes:video/mp4,video/avi,video/mpeg,video/quicktime,video/x-ms-wmv|max:3584', // max 3MB
                'display_type' => 'nullable|in:10,20,30,99',
            ]);

            if (! $request->hasFile('file')) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'File tidak ditemukan.',
                ], 422);
            }

            $file = $request->file('file');
            $getID3 = new getID3();
            $fileInfo = $getID3->analyze($file->getPathname());
            $duration = isset($fileInfo['playtime_seconds']) ? (float) $fileInfo['playtime_seconds'] : 0;

            // if ($duration > 60) {
            //     return response()->json([
            //         'status' => 'error',
            //         'message' => 'Durasi video melebihi 60 detik.',
            //     ], 422);
            // }

            $path = $file->store('videojj', 'public');
            $filename = basename($path);

            $user = Auth::user();
            $displayType = $request->input('display_type', 10);

            $username1 = $user->profile->username_1 ?? null;
            $username2 = $user->profile->username_2 ?? null;

            $existing = DataJJ::where('user_id', $user->id)
                ->where('display_type', $displayType)
                ->first();

            $oldFilename = $existing->filename ?? null;

            try {
                DB::transaction(function () use ($existing, $user, $displayType, $filename, $duration, $file, $username1, $username2) {
                    if ($existing) {
                        $existing->update([
                            'username_1'  => $username1,
                            'username_2'  => $username2,
                            'filename'    => $filename,
                            'duration'    => round($duration),
                            'size'        => $file->getSize(),
                            'sts_active'  => true,
                        ]);
                    } else {
                        DataJJ::create([
                            'user_id'     => $user->id,
                            'username_1'  => $username1,
                            'username_2'  => $username2,
                            'display_type' => $displayType,
                            'filename'    => $filename,
                            'duration'    => round($duration),
                            'size'        => $file->getSize(),
                            'sts_active'  => true,
                        ]);
                    }
                });
            } catch (Throwable $e) {
                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
                throw $e;
            }

            if ($oldFilename && Storage::disk('public')->exists('videojj/' . $oldFilename)) {
                Storage::disk('public')->delete('videojj/' . $oldFilename);
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
        } catch (Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage() ?? 'Terjadi kesalahan',
            ], 500);
        }
    }
}
