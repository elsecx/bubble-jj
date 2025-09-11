<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;

class OperatorsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return spaRender($request, 'pages.admin.operators.index');
    }

    public function data(Request $request)
    {
        $role = Role::where('name', 'operator')->firstOrFail();
        $operators = User::with('role')->where('role_id', $role->id)->orderBy('created_at', 'desc');

        if ($request->status !== null && $request->status !== '') {
            $operators->where('is_active', $request->status);
        }

        return DataTables::of($operators)
            ->addIndexColumn()
            ->addColumn('name', fn($admin) => $admin->name)
            ->addColumn('email', fn($admin) => $admin->email)
            ->editColumn('status', function ($row) {
                return "<span class='badge text-bg-{$row->status_color}'>{$row->status_label}</span>";
            })
            ->editColumn('created_at', fn($row) => formatDate($row->created_at))
            ->addColumn('action', function ($row) {
                $detailUrl = route('admin.operators.show', $row->id);
                $editUrl = route('admin.operators.edit', $row->id);
                $deleteUrl = route('admin.operators.destroy', $row->id);

                return "
                    <a href='{$editUrl}' class='btn btn-sm btn-info spa-link'><i class='bi bi-pencil-square'></i></a>
                    <button class='btn btn-sm btn-danger btn-delete' data-url='{$deleteUrl}'><i class='bi bi-trash'></i></button>
                    <a href='{$detailUrl}' class='btn btn-sm btn-secondary spa-link'>Detail <i class='bi bi-arrow-right'></i></a>
                ";
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        return spaRender($request, 'pages.admin.operators.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $role = Role::where('name', 'operator')->firstOrFail();

        User::create([
            'name' => $request->name,
            'email' => $request->username,
            'password' => Hash::make($request->password),
            'role_id' => $role->id,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Operator berhasil ditambahkan.',
            'redirect' => route('admin.operators.index'),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, User $operator)
    {
        if ($operator->role->name !== 'operator') {
            abort(404);
        }

        $operator->load('role');

        return spaRender($request, 'pages.admin.operators.detail', [
            'operator' => $operator,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, User $operator)
    {
        if ($operator->role->name !== 'operator') {
            abort(404);
        }

        $operator->load('role');

        return spaRender($request, 'pages.admin.operators.edit', [
            'operator' => $operator,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $operator = User::findOrFail($id);

        if ($operator->role->name !== 'operator') {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak valid, hanya operator yang dapat diperbarui.'
            ], 400);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,email,' . $operator->id],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->username,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $operator->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Operator berhasil diperbarui.',
            'redirect' => route('admin.operators.index'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $operator = User::findOrFail($id);

        if ($operator->role->name !== 'operator') {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak valid, hanya operator yang dapat dihapus.'
            ], 400);
        }

        $operator->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Operator berhasil dihapus.',
            'redirect' => route('admin.operators.index'),
        ]);
    }
}
