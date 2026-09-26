<?php

namespace App\Http\Controllers;

use App\Http\Requests\RekrutmenRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use App\Models\Rekrutmen;
use App\Services\CodeGenerator;

class RekrutmenController extends Controller
{
    public function index(Request $request)
    {
        $startIndex = microtime(true);
        $previewKode = \App\Services\CodeGenerator::generate(\App\Models\Rekrutmen::class, 'REK');
        $statusOptions = [
            'aktif' => 'Aktif',
            'nonaktif' => 'Nonaktif',
        ];

        $rekrutmen = $this->filter($request)
            ->orderByDesc('kode') 
            // ->latest()
            ->paginate(10)
            ->withQueryString();

        if ($request->ajax() || $request->wantsJson()) {
            Log::debug('Rekrutmen index timings', ['ajax' => true, 'ms' => round((microtime(true) - $startIndex) * 1000, 2)]);
            return view('components.table.table', compact('rekrutmen'));
        }

        Log::debug('Rekrutmen index timings', ['ajax' => false, 'ms' => round((microtime(true) - $startIndex) * 1000, 2)]);
        return view('rekrutmen.index', compact('statusOptions', 'rekrutmen', 'previewKode'));
    }

    private function filter(Request $request)
    {
        $search = $request->query('search');
        $status = $request->has('status')
            ? (array) $request->query('status', [])
            : null;

        return Rekrutmen::query()
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

    public function store(RekrutmenRequest $request)
    {
        $start = microtime(true);
        $data = $request->validated();
        $data['status'] = $data['status'] === '1' ? 'aktif' : 'nonaktif';

        $before = microtime(true);
        $rekrutmen = Rekrutmen::create($data);
        $after = microtime(true);

        Log::debug('Rekrutmen store timings', [
            'total_ms' => round((microtime(true) - $start) * 1000, 2),
            'create_ms' => round(($after - $before) * 1000, 2),
            'id' => $rekrutmen->id ?? null,
        ]);

        return redirect()->route('rekrutmen.index')->with('success', 'Rekrutmen berhasil ditambahkan.');
    }

    public function edit(Rekrutmen $rekrutmen)
    {
        return view('rekrutmen.form-edit', compact('rekrutmen'));
    } 
    public function editData($id)
    {
        //protected $fillable = ['kode', 'lowongan_id', 'departement_id', 'divisi_id', 'section_id', 'job_position_id', 'job_level_id', 'cabang_kantor_id', 'nama', 'email', 'no_tlp', 'file_cv', 'jenjang_pendidikan_id', 'sumber_pelamar_id', 'status_rekrutmen', 'pool_talent', 'status'];
        $rekrutmen = Rekrutmen::findOrFail($id);
        return response()->json([
            'id' => $rekrutmen->id,
            'kode' => $rekrutmen->kode,
            'lowongan_id' => $rekrutmen->lowongan_id,
            'departement_id' => $rekrutmen->departement_id,
            'divisi_id' => $rekrutmen->divisi_id,
            'section_id' => $rekrutmen->section_id,
            'job_position_id' => $rekrutmen->job_position_id,
            'job_level_id' => $rekrutmen->job_level_id,
            'cabang_kantor_id' => $rekrutmen->cabang_kantor_id,
            'nama' => $rekrutmen->nama,
            'email' => $rekrutmen->email,
            'no_tlp' => $rekrutmen->no_tlp,
            'file_cv' => $rekrutmen->file_cv,
            'jenjang_pendidikan_id' => $rekrutmen->jenjang_pendidikan_id,
            'sumber_pelamar_id' => $rekrutmen->sumber_pelamar_id,
            'status_rekrutmen' => $rekrutmen->status_rekrutmen,
            'pool_talent' => $rekrutmen->pool_talent,
            'status' => $rekrutmen->status,
        ]);
    }

    public function update(RekrutmenRequest $request, Rekrutmen $rekrutmen)
    {
        $data = $request->validated();
        $data['status'] = $data['status'] === '1' ? 'aktif' : 'nonaktif';

        Log::debug('Data update rekrutmen', [
            'id' => $rekrutmen->id,
            'data' => $data,
        ]);

        $rekrutmen->update($data);
        return redirect()->route('rekrutmen.index')->with('success', 'Rekrutmen berhasil diperbarui.');
    }

    public function toggleStatus(Rekrutmen $rekrutmen)
    {
        $rekrutmen->update([
            'status' => $rekrutmen->status === 'aktif' ? 'nonaktif' : 'aktif',
        ]);

        return response()->json([
            'success' => true,
            'status' => $rekrutmen->status,
        ]);
    }

    public function destroy($id)
    {
        $rekrutmen = Rekrutmen::findOrFail($id);

        // if ($rekrutmen->divisi()->exists()) {
        //     return redirect()
        //         ->route('rekrutmen.index')
        //         ->with(
        //             'error',
        //             'Rekrutmen tidak dapat dihapus karena masih digunakan oleh data Divisi.'
        //         );
        // }

        $rekrutmen->delete();

        return redirect()
            ->route('rekrutmen.index')
            ->with(
                'success',
                'Rekrutmen berhasil dipindahkan ke Trash.'
            );
    }


    public function trash()
    {
        $rekrutmens = Rekrutmen::onlyTrashed()->paginate(10);
        return view('rekrutmen.trash', compact('rekrutmens'));
    }

    public function restore($id)
    {
        Rekrutmen::onlyTrashed()->findOrFail($id)->restore();
        return redirect()->route('rekrutmen.trash')->with('success', 'Rekrutmen berhasil dipulihkan.');
    }

    public function forceDelete($id)
    {
        Rekrutmen::onlyTrashed()
            ->findOrFail($id)
            ->forceDelete();

        return redirect()
            ->route('rekrutmen.trash')
            ->with('success', 'Rekrutmen berhasil dihapus permanen.');
    }
}
