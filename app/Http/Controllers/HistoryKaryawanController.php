<?php

namespace App\Http\Controllers;

use App\Http\Requests\HistoryKaryawanRequest;
use App\Models\CabangKantor;
use App\Models\Departemen;
use App\Models\Divisi;
use App\Models\HistoryKaryawan;
use App\Models\JobLevel;
use App\Models\JobPosition;
use App\Models\Karyawan;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HistoryKaryawanController extends Controller
{

    public function index(Request $request)
    {
        $previewKode = \App\Services\CodeGenerator::generate(\App\Models\HistoryKaryawan::class, 'SK');
        $jenisOptions = [
            'promosi' => 'Promosi',
            'demosi' => 'Demosi',
            'rotasi' => 'Rotasi',
            'mutasi' => 'Mutasi',
        ];

        $statusOptions = [
            'aktif' => 'Aktif',
            'nonaktif' => 'Nonaktif',
        ];

        $perubahanKaryawan = $this->filter($request)
            ->with([
                'karyawan',
                'departemenLama',
                'departemenBaru',
                'divisiLama',
                'divisiBaru',
                'sectionLama',
                'sectionBaru',
                'posisiLama',
                'posisiBaru',
                'levelLama',
                'levelBaru',
                'cabangLama',
                'cabangBaru',
            ])
            ->orderByDesc('tanggal_efektif')
            ->paginate(10)
            ->withQueryString();

        if ($request->ajax() || $request->wantsJson()) {
            return view(
                'components.table.table',
                compact('perubahanKaryawan')
            );
        }

        $karyawanOptions = Karyawan::query()
            ->with([
                'departemen',
                'divisi',
                'section',
                'jobPosition',
                'jobLevel',
                'cabangKantor',
            ])
            ->orderBy('nama')
            ->get();

        $departemenOptions = Departemen::orderBy('nama')
            ->pluck('nama', 'id');

        $divisiOptions = Divisi::orderBy('nama')
            ->pluck('nama', 'id');

        $sectionOptions = Section::orderBy('nama')
            ->pluck('nama', 'id');

        $posisiOptions = JobPosition::orderBy('nama')
            ->pluck('nama', 'id');

        $levelOptions = JobLevel::orderBy('nama')
            ->pluck('nama', 'id');

        $cabangOptions = CabangKantor::orderBy('nama')
            ->pluck('nama', 'id');
        // dd($departemenOptions);

        return view('perubahan-karyawan.index', compact(
            'previewKode',
            'perubahanKaryawan',
            'karyawanOptions',
            'departemenOptions',
            'divisiOptions',
            'sectionOptions',
            'posisiOptions',
            'levelOptions',
            'cabangOptions',
            'jenisOptions',
            'statusOptions',
        ));
    }

    private function filter(Request $request)
    {
        $search = $request->query('search');

        $jenis = $request->has('jenis')
            ? (array) $request->query('jenis', [])
            : null;

        $status = $request->has('status')
            ? (array) $request->query('status', [])
            : null;

        $efektif = $request->query('efektif');

        return HistoryKaryawan::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('kode', 'like', "%{$search}%")
                        ->orWhereHas('karyawan', function ($karyawan) use ($search) {
                            $karyawan
                                ->where('nama', 'like', "%{$search}%")
                                ->orWhere('nip', 'like', "%{$search}%");
                        });
                });
            })
            ->when(!is_null($jenis), function ($query) use ($jenis) {
                $query->whereIn('jenis_perubahan', $jenis);
            })
            ->when(!is_null($status), function ($query) use ($status) {
                $query->whereIn('status', $status);
            })
            ->when($efektif, function ($query) use ($efektif) {
                $tanggal = explode(' - ', $efektif);

                if (count($tanggal) === 2) {
                    $query->whereBetween('tanggal_efektif', [
                        $tanggal[0],
                        $tanggal[1],
                    ]);
                } else {
                    $query->whereDate('tanggal_efektif', $efektif);
                }
            });
    }

    public function store(HistoryKaryawanRequest $request)
    {
        $data = $request->validated();

        $karyawan = Karyawan::findOrFail($data['karyawan_id']);

        // Simpan data organisasi karyawan sebelum perubahan
        $data['cabang_lama'] = $karyawan->cabang_kantor_id;
        $data['departemen_lama'] = $karyawan->departemen_id;
        $data['divisi_lama'] = $karyawan->divisi_id;
        $data['section_lama'] = $karyawan->section_id;
        $data['posisi_lama'] = $karyawan->job_position_id;
        $data['level_lama'] = $karyawan->job_level_id;

        if ($request->hasFile('file_sk')) {
            $data['file_sk'] = $request
                ->file('file_sk')
                ->store('history-karyawan/sk', 'public');
        }

        HistoryKaryawan::create($data);

        return redirect()
            ->route('perubahan-karyawan.index')
            ->with('success', 'Perubahan karyawan berhasil ditambahkan.');
    }

    public function editData($id)
    {
        $history = HistoryKaryawan::with([
            'karyawan',
            'departemenLama',
            'departemenBaru',
            'divisiLama',
            'divisiBaru',
            'sectionLama',
            'sectionBaru',
            'posisiLama',
            'posisiBaru',
            'levelLama',
            'levelBaru',
            'cabangLama',
            'cabangBaru',
        ])->findOrFail($id);

        return response()->json([
            'id' => $history->id,

            // Kode SK dari HasGeneratedCode
            'kode' => $history->kode,

            // File SK
            'file_sk' => $history->file_sk,
            'file_sk_url' => $history->file_sk
                ? Storage::disk('public')->url($history->file_sk)
                : null,
            'file_sk_name' => $history->file_sk
                ? basename($history->file_sk)
                : null,

            // Karyawan
            'karyawan_id' => $history->karyawan_id,
            'karyawan_nama' => $history->karyawan?->nama,
            'karyawan_nip' => $history->karyawan?->nip,

            // Data lama
            'departemen_lama' => $history->departemen_lama,
            'departemen_lama_nama' => $history->departemenLama?->nama,

            'divisi_lama' => $history->divisi_lama,
            'divisi_lama_nama' => $history->divisiLama?->nama,

            'section_lama' => $history->section_lama,
            'section_lama_nama' => $history->sectionLama?->nama,

            'posisi_lama' => $history->posisi_lama,
            'posisi_lama_nama' => $history->posisiLama?->nama,

            'level_lama' => $history->level_lama,
            'level_lama_nama' => $history->levelLama?->nama,

            'cabang_lama' => $history->cabang_lama,
            'cabang_lama_nama' => $history->cabangLama?->nama,

            // Data baru
            'departemen_baru' => $history->departemen_baru,
            'departemen_baru_nama' => $history->departemenBaru?->nama,

            'divisi_baru' => $history->divisi_baru,
            'divisi_baru_nama' => $history->divisiBaru?->nama,

            'section_baru' => $history->section_baru,
            'section_baru_nama' => $history->sectionBaru?->nama,

            'posisi_baru' => $history->posisi_baru,
            'posisi_baru_nama' => $history->posisiBaru?->nama,

            'level_baru' => $history->level_baru,
            'level_baru_nama' => $history->levelBaru?->nama,

            'cabang_baru' => $history->cabang_baru,
            'cabang_baru_nama' => $history->cabangBaru?->nama,

            // Perubahan
            'jenis_perubahan' => $history->jenis_perubahan,
            'tanggal_efektif' => $history->tanggal_efektif,
            'status' => $history->status,
        ]);
    }

    public function update(
        HistoryKaryawanRequest $request,
        HistoryKaryawan $perubahanKaryawan
    ) {
        $data = $request->validated();

        if ($request->hasFile('file_sk')) {
            if ($perubahanKaryawan->file_sk) {
                Storage::disk('public')->delete($perubahanKaryawan->file_sk);
            }

            $data['file_sk'] = $request
                ->file('file_sk')
                ->store('history-karyawan/sk', 'public');
        }

        $perubahanKaryawan->update($data);

        return redirect()
            ->route('perubahan-karyawan.index')
            ->with('success', 'Perubahan karyawan berhasil diperbarui.');
    }

    public function toggleStatus(HistoryKaryawan $perubahanKaryawan)
    {
        dd([
        'id' => $perubahanKaryawan->id,
        'kode' => $perubahanKaryawan->kode,
        'status_sebelum' => $perubahanKaryawan->status,
    ]);
        $perubahanKaryawan->update([
            'status' => $perubahanKaryawan->status === 'aktif'
                ? 'nonaktif'
                : 'aktif',
        ]);

        return response()->json([
            'success' => true,
            'status' => $perubahanKaryawan->status,
        ]);
    }

    public function destroy($id)
    {
        $history = HistoryKaryawan::findOrFail($id);

        $history->delete();

        return redirect()
            ->route('perubahan-karyawan.index')
            ->with('success', 'History perubahan karyawan berhasil dipindahkan ke Trash.');
    }

    public function trash()
    {
        $perubahanKaryawan = HistoryKaryawan::onlyTrashed()
            ->with('karyawan')
            ->orderByDesc('deleted_at')
            ->paginate(10);

        return view(
            'perubahan-karyawan.trash',
            compact('perubahanKaryawan')
        );
    }

    public function restore($id)
    {
        HistoryKaryawan::onlyTrashed()
            ->findOrFail($id)
            ->restore();

        return redirect()
            ->route('perubahan-karyawan.trash')
            ->with('success', 'History perubahan karyawan berhasil dipulihkan.');
    }

    public function forceDelete($id)
    {
        $history = HistoryKaryawan::onlyTrashed()
            ->findOrFail($id);

        if ($history->file_sk) {
            Storage::disk('public')->delete($history->file_sk);
        }

        $history->forceDelete();

        return redirect()
            ->route('perubahan-karyawan.trash')
            ->with('success', 'History perubahan karyawan berhasil dihapus permanen.');
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
