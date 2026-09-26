<?php

namespace App\Http\Controllers;

use App\Http\Requests\LowonganRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use App\Models\Lowongan;
use App\Services\CodeGenerator;

class LowonganController extends Controller
{
    public function index(Request $request)
    {
        $startIndex = microtime(true);
        $previewKode = \App\Services\CodeGenerator::generate(\App\Models\Lowongan::class, 'LOW');
        $statusOptions = [
            'aktif' => 'Aktif',
            'nonaktif' => 'Nonaktif',
        ];

        $lowongan = $this->filter($request)
            ->orderByDesc('kode') 
            // ->latest()
            ->paginate(10)
            ->withQueryString();

        if ($request->ajax() || $request->wantsJson()) {
            Log::debug('Lowongan index timings', ['ajax' => true, 'ms' => round((microtime(true) - $startIndex) * 1000, 2)]);
            return view('components.table.table', compact('lowongan'));
        }

        Log::debug('Lowongan index timings', ['ajax' => false, 'ms' => round((microtime(true) - $startIndex) * 1000, 2)]);
        return view('lowongan.index', compact('statusOptions', 'lowongan', 'previewKode'));
    }

    private function filter(Request $request)
    {
        $search = $request->query('search');
        $status = $request->has('status')
            ? (array) $request->query('status', [])
            : null;

        return Lowongan::query()
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

    public function store(LowonganRequest $request)
    {
        $start = microtime(true);
        $data = $request->validated();
        $data['status'] = $data['status'] === '1' ? 'aktif' : 'nonaktif';

        $before = microtime(true);
        $lowongan = Lowongan::create($data);
        $after = microtime(true);

        Log::debug('Lowongan store timings', [
            'total_ms' => round((microtime(true) - $start) * 1000, 2),
            'create_ms' => round(($after - $before) * 1000, 2),
            'id' => $lowongan->id ?? null,
        ]);

        return redirect()->route('lowongan.index')->with('success', 'Lowongan berhasil ditambahkan.');
    }

    public function edit(Lowongan $lowongan)
    {
        return view('lowongan.form-edit', compact('lowongan'));
    }   
    public function editData($id)
    {
        //['kode', 'judul', 'permintaan_karyawan_id', 'cabang_kantor_id', 'departement_id', 'divisi_id', 'section_id', 'job_position_id', 'job_level_id', 'kuota', 'kualifikasi', 'deskripsi', 'min_gaji', 'max_gaji', 'tanggal_buka', 'tanggal_tutup', 'status']
        $lowongan = Lowongan::findOrFail($id);
        return response()->json([
            'id' => $lowongan->id,
            'kode' => $lowongan->kode,
            'judul' => $lowongan->judul,
            'permintaan_karyawan_id' => $lowongan->permintaan_karyawan_id,
            'cabang_kantor_id' => $lowongan->cabang_kantor_id,
            'department_id' => $lowongan->department_id,
            'divisi_id' => $lowongan->divisi_id,
            'section_id' => $lowongan->section_id,
            'job_position_id' => $lowongan->job_position_id,
            'job_level_id' => $lowongan->job_level_id,
            'kuota' => $lowongan->kuota,
            'kualifikasi' => $lowongan->kualifikasi,
            'deskripsi' => $lowongan->deskripsi,
            'min_gaji' => $lowongan->min_gaji,
            'max_gaji' => $lowongan->max_gaji,
            'tanggal_buka' => $lowongan->tanggal_buka,
            'tanggal_tutup' => $lowongan->tanggal_tutup,
            'status' => $lowongan->status,
        ]);
    }

    public function update(LowonganRequest $request, Lowongan $lowongan)
    {
        $data = $request->validated();
        $data['status'] = $data['status'] === '1' ? 'aktif' : 'nonaktif';

        Log::debug('Data update lowongan', [
            'id' => $lowongan->id,
            'data' => $data,
        ]);

        $lowongan->update($data);
        return redirect()->route('lowongan.index')->with('success', 'Lowongan berhasil diperbarui.');
    }

    public function toggleStatus(Lowongan $lowongan)
    {
        $lowongan->update([
            'status' => $lowongan->status === 'aktif' ? 'nonaktif' : 'aktif',
        ]);

        return response()->json([
            'success' => true,
            'status' => $lowongan->status,
        ]);
    }

    public function destroy($id)
    {
        $lowongan = Lowongan::findOrFail($id);

        // if ($lowongan->divisi()->exists()) {
        //     return redirect()
        //         ->route('lowongan.index')
        //         ->with(
        //             'error',
        //             'Lowongan tidak dapat dihapus karena masih digunakan oleh data Divisi.'
        //         );
        // }

        $lowongan->delete();

        return redirect()
            ->route('lowongan.index')
            ->with(
                'success',
                'Lowongan berhasil dipindahkan ke Trash.'
            );
    }


    public function trash()
    {
        $lowongans = Lowongan::onlyTrashed()->paginate(10);
        return view('lowongan.trash', compact('lowongans'));
    }

    public function restore($id)
    {
        Lowongan::onlyTrashed()->findOrFail($id)->restore();
        return redirect()->route('lowongan.trash')->with('success', 'Lowongan berhasil dipulihkan.');
    }

    public function forceDelete($id)
    {
        Lowongan::onlyTrashed()
            ->findOrFail($id)
            ->forceDelete();

        return redirect()
            ->route('lowongan.trash')
            ->with('success', 'Lowongan berhasil dihapus permanen.');
    }
}
