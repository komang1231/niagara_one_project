<?php

namespace App\Http\Controllers;

use App\Http\Requests\DepartemenRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Departemen;
use App\Services\CodeGenerator;

class DepartemenController extends Controller
{
    public function index(Request $request)
    {
        $startIndex = microtime(true);
        $previewKode = \App\Services\CodeGenerator::generate(\App\Models\Departemen::class, 'DEP');
        $statusOptions = [
            'aktif' => 'Aktif',
            'nonaktif' => 'Nonaktif',
        ];

        $departemen = $this->filter($request)->latest()->paginate(10)->withQueryString();

        if ($request->ajax() || $request->wantsJson()) {
            Log::debug('Departemen index timings', ['ajax' => true, 'ms' => round((microtime(true) - $startIndex) * 1000, 2)]);
            return view('components.table.table', compact('departemen'));
        }

        Log::debug('Departemen index timings', ['ajax' => false, 'ms' => round((microtime(true) - $startIndex) * 1000, 2)]);
        return view('departemen.index', compact('statusOptions', 'departemen', 'previewKode'));
    }

    private function filter(Request $request)
    {
        $search = $request->query('search');
        $status = $request->has('status')
            ? (array) $request->query('status', [])
            : null;

        return Departemen::query()
            ->when($search, fn($query) => $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('kode', 'like', "%{$search}%");
            }))
            ->when(!is_null($status), fn($query) => $query->whereIn('status', $status));
    }

    public function create()
    {
        // $previewKode = \App\Services\CodeGenerator::generate(\App\Models\Departemen::class, 'DEP');
        return view(
            'departemen.form-create'
            // ,compact('previewKode')
        );
    }

    public function store(DepartemenRequest $request)
    {
        $start = microtime(true);
        $data = $request->validated();
        $data['status'] = $data['status'] === '1' ? 'aktif' : 'nonaktif';

        $before = microtime(true);
        $departemen = Departemen::create($data);
        $after = microtime(true);

        Log::debug('Departemen store timings', [
            'total_ms' => round((microtime(true) - $start) * 1000, 2),
            'create_ms' => round(($after - $before) * 1000, 2),
            'id' => $departemen->id ?? null,
        ]);

        return redirect()->route('departemen.index')->with('success', 'Departemen berhasil ditambahkan.');
    }

    public function edit(Departemen $departemen)
    {
        return view('departemen.form-edit', compact('departemen'));
    }
    public function editData($id)
    {
        $departemen = Departemen::findOrFail($id);
        return response()->json([
            'id' => $departemen->id,
            'kode' => $departemen->kode,
            'nama' => $departemen->nama,
            'status' => $departemen->status,
        ]);
    }

    public function update(DepartemenRequest $request, Departemen $departemen)
    {
        $data = $request->validated();
        $data['status'] = $data['status'] === '1' ? 'aktif' : 'nonaktif';

        Log::debug('Data update departemen', [
            'id' => $departemen->id,
            'data' => $data,
        ]);

        $departemen->update($data);
        return redirect()->route('departemen.index')->with('success', 'Departemen berhasil diperbarui.');
    }

    public function toggleStatus(Departemen $departemen)
    {
        $departemen->update([
            'status' => $departemen->status === 'aktif' ? 'nonaktif' : 'aktif',
        ]);

        return response()->json([
            'success' => true,
            'status' => $departemen->status,
        ]);
    }

    public function destroy(Departemen $departemen)
    {
        $departemen->delete();
        return redirect()->route('departemen.index')->with('success', 'Departemen berhasil dihapus.');
    }

    public function trash()
    {
        $departemens = Departemen::onlyTrashed()->paginate(10);
        return view('departemen.trash', compact('departemens'));
    }

    public function restore($id)
    {
        Departemen::onlyTrashed()->findOrFail($id)->restore();
        return redirect()->route('departemen.trash')->with('success', 'Departemen berhasil dipulihkan.');
    }

    public function forceDelete($id)
    {
        Departemen::onlyTrashed()
            ->findOrFail($id)
            ->forceDelete();

        return redirect()
            ->route('departemen.index')
            ->with('success', 'Departemen berhasil dihapus permanen.');
    }
}
