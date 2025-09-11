<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\DataJJ;
use App\Models\Order;
use App\Models\UploadCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $categories = UploadCategory::rememberCache('categories_all', 3600, function () {
            return UploadCategory::all();
        });

        $orders = Order::with('category')->where('user_id', Auth::user()->id)->orderBy('updated_at', 'desc')->get();
        $videos = DataJJ::where('user_id', Auth::id())->where('sts_active', true)->get()->groupBy('display_type');

        $data = [
            'categories' => $categories,
            'orders' => $orders,
            'videos' => $videos,
        ];

        return spaRender($request, 'pages.user.dashboard', $data);
    }
}
