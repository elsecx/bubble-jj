<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Order;
use App\Models\UploadCategory;
use App\Services\Order as OrderServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    public function handleView(Request $request, String $slug)
    {
        $menu = UploadCategory::where('slug', $slug)->firstOrFail();
        $data['menu'] = $menu;

        return spaRender($request, 'pages.user.order.' . $slug, $data);
    }

    public function handleService(Request $request, String $slug)
    {
        $category = UploadCategory::where('slug', $slug)->firstOrFail();

        switch ($category->slug) {
            case 'photo':
                return OrderServices\UploadPhotosService::handle($request, $category);
                break;
            case 'video':
                return OrderServices\UploadVideoService::handle($request, $category);
                break;
            case 'free':
                return OrderServices\UploadFreeService::handle($request, $category);
                break;
            default:
                abort(404);
        }
    }

    public function show(Request $request, Order $order)
    {
        $order->load('user.profile', 'category');

        return spaRender($request, 'pages.user.order.detail', [
            'order' => $order
        ]);
    }

    public function destroy(Request $request, Order $order)
    {
        if ($order->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized');
        }

        $order->files()->each(function ($file) {
            $file->delete();
        });

        $order->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Pesanan berhasil dihapus.',
            'redirect' => route('user.dashboard'),
        ]);
    }

    public function destroyFile(Request $request, File $file)
    {
        $order = $file->order;

        if ($order->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized');
        }

        if ($order->files()->count() <= 1) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tidak bisa menghapus file terakhir. Harus ada minimal satu file per pesanan.'
            ], 422);
        }

        if (Storage::exists($file->filename)) {
            Storage::delete($file->filename);
        }

        $file->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'File berhasil dihapus.'
        ]);
    }
}
