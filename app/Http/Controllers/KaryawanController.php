<?php

namespace App\Http\Controllers;

use App\Http\Requests\KaryawanRequest;
use App\Models\Karyawan;
use App\Models\Lowongan;
use App\Models\Departemen;
use App\Models\Divisi;
use App\Models\Section;
use App\Models\JobPosition;
use App\Models\JobLevel;
use App\Models\CabangKantor;
use App\Models\JenjangPendidikan;
use App\Models\StatusKawin;
use App\Models\Agama;
use App\Models\StatusKepegawaian;
use App\Models\Bank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\CodeGenerator;

class KaryawanController extends Controller
{
    public function index(Request $request)
    {
        $startIndex = microtime(true);

        $previewNip = CodeGenerator::generate(
            Karyawan::class,
            'NIP',
            'nip'
        );

        $statusOptions = [
            'aktif' => 'Aktif',
            'nonaktif' => 'Nonaktif',
        ];

        $karyawan = $this->filter($request)
            ->with([
                'departemen',
                'divisi',
                'section',
                'jobPosition',
                'jobLevel',
                'statusKepegawaian',
            ])
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $departemens = Departemen::where('status', 'aktif')
            ->orderBy('nama')
            ->get();

        $jobPositions = JobPosition::where('status', 'aktif')
            ->orderBy('nama')
            ->get();

        $jobLevels = JobLevel::where('status', 'aktif')
            ->orderBy('nama')
            ->get();

        $cabangKantors = CabangKantor::where('status', 'aktif')
            ->orderBy('nama')
            ->get();

        $jenjangPendidikans = JenjangPendidikan::where('status', 'aktif')
            ->orderBy('nama')
            ->get();

        $statusKawins = StatusKawin::where('status', 'aktif')
            ->orderBy('nama')
            ->get();

        $agamas = Agama::where('status', 'aktif')
            ->orderBy('nama')
            ->get();

        $statusKepegawaians = StatusKepegawaian::where('status', 'aktif')
            ->orderBy('nama')
            ->get();

        $banks = Bank::where('status', 'aktif')
            ->orderBy('nama')
            ->get();

        $lowongans = Lowongan::where('status', 'aktif')
            ->orderBy('id', 'desc')
            ->get();

        if ($request->ajax() || $request->wantsJson()) {
            Log::debug('Karyawan index timings', [
                'ajax' => true,
                'ms' => round(
                    (microtime(true) - $startIndex) * 1000,
                    2
                ),
            ]);

            return view(
                'components.table.table',
                compact('karyawan')
            );
        }

        Log::debug('Karyawan index timings', [
            'ajax' => false,
            'ms' => round(
                (microtime(true) - $startIndex) * 1000,
                2
            ),
        ]);

        return view('karyawan.index', compact(
            'karyawan',
            'statusOptions',
            'previewNip',
            'departemens',
            'jobPositions',
            'jobLevels',
            'cabangKantors',
            'jenjangPendidikans',
            'statusKawins',
            'agamas',
            'statusKepegawaians',
            'banks',
            'lowongans'
        ));
    }

    private function filter(Request $request)
    {
        $search = $request->query('search');

        $status = $request->has('status')
            ? (array) $request->query('status', [])
            : null;

        return Karyawan::query()
            ->when($search, fn($query) => $query->where(function ($q) use ($search) {
                $q->where('nip', 'like', "%{$search}%")
                    ->orWhere('nama', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%");
            }))
            ->when(
                !is_null($status),
                fn($query) => $query->whereIn('status', $status)
            );
    }

    public function store(KaryawanRequest $request)
    {
        $start = microtime(true);

        $data = $request->validated();

        $before = microtime(true);

        $karyawan = Karyawan::create($data);

        $after = microtime(true);

        Log::debug('Karyawan store timings', [
            'total_ms' => round(
                (microtime(true) - $start) * 1000,
                2
            ),
            'create_ms' => round(
                ($after - $before) * 1000,
                2
            ),
            'id' => $karyawan->id ?? null,
        ]);

        return redirect()
            ->route('karyawan.index')
            ->with('success', 'Karyawan berhasil ditambahkan.');
    }

    public function editData($id)
    {
        $karyawan = Karyawan::findOrFail($id);

        return response()->json([
            'id' => $karyawan->id,
            'nip' => $karyawan->nip,
            'rekrutmen_id' => $karyawan->rekrutmen_id,
            'lowongan_id' => $karyawan->lowongan_id,
            'departemen_id' => $karyawan->departemen_id,
            'divisi_id' => $karyawan->divisi_id,
            'section_id' => $karyawan->section_id,
            'job_position_id' => $karyawan->job_position_id,
            'job_level_id' => $karyawan->job_level_id,
            'cabang_kantor_id' => $karyawan->cabang_kantor_id,
            'gaji' => $karyawan->gaji,
            'nama' => $karyawan->nama,
            'email' => $karyawan->email,
            'no_tlp' => $karyawan->no_tlp,
            'nik' => $karyawan->nik,
            'no_bpjs_ketenagakerjaan' => $karyawan->no_bpjs_ketenagakerjaan,
            'no_bpjs_kesehatan' => $karyawan->no_bpjs_kesehatan,
            'no_npwp' => $karyawan->no_npwp,
            'jenjang_pendidikan_id' => $karyawan->jenjang_pendidikan_id,
            'status_kawin_id' => $karyawan->status_kawin_id,
            'agama_id' => $karyawan->agama_id,
            'status_kepegawaian_id' => $karyawan->status_kepegawaian_id,
            'bank_id' => $karyawan->bank_id,
            'nama_bank' => $karyawan->nama_bank,
            'no_rekening' => $karyawan->no_rekening,
            'status' => $karyawan->status,
        ]);
    }

    public function update(
        KaryawanRequest $request,
        Karyawan $karyawan
    ) {
        $data = $request->validated();

        Log::debug('Data update karyawan', [
            'id' => $karyawan->id,
            'data' => $data,
        ]);

        $karyawan->update($data);

        return redirect()
            ->route('karyawan.index')
            ->with('success', 'Karyawan berhasil diperbarui.');
    }

    public function toggleStatus(Karyawan $karyawan)
    {
        $karyawan->update([
            'status' => $karyawan->status === 'aktif'
                ? 'nonaktif'
                : 'aktif',
        ]);

        return response()->json([
            'success' => true,
            'status' => $karyawan->status,
        ]);
    }

    public function destroy(Karyawan $karyawan)
    {
        $karyawan->delete();

        return redirect()
            ->route('karyawan.index')
            ->with(
                'success',
                'Karyawan berhasil dihapus.'
            );
    }

    public function trash()
    {
        $karyawans = Karyawan::onlyTrashed()
            ->paginate(10);

        return view(
            'karyawan.trash',
            compact('karyawans')
        );
    }

    public function restore($id)
    {
        Karyawan::onlyTrashed()
            ->findOrFail($id)
            ->restore();

        return redirect()
            ->route('karyawan.index')
            ->with(
                'success',
                'Karyawan berhasil dipulihkan.'
            );
    }

    public function forceDelete($id)
    {
        Karyawan::onlyTrashed()
            ->findOrFail($id)
            ->forceDelete();

        return redirect()
            ->route('karyawan.index')
            ->with('success', 'Karyawan berhasil dihapus permanen.');
    }

    public function getDivisi($departemenId)
    {
        $divisis = Divisi::where(
            'departemen_id',
            $departemenId
        )
            ->where('status', 'aktif')
            ->orderBy('nama')
            ->get([
                'id',
                'nama',
            ]);

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
            ->get([
                'id',
                'nama',
            ]);

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
            ->get([
                'id',
                'nama',
            ]);

        return response()->json($jobPositions);
    }


    public function show(Karyawan $karyawan)
    {
        $karyawan->load([
            'departemen',
            'divisi',
            'section',
            'jobPosition',
            'jobLevel',
            'cabangKantor',
            'jenjangPendidikan',
            'statusKawin',
            'agama',
            'statusKepegawaian',
            'bank',
        ]);

        $penempatan = collect([
            $karyawan->departemen?->nama,
            $karyawan->divisi?->nama,
            $karyawan->section?->nama,
        ])
            ->filter()
            ->implode(' › ');

        return response()->json([

            'id' => $karyawan->id,

            'nama' => $karyawan->nama,

            'nip' => $karyawan->nip,

            'inisial' => mb_strtoupper(
                mb_substr($karyawan->nama, 0, 1)
            ),

            'job_position' =>
            $karyawan->jobPosition?->nama ?? '-',

            'status_kepegawaian' =>
            $karyawan->statusKepegawaian?->nama ?? '-',

            'status' =>
            $karyawan->status,


            /*
        |--------------------------------------------------------------------------
        | DATA DIBUAT
        |--------------------------------------------------------------------------
        | created_at = waktu record karyawan dibuat di database.
        | Bukan tanggal karyawan mulai bekerja.
        */

            'data_dibuat' => $karyawan->created_at
                ? $karyawan->created_at
                ->timezone('Asia/Makassar')
                ->format('d/m/Y H:i') . ' WITA'
                : '-',


            'email' =>
            $karyawan->email,

            'no_tlp' =>
            $this->formatGroup(
                $karyawan->no_tlp,
                4
            ),

            'nik' =>
            $this->formatGroup(
                $karyawan->nik,
                4
            ),

            'jenjang_pendidikan' =>
            $karyawan->jenjangPendidikan?->nama ?? '-',

            'status_kawin' =>
            $karyawan->statusKawin?->nama ?? '-',

            'agama' =>
            $karyawan->agama?->nama ?? '-',


            'penempatan_breadcrumb' =>
            $penempatan ?: '-',


            'job_position_level' =>
            trim(
                ($karyawan->jobPosition?->nama ?? '-') .
                    ' · ' .
                    ($karyawan->jobLevel?->nama ?? '')
            ),


            'cabang_kantor' =>
            $karyawan->cabangKantor?->nama ?? '-',


            'status_kepegawaian_full' =>
            $karyawan->statusKepegawaian?->nama ?? '-',


            'status_aktif' =>
            $karyawan->status === 'aktif'
                ? 'Aktif'
                : 'Nonaktif',


            'gaji' =>
            $karyawan->gaji
                ? 'Rp ' . number_format(
                    $karyawan->gaji,
                    0,
                    ',',
                    '.'
                )
                : '-',


            'bank' =>
            $karyawan->bank?->nama ?? '-',


            'nama_bank' =>
            $karyawan->nama_bank ?? '-',


            'no_rekening' =>
            $this->formatGroup(
                $karyawan->no_rekening,
                4
            ),


            'no_npwp' =>
            $karyawan->no_npwp ?? '-',


            'no_bpjs_ketenagakerjaan' =>
            $this->formatGroup(
                $karyawan->no_bpjs_ketenagakerjaan,
                4
            ),


            'no_bpjs_kesehatan' =>
            $this->formatGroup(
                $karyawan->no_bpjs_kesehatan,
                4
            ),

        ]);
    }

    /**
     * Kelompokkan digit angka jadi blok per-N karakter dgn spasi,
     * biar angka panjang (no rekening, BPJS, dll) gampang dibaca.
     * Contoh: formatGroup('081547607124', 4) -> "0815 4760 7124"
     */
    private function formatGroup(?string $value, int $groupSize = 4): string
    {
        if (!$value) {
            return '-';
        }

        return trim(chunk_split($value, $groupSize, ' '));
    }
}
