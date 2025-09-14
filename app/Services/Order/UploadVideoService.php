<?php

namespace App\Services\Order;

use App\Models\File;
use getID3;
use App\Models\Order;
use App\Models\UploadCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class UploadVideoService
{
    public static function handle(Request $request, UploadCategory $category)
    {
        try {
            $request->validate([
                'file'  => 'required|mimetypes:video/mp4,video/avi,video/mpeg,video/quicktime,video/x-ms-wmv|max:153600', // max 150MB
                'display_type' => 'nullable|in:10,20,30,99',
                'notes' => 'nullable|string',
            ]);

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $getID3 = new getID3();
                $fileInfo = $getID3->analyze($file->getPathname());
                $duration = $fileInfo['playtime_seconds'] ?? 0;

                // if ($duration > 60) {
                //     return response()->json([
                //         'status' => 'error',
                //         'message' => 'Durasi video melebihi 60 detik.',
                //     ], 422);
                // }

                DB::beginTransaction();

                $order = Order::create([
                    'user_id' => Auth::user()->id,
                    'category_id' => $category->id,
                    'display_type' => $request->display_type ?? 10,
                    'notes' => $request->notes,
                ]);

                $path = $file->store('orders/video', 'public');
                // $filename = basename($path);

                File::create([
                    'order_id' => $order->id,
                    'filename' => $path,
                    'duration' => round($duration),
                    'size' => $file->getSize(),
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Order telah terkirim, tunggu admin memproses.',
            ]);
        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => implode(', ', $e->validator->errors()->all()),
            ], 422);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage() ?? 'Terjadi kesalahan',
            ], 500);
        }
    }
}
