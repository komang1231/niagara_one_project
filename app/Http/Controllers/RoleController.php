<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\CodeGenerator;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $startIndex = microtime(true);

        $previewKode = CodeGenerator::generate(
            Role::class,
            'ROLE'
        );

        $statusOptions = [
            'aktif' => 'Aktif',
            'nonaktif' => 'Nonaktif',
        ];

        $role = $this->filter($request)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        if ($request->ajax() || $request->wantsJson()) {
            Log::debug('Role index timings', [
                'ajax' => true,
                'ms' => round(
                    (microtime(true) - $startIndex) * 1000,
                    2
                )
            ]);

            return view(
                'components.table.table',
                compact('role')
            );
        }

        Log::debug('Role index timings', [
            'ajax' => false,
            'ms' => round(
                (microtime(true) - $startIndex) * 1000,
                2
            )
        ]);

        return view(
            'role.index',
            compact(
                'statusOptions',
                'role',
                'previewKode'
            )
        );
    }


    private function filter(Request $request)
    {
        $search = $request->query('search');
        $status = $request->has('status')
            ? (array) $request->query('status', [])
            : null;

        return Role::query()
            ->when($search, fn($query) => $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('kode', 'like', "%{$search}%");
            }))
            ->when(!is_null($status), fn($query) => $query->whereIn('status', $status));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleRequest $request)
    {
        $start = microtime(true);
        $data = $request->validated();
        $data['status'] = $data['status'] === '1' ? 'aktif' : 'nonaktif';

        $before = microtime(true);
        $role = Role::create($data);
        $after = microtime(true);

        Log::debug('Role store timings', [
            'total_ms' => round((microtime(true) - $start) * 1000, 2),
            'create_ms' => round(($after - $before) * 1000, 2),
            'id' => $role->id ?? null,
        ]);

        return redirect()->route('role.index')->with('success', 'Role berhasil ditambahkan.');
    }

    public function edit(Role $role)
    {
        return view('role.form-edit', compact('role'));
    }
    public function editData($id)
    {
        $role = Role::findOrFail($id);
        return response()->json([
            'id' => $role->id,
            'kode' => $role->kode,
            'nama' => $role->nama,
            'status' => $role->status,
        ]);
    }

    public function update(RoleRequest $request, Role $role)
    {
        $data = $request->validated();
        $data['status'] = $data['status'] === '1' ? 'aktif' : 'nonaktif';

        $role->update($data);
        return redirect()->route('role.index')->with('success', 'Role berhasil diperbarui.');
    }

    public function toggleStatus(Role $role)
    {
        $role->update([
            'status' => $role->status === 'aktif' ? 'nonaktif' : 'aktif',
        ]);

        return response()->json([
            'success' => true,
            'status' => $role->status,
        ]);
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('role.index')->with('success', 'Role berhasil dihapus.');
    }

    public function trash()
    {
        $roles = Role::onlyTrashed()->paginate(10);
        return view('role.trash', compact('roles'));
    }

    public function restore($id)
    {
        Role::onlyTrashed()->findOrFail($id)->restore();
        return redirect()->route('role.index')->with('success', 'Role berhasil dipulihkan.');
    }

    public function forceDelete($id)
    {
        Role::onlyTrashed()
            ->findOrFail($id)
            ->forceDelete();

        return redirect()
            ->route('role.index')
            ->with('success', 'Role berhasil dihapus permanen.');
    }
}
