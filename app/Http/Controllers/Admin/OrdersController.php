<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class OrdersController extends Controller
{
    public function index(Request $request)
    {
        return spaRender($request, 'pages.admin.orders.index');
    }

    public function data(Request $request)
    {
        $orders = Order::with('user', 'category');

        if ($request->status) {
            $orders->where('status', $request->status);
        }

        $orders->orderByRaw("FIELD(status, 'pending') DESC")->orderBy('created_at', 'desc');

        return DataTables::of($orders)
            ->addIndexColumn()
            ->addColumn('username', fn($order) => $order->user->profile->username_1)
            ->addColumn('type', fn($order) => $order->category->title)
            ->editColumn('notes', function ($row) {
                if (!$row->notes) return '-';
                $words = explode(' ', $row->notes);
                $short = implode(' ', array_slice($words, 0, 5));
                if (count($words) > 5) {
                    $short .= '...';
                }
                return $short;
            })
            ->editColumn('status', function ($row) {
                $status = ucfirst($row->status);
                $badgeClass = match ($row->status) {
                    'approved' => 'bg-success',
                    'rejected' => 'bg-danger',
                    'pending'  => 'bg-warning',
                    default    => 'bg-secondary',
                };
                return "<span class='badge {$badgeClass}'>{$status}</span>";
            })
            ->editColumn('created_at', fn($row) => formatDate($row->created_at))
            ->addColumn('action', function ($row) {
                // $detailUrl = route('order.show', $row->id);
                return "<a href='#' class='btn btn-sm btn-info spa-link'>Detail</a>";
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }
}
