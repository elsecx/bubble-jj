<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\UploadCategory;
use App\Services\Order as Order;
use Illuminate\Http\Request;

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
                return Order\UploadPhotosService::handle($request, $category);
                break;
            case 'video':
                return Order\UploadVideoService::handle($request, $category);
                break;
            case 'free':
                return Order\UploadFreeService::handle($request, $category);
                break;
            default:
                abort(404);
        }
    }
}
