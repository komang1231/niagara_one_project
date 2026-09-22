<?php

namespace App\Http\Controllers;

use App\Http\Requests\CabangKantorRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use App\Models\CabangKantor;
use App\Services\CodeGenerator;

class CabangKantorController extends Controller
{
    public function index(Request $request)
    {
        $startIndex = microtime(true);
        $previewKode = \App\Services\CodeGenerator::generate(\App\Models\CabangKantor::class, 'CABANG');
        $statusOptions = [
            'aktif' => 'Aktif',
            'nonaktif' => 'Nonaktif',
        ];

        $cabangKantor = $this->filter($request)
            ->orderByDesc('kode') 
            // ->latest()
            ->paginate(10)
            ->withQueryString();

        if ($request->ajax() || $request->wantsJson()) {
            Log::debug('CabangKantor index timings', ['ajax' => true, 'ms' => round((microtime(true) - $startIndex) * 1000, 2)]);
            return view('components.table.table', compact('cabangKantor'));
        }

        Log::debug('CabangKantor index timings', ['ajax' => false, 'ms' => round((microtime(true) - $startIndex) * 1000, 2)]);
        return view('cabang-kantor.index', compact('statusOptions', 'cabangKantor', 'previewKode'));
    }

    private function filter(Request $request)
    {
        $search = $request->query('search');
        $status = $request->has('status')
            ? (array) $request->query('status', [])
            : null;

        return CabangKantor::query()
            ->when($search, fn($query) => $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('kode', 'like', "%{$search}%");
            }))
            ->when(!is_null($status), fn($query) => $query->whereIn('status', $status));
    }

    public function create()
    {
        //
    }

    public function store(CabangKantorRequest $request)
    {
        $start = microtime(true);
        $data = $request->validated();
        $data['status'] = $data['status'] === '1' ? 'aktif' : 'nonaktif';

        $before = microtime(true);
        $cabangKantor = CabangKantor::create($data);
        $after = microtime(true);

        Log::debug('CabangKantor store timings', [
            'total_ms' => round((microtime(true) - $start) * 1000, 2),
            'create_ms' => round(($after - $before) * 1000, 2),
            'id' => $cabangKantor->id ?? null,
        ]);

        return redirect()->route('cabang-kantor.index')->with('success', 'Cabang Kantor berhasil ditambahkan.');
    }

    public function edit(CabangKantor $cabangKantor)
    {
        return view('cabang-kantor.form-edit', compact('cabangKantor'));
    }
    public function editData($id)
    {
        $cabangKantor = CabangKantor::findOrFail($id);
        return response()->json([
            'id' => $cabangKantor->id,
            'kode' => $cabangKantor->kode,
            'nama' => $cabangKantor->nama,
            'status' => $cabangKantor->status,
        ]);
    }

    public function update(CabangKantorRequest $request, CabangKantor $cabangKantor)
    {
        $data = $request->validated();
        $data['status'] = $data['status'] === '1' ? 'aktif' : 'nonaktif';

        Log::debug('Data update cabang kantor', [
            'id' => $cabangKantor->id,
            'data' => $data,
        ]);

        $cabangKantor->update($data);
        return redirect()->route('cabang-kantor.index')->with('success', 'Cabang Kantor berhasil diperbarui.');
    }

    public function toggleStatus(CabangKantor $cabangKantor)
    {
        $cabangKantor->update([
            'status' => $cabangKantor->status === 'aktif' ? 'nonaktif' : 'aktif',
        ]);

        return response()->json([
            'success' => true,
            'status' => $cabangKantor->status,
        ]);
    }

    public function destroy($id)
    {
        $cabangKantor = CabangKantor::findOrFail($id);

        if ($cabangKantor->karyawan()->exists()) {
            return redirect()
                ->route('cabang-kantor.index')
                ->with(
                    'error',
                    'Cabang Kantor tidak dapat dihapus karena masih digunakan oleh data Karyawan.'
                );
        }

        $cabangKantor->delete();

        return redirect()
            ->route('cabang-kantor.index')
            ->with(
                'success',
                'Cabang Kantor berhasil dipindahkan ke Trash.'
            );
    }


    public function trash()
    {
        $cabangKantors = CabangKantor::onlyTrashed()->paginate(10);
        return view('cabang-kantor.trash', compact('cabangKantors'));
    }

    public function restore($id)
    {
        CabangKantor::onlyTrashed()->findOrFail($id)->restore();
        return redirect()->route('cabang-kantor.trash')->with('success', 'Cabang Kantor berhasil dipulihkan.');
    }

    public function forceDelete($id)
    {
        CabangKantor::onlyTrashed()
            ->findOrFail($id)
            ->forceDelete();

        return redirect()
            ->route('cabang-kantor.trash')
            ->with('success', 'Cabang Kantor berhasil dihapus permanen.');
    }
}
