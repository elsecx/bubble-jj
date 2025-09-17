<?php

namespace App\Http\Controllers\Admin;

use getID3;
use App\Http\Controllers\Controller;
use App\Models\DataJJ;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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
            ->editColumn('orderer', function ($order) {
                $username = $order->user->profile->username_1 ?? '-';
                $no_telp = $order->user->profile->no_telp ?? '-';
                return "<strong>{$username}</strong><br><small class='text-primary'>{$no_telp}</small>";
            })
            ->addColumn('category', fn($order) => $order->category->title)
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
                return "<span class='badge text-bg-{$row->status_color}'>{$row->status_label}</span>";
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at->format('d M Y H:i') . '<br><small>' . $row->created_at->diffForHumans() . '</small>';
            })
            ->addColumn('action', function ($row) {
                $detailUrl = route('admin.orders.show', $row->id);
                return "<a href='{$detailUrl}' class='btn btn-sm btn-info spa-link'>Detail</a>";
            })
            ->rawColumns(['orderer', 'status', 'created_at', 'action'])
            ->make(true);
    }

    public function show(Request $request, Order $order)
    {
        $order->load('user.profile', 'category');

        return spaRender($request, 'pages.admin.orders.detail', [
            'order' => $order
        ]);
    }

    public function result(Request $request, Order $order)
    {
        try {
            $request->validate([
                'file_result'   => 'required|mimes:mp4,avi,mpeg,quicktime,wmv|max:2560', // max 2.5MB
                'proof_payment' => 'required|image|max:2560', // max 2.5MB
            ]);

            if ($request->hasFile('file_result')) {
                $videoFile = $request->file('file_result');
                $proofFile = $request->file('proof_payment');

                $getID3 = new getID3();
                $fileInfo = $getID3->analyze($videoFile->getPathname());
                $duration = $fileInfo['playtime_seconds'] ?? 0;

                if ($duration > 60) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Durasi video melebihi 60 detik.',
                    ], 422);
                }

                DB::beginTransaction();

                $existing = DataJJ::where('user_id', $order->user_id)->where('username_1', $order->user->profile->username_1)->first();

                if ($existing) {
                    Storage::disk('public')->delete('videojj/' . $existing->filename);
                }

                $path = $videoFile->store('videojj', 'public');
                $filename = basename($path);

                $proofPath = $proofFile->store('bukti_trf', 'public');
                $proofFilename = basename($proofPath);

                $order->update([
                    'proof_payment' => $proofFilename,
                    'status'        => 'approved',
                ]);

                DataJJ::updateOrCreate(
                    [
                        'user_id' => $order->user_id,
                        'username_1' => $order->user->profile->username_1,
                    ],
                    [
                        'username_2' => $order->user->profile->username_2,
                        'display_type' => $order->display_type ?? 10,
                        'filename'   => $filename,
                        'duration'   => round($duration),
                        'size'       => $videoFile->getSize(),
                        'sts_active' => true,
                    ]
                );
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Video dan bukti transfer berhasil diupload!',
                'redirect' => route('admin.orders.view'),
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

    public function reject(Request $request, Order $order)
    {
        try {
            $request->validate([
                'reject_reason' => 'required|string|max:1000',
            ]);

            $order->files()->each(function ($file) {
                if (Storage::disk('public')->exists($file->filename)) {
                    Storage::disk('public')->delete($file->filename);
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
