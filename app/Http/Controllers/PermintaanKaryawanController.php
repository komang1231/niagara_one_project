<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\PermintaanKaryawanRequest;
use App\Models\PermintaanKaryawan;
use App\Models\Divisi;
use App\Models\Section;
use App\Models\JobPosition;

class PermintaanKaryawanController extends Controller
{
    private function filter(Request $request)
    {
        $search = $request->query('search');

        return PermintaanKaryawan::query()
            ->when($search, fn($query) => $query->where(function ($q) use ($search) {
                $q->where('kode', 'like', "%{$search}%");
            }));
    }

    public function create()
    {
        //
    }

    public function store(PermintaanKaryawanRequest $request)
    {
        $start = microtime(true);
        $data = $request->validated();

        $data['karyawan_id'] = auth()->user()->karyawan_id;

        // Super Admin langsung disetujui.
        if (auth()->user()->role?->nama === 'Super Admin') {
            $data['processed_by'] = auth()->id();
            $data['approved_at'] = now();
            $data['rejected_at'] = null;
        }

        $before = microtime(true);
        $permintaanKaryawan = PermintaanKaryawan::create($data);
        $after = microtime(true);

        Log::debug('PermintaanKaryawan store timings', [
            'total_ms' => round((microtime(true) - $start) * 1000, 2),
            'create_ms' => round(($after - $before) * 1000, 2),
            'id' => $permintaanKaryawan->id ?? null,
        ]);

        $message = auth()->user()->role?->nama === 'Super Admin'
            ? 'Permintaan Karyawan berhasil diajukan dan langsung disetujui.'
            : 'Permintaan Karyawan berhasil ditambahkan.';

        return redirect()
            ->route('permintaan.index')
            ->with('success', $message);
    }

    public function edit(PermintaanKaryawan $permintaanKaryawan)
    {
        //
    }
    public function editData($id)
    {
        $permintaanKaryawan = PermintaanKaryawan::findOrFail($id);

        if (
            $permintaanKaryawan->approved_at ||
            $permintaanKaryawan->rejected_at
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Permintaan Karyawan yang sudah diproses tidak dapat diubah.',
            ], 422);
        }

        return response()->json([
            'success' => true,
            'id' => $permintaanKaryawan->id,
            'kode' => $permintaanKaryawan->kode,
            'karyawan_id' => $permintaanKaryawan->karyawan_id,
            'cabang_kantor_id' => $permintaanKaryawan->cabang_kantor_id,
            'departemen_id' => $permintaanKaryawan->departemen_id,
            'divisi_id' => $permintaanKaryawan->divisi_id,
            'section_id' => $permintaanKaryawan->section_id,
            'job_position_id' => $permintaanKaryawan->job_position_id,
            'job_level_id' => $permintaanKaryawan->job_level_id,
            'jumlah' => $permintaanKaryawan->jumlah,
        ]);
    }

    public function update(PermintaanKaryawanRequest $request, PermintaanKaryawan $permintaanKaryawan)
    {
        if (
            $permintaanKaryawan->approved_at || $permintaanKaryawan->rejected_at
        ) {
            return redirect()
                ->route('permintaan.index')
                ->with(
                    'error',
                    'Permintaan Karyawan yang sudah diproses tidak dapat diubah.'
                );
        }

        $data = $request->validated();

        Log::debug('Data update permintaan karyawan', [
            'id' => $permintaanKaryawan->id,
            'data' => $data,
        ]);

        $permintaanKaryawan->update($data);

        return redirect()
            ->route('permintaan.index')
            ->with(
                'success',
                'Permintaan Karyawan berhasil diperbarui.'
            );
    }

    public function destroy($id)
    {
        $permintaanKaryawan = PermintaanKaryawan::findOrFail($id);

        if (
            $permintaanKaryawan->approved_at ||
            $permintaanKaryawan->rejected_at
        ) {
            return redirect()
                ->route('permintaan-karyawan.index')
                ->with(
                    'error',
                    'Permintaan Karyawan yang sudah diproses tidak dapat dihapus.'
                );
        }

        $permintaanKaryawan->delete();

        return redirect()
            ->route('permintaan.index')
            ->with(
                'success',
                'Permintaan Karyawan berhasil dipindahkan ke Trash.'
            );
    }


    public function trash()
    {
        $permintaanKaryawans = PermintaanKaryawan::onlyTrashed()->paginate(10);
        return view('permintaan-karyawan.trash', compact('permintaanKaryawans'));
    }

    public function restore($id)
    {
        PermintaanKaryawan::onlyTrashed()->findOrFail($id)->restore();
        return redirect()->route('permintaan-karyawan.trash')->with('success', 'Permintaan Karyawan berhasil dipulihkan.');
    }

    public function forceDelete($id)
    {
        PermintaanKaryawan::onlyTrashed()
            ->findOrFail($id)
            ->forceDelete();

        return redirect()
            ->route('permintaan-karyawan.trash')
            ->with('success', 'Permintaan Karyawan berhasil dihapus permanen.');
    }

    public function getDivisi($departemenId)
    {
        $divisis = Divisi::where(
            'departemen_id',
            $departemenId
        )
            ->where('status', 'aktif')
            ->orderBy('nama')
            ->get();

        return response()->json($divisis);
    }

    public function getSection($divisiId)
    {
        $sections = Section::where(
            'divisi_id',
            $divisiId
        )
            ->where('status', 'aktif')
            ->orderBy('nama')
            ->get();

        return response()->json($sections);
    }

    public function getJobPosition($sectionId)
    {
        $jobPositions = JobPosition::where(
            'section_id',
            $sectionId
        )
            ->where('status', 'aktif')
            ->orderBy('nama')
            ->get();

        return response()->json($jobPositions);
    }
}
