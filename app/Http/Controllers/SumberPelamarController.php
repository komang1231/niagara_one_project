<?php

namespace App\Http\Controllers;

use App\Http\Requests\SumberPelamarRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use App\Models\SumberPelamar;
use App\Services\CodeGenerator;

class SumberPelamarController extends Controller
{
    public function index(Request $request)
    {
        $startIndex = microtime(true);
        $previewKode = \App\Services\CodeGenerator::generate(\App\Models\SumberPelamar::class, 'SUMBER_PELAMAR');
        $statusOptions = [
            'aktif' => 'Aktif',
            'nonaktif' => 'Nonaktif',
        ];

        $sumberPelamar = $this->filter($request)
            ->orderByDesc('kode')
            // ->latest()
            ->paginate(10)
            ->withQueryString();

        if ($request->ajax() || $request->wantsJson()) {
            Log::debug('SumberPelamar index timings', ['ajax' => true, 'ms' => round((microtime(true) - $startIndex) * 1000, 2)]);
            return view('components.table.table', compact('sumberPelamar'));
        }

        Log::debug('SumberPelamar index timings', ['ajax' => false, 'ms' => round((microtime(true) - $startIndex) * 1000, 2)]);
        return view('sumber-pelamar.index', compact('statusOptions', 'sumberPelamar', 'previewKode'));
    }

    private function filter(Request $request)
    {
        $search = $request->query('search');
        $status = $request->has('status')
            ? (array) $request->query('status', [])
            : null;

        return SumberPelamar::query()
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

    public function store(SumberPelamarRequest $request)
    {
        $start = microtime(true);
        $data = $request->validated();
        $data['status'] = $data['status'] === '1' ? 'aktif' : 'nonaktif';

        $before = microtime(true);
        $sumberPelamar = SumberPelamar::create($data);
        $after = microtime(true);

        Log::debug('SumberPelamar store timings', [
            'total_ms' => round((microtime(true) - $start) * 1000, 2),
            'create_ms' => round(($after - $before) * 1000, 2),
            'id' => $sumberPelamar->id ?? null,
        ]);

        return redirect()->route('sumber-pelamar.index')->with('success', 'Sumber Pelamar berhasil ditambahkan.');
    }

    public function edit(SumberPelamar $sumberPelamar)
    {
        return view('sumber-pelamar.form-edit', compact('sumberPelamar'));
    }
    public function editData($id)
    {
        $sumberPelamar = SumberPelamar::findOrFail($id);
        return response()->json([
            'id' => $sumberPelamar->id,
            'kode' => $sumberPelamar->kode,
            'nama' => $sumberPelamar->nama,
            'status' => $sumberPelamar->status,
        ]);
    }

    public function update(SumberPelamarRequest $request, SumberPelamar $sumberPelamar)
    {
        $data = $request->validated();
        $data['status'] = $data['status'] === '1' ? 'aktif' : 'nonaktif';

        Log::debug('Data update sumber pelamar', [
            'id' => $sumberPelamar->id,
            'data' => $data,
        ]);

        $sumberPelamar->update($data);
        return redirect()->route('sumber-pelamar.index')->with('success', 'Sumber Pelamar berhasil diperbarui.');
    }

    public function toggleStatus(SumberPelamar $sumberPelamar)
    {
        $sumberPelamar->update([
            'status' => $sumberPelamar->status === 'aktif' ? 'nonaktif' : 'aktif',
        ]);

        return response()->json([
            'success' => true,
            'status' => $sumberPelamar->status,
        ]);
    }

    public function destroy($id)
    {
        $sumberPelamar = SumberPelamar::findOrFail($id);

        if ($sumberPelamar->divisi()->exists()) {
            return redirect()
                ->route('sumber-pelamar.index')
                ->with(
                    'error',
                    'Sumber Pelamar tidak dapat dihapus karena masih digunakan oleh data Divisi.'
                );
        }

        $sumberPelamar->delete();

        return redirect()
            ->route('sumber-pelamar.index')
            ->with(
                'success',
                'Sumber Pelamar berhasil dipindahkan ke Trash.'
            );
    }


    public function trash()
    {
        $sumberPelamars = SumberPelamar::onlyTrashed()->paginate(10);
        return view('sumber-pelamar.trash', compact('sumberPelamars'));
    }

    public function restore($id)
    {
        SumberPelamar::onlyTrashed()->findOrFail($id)->restore();
        return redirect()->route('sumber-pelamar.trash')->with('success', 'Sumber Pelamar berhasil dipulihkan.');
    }

    public function forceDelete($id)
    {
        SumberPelamar::onlyTrashed()
            ->findOrFail($id)
            ->forceDelete();

        return redirect()
            ->route('sumber-pelamar.trash')
            ->with('success', 'Sumber Pelamar berhasil dihapus permanen.');
    }
}
