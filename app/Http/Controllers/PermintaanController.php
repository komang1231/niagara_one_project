<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PermintaanKaryawan;
use App\Models\PermintaanCuti;
use App\Models\PermintaanLembur;
use App\Models\PermintaanResign;
use App\Models\PermintaanTukarShift;
use App\Services\CodeGenerator;
use App\Models\Divisi;
use App\Models\Section;
use App\Models\JobPosition;
use App\Models\JobLevel;
use App\Models\Departemen;
use App\Models\CabangKantor;
use App\Models\Cuti;
use App\Models\Karyawan;
use App\Models\Shift;

class PermintaanController extends Controller
{
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Tab Aktif
        |--------------------------------------------------------------------------
        */

        $tabs = [
            'karyawan' => 'Permintaan Karyawan',
            'cuti' => 'Permintaan Cuti',
            'lembur' => 'Permintaan Lembur',
            'resign' => 'Permintaan Resign',
            'tukar-shift' => 'Permintaan Tukar Shift',
        ];
        $tab = $request->get('tab', 'karyawan');


        /*
        |--------------------------------------------------------------------------
        | Preview Kode
        |--------------------------------------------------------------------------
        */

        $previewKodeKaryawan = CodeGenerator::generate(
            PermintaanKaryawan::class,
            'PMK'
        );

        $previewKodeCuti = CodeGenerator::generate(
            PermintaanCuti::class,
            'PMC'
        );

        $previewKodeLembur = CodeGenerator::generate(
            PermintaanLembur::class,
            'PML'
        );

        $previewKodeResign = CodeGenerator::generate(
            PermintaanResign::class,
            'PMR'
        );

        $previewKodeTukarShift = CodeGenerator::generate(
            PermintaanTukarShift::class,
            'PMTS'
        );


        /*
        |--------------------------------------------------------------------------
        | Permintaan Karyawan
        |--------------------------------------------------------------------------
        */

        $permintaanKaryawan = PermintaanKaryawan::query()
            ->when($request->filled('search_karyawan'), function ($query) use ($request) {
                $query->where('kode', 'like', '%' . $request->search_karyawan . '%');
            })
            ->latest('id')
            ->paginate(10, ['*'], 'karyawan_page')
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | Permintaan Cuti
        |--------------------------------------------------------------------------
        */

        $permintaanCuti = PermintaanCuti::query()
            ->when($request->filled('search_cuti'), function ($query) use ($request) {
                $query->where('kode', 'like', '%' . $request->search_cuti . '%');
            })
            ->latest('id')
            ->paginate(10, ['*'], 'cuti_page')
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | Permintaan Lembur
        |--------------------------------------------------------------------------
        */

        $permintaanLembur = PermintaanLembur::query()
            ->when($request->filled('search_lembur'), function ($query) use ($request) {
                $query->where('kode', 'like', '%' . $request->search_lembur . '%');
            })
            ->latest('id')
            ->paginate(10, ['*'], 'lembur_page')
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | Permintaan Resign
        |--------------------------------------------------------------------------
        */

        $permintaanResign = PermintaanResign::query()
            ->when($request->filled('search_resign'), function ($query) use ($request) {
                $query->where('kode', 'like', '%' . $request->search_resign . '%');
            })
            ->latest('id')
            ->paginate(10, ['*'], 'resign_page')
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | Permintaan Tukar Shift
        |--------------------------------------------------------------------------
        */

        $permintaanTukarShift = PermintaanTukarShift::query()
            ->when($request->filled('search_tukar_shift'), function ($query) use ($request) {
                $query->where('kode', 'like', '%' . $request->search_tukar_shift . '%');
            })
            ->latest('id')
            ->paginate(10, ['*'], 'tukar_shift_page')
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Relasi Lainnya
        |--------------------------------------------------------------------------
        */

        $karyawans = Karyawan::where('status', 'aktif')->get();
        $pemohon = Karyawan::where('status', 'aktif')->get();
        $cabangKantors = CabangKantor::where('status', 'aktif')->get();
        $departemens = Departemen::where('status', 'aktif')->get();
        $divisis = Divisi::where('status', 'aktif')->get();
        $sections = Section::where('status', 'aktif')->get();
        $jobPositions = JobPosition::where('status', 'aktif')->get();
        $jobLevels = JobLevel::where('status', 'aktif')->get();
        $cutis = Cuti::where('status', 'aktif')->get();
        $shifts = Shift::where('status', 'aktif')->get();


        /*
        |--------------------------------------------------------------------------
        | View
        |--------------------------------------------------------------------------
        */

        return view('permintaan.index', compact(
            'tab',
            'tabs',

            'previewKodeKaryawan',
            'previewKodeCuti',
            'previewKodeLembur',
            'previewKodeResign',
            'previewKodeTukarShift',

            'permintaanKaryawan',
            'permintaanCuti',
            'permintaanLembur',
            'permintaanResign',
            'permintaanTukarShift',

            'karyawans',
            'pemohon',
            'cabangKantors',
            'departemens',
            'divisis',
            'sections',
            'jobPositions',
            'jobLevels',
            'cutis',
            'shifts',
        ));
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

    public function editDataPermintaanKaryawan($id)
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
            'nama' => $permintaanKaryawan->nama,
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
}
