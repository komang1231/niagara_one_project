<?php

namespace App\Http\Controllers;

use App\Http\Requests\DivisiRequest;
use App\Models\Divisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\CodeGenerator;

class DivisiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $startIndex = microtime(true);
        $statusOptions = [
            'aktif' => 'Aktif',
            'nonaktif' => 'Nonaktif',
        ];

        $divisi = $this->filter($request)->latest()->paginate(10)->withQueryString();

        if ($request->ajax() || $request->wantsJson()) {
            Log::debug('Divisi index timings', ['ajax' => true, 'ms' => round((microtime(true) - $startIndex) * 1000, 2)]);
            return view('components.table.table', compact('divisi'));
        }

        Log::debug('Divisi index timings', ['ajax' => false, 'ms' => round((microtime(true) - $startIndex) * 1000, 2)]);
        return view('divisi.index', compact('statusOptions', 'divisi'));
    }

    private function filter(Request $request)
    {
        $search = $request->query('search');
        $status = $request->has('status')
            ? (array) $request->query('status', [])
            : null;

        return Divisi::query()
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
        $previewKode = \App\Services\CodeGenerator::generate(\App\Models\Divisi::class, 'DIV');
        return view('divisi.form-create', compact('previewKode'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $start = microtime(true);
        $data = $request->validated();
        $data['status'] = $data['status'] === '1' ? 'aktif' : 'nonaktif';

        $before = microtime(true);
        $divisi = Divisi::create($data);
        $after = microtime(true);

        Log::debug('Divisi store timings', [
            'total_ms' => round((microtime(true) - $start) * 1000, 2),
            'create_ms' => round(($after - $before) * 1000, 2),
            'id' => $divisi->id ?? null,
        ]);

        return redirect()->route('divisi.index')->with('success', 'Divisi berhasil ditambahkan.');
    }

    public function edit(Divisi $divisi)
    {
        return view('divisi.form-edit', compact('divisi'));
    }
    public function editData($id)
    {
        $divisi = Divisi::findOrFail($id);
        return response()->json([
            'id' => $divisi->id,
            'kode' => $divisi->kode,
            'nama' => $divisi->nama,
            'status' => $divisi->status,
        ]);
    }

    public function update(DivisiRequest $request, Divisi $divisi)
    {
        $data = $request->validated();
        $data['status'] = $data['status'] === '1' ? 'aktif' : 'nonaktif';

        $divisi->update($data);
        return redirect()->route('divisi.index')->with('success', 'Divisi berhasil diperbarui.');
    }

    public function toggleStatus(Divisi $divisi)
    {
        $divisi->update([
            'status' => $divisi->status === 'aktif' ? 'nonaktif' : 'aktif',
        ]);

        return response()->json([
            'success' => true,
            'status' => $divisi->status,
        ]);
    }

    public function destroy(Divisi $divisi)
    {
        $divisi->delete();
        return redirect()->route('divisi.index')->with('success', 'Divisi berhasil dihapus.');
    }

    public function trash()
    {
        $divisis = Divisi::onlyTrashed()->paginate(10);
        return view('divisi.trash', compact('divisis'));
    }

    public function restore($id)
    {
        Divisi::onlyTrashed()->findOrFail($id)->restore();
        return redirect()->route('divisi.index')->with('success', 'Divisi berhasil dipulihkan.');
    }
}
