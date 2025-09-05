<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Yajra\DataTables\DataTables;

class OrdersController extends Controller
{
    public function index(Request $request)
    {
        return spaRender($request, 'pages.admin.orders.index');
    }

    public function data(Request $request)
    {
        $orders = Order::with('user.profile', 'category');

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
                $detailUrl = route('admin.orders.show', $row->id);
                return "<a href='{$detailUrl}' class='btn btn-sm btn-info spa-link'>Detail</a>";
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function show(Request $request, Order $order)
    {
        $order->load('user.profile', 'category');

        return spaRender($request, 'pages.admin.orders.detail', [
            'order' => $order
        ]);
    }

    public function reject(Request $request, Order $order)
    {
        try {
            $request->validate([
                'reject_reason' => 'required|string|max:1000',
            ]);

            $order->files()->each(function ($file) {
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($file->filename)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($file->filename);
                }
                $file->delete();
            });

            $order->update([
                'status' => 'rejected',
                'reject_reason' => $request->reject_reason,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Pesanan berhasil ditolak dan file terkait telah dihapus.',
                'redirect' => route('admin.orders.view'),
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
