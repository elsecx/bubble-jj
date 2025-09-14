<?php

namespace App\Services\Order;

use App\Models\File;
use App\Models\Order;
use App\Models\UploadCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class UploadPhotosService
{
    public static function handle(Request $request, UploadCategory $category)
    {
        try {
            $request->validate([
                'files' => 'required|array|min:1|max:5',
                'files.*' => 'image|max:153600', // max 150MB
                'display_type' => 'nullable|in:10,20,30,99',
                'notes'   => 'nullable|string',
            ]);

            if ($request->hasFile('files')) {
                DB::beginTransaction();

                $order = Order::create([
                    'user_id' => Auth::user()->id,
                    'category_id' => $category->id,
                    'display_type' => $request->display_type ?? 10,
                    'notes' => $request->notes,
                ]);

                foreach ($request->file('files') as $file) {
                    $path = $file->store('orders/photos', 'public');
                    // $filename = basename($path);

                    File::create([
                        'order_id' => $order->id,
                        'filename' => $path,
                        'duration' => null,
                        'size' => $file->getSize(),
                    ]);
                }
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
