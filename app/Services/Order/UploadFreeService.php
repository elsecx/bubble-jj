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
            // Logic upload free here
        } catch (ValidationException $e) {
            $message = implode(', ', $e->validator->errors()->all());

            return response()->json([
                'status' => 'error',
                'message' => $message,
            ], 422);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage() ?? 'Terjadi kesalahan',
            ], 500);
        }
    }
}
