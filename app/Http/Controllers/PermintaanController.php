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
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

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
            'karyawan' => 'Karyawan',
            'cuti' => 'Cuti',
            'lembur' => 'Lembur',
            'resign' => 'Resign',
            'tukar-shift' => 'Tukar Shift',
        ];
        $tab = $request->get('tab', 'karyawan');

        $user = auth()->user();


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
            ->where('karyawan_id', $user->karyawan->id)
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
            ->where('karyawan_id', $user->karyawan->id)
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
            ->where('karyawan_id', $user->karyawan->id)
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
            ->where('karyawan_id', $user->karyawan->id)
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
            ->where('karyawan_pengaju', $user->karyawan->id)
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


    //PERMINTAAN KARYAWAN EDIT DATA
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

    //PERMINTAAN CUTI EDIT DATA
    public function editDataPermintaanCuti($id)
    {
        $permintaanCuti = PermintaanCuti::with('details')
            ->findOrFail($id);

        if (
            $permintaanCuti->approved_at ||
            $permintaanCuti->rejected_at
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Permintaan Cuti yang sudah diproses tidak dapat diubah.',
            ], 422);
        }

        return response()->json([
            'success' => true,
            'id' => $permintaanCuti->id,
            'kode' => $permintaanCuti->kode,
            'cuti_id' => $permintaanCuti->cuti_id,
            'karyawan_id' => $permintaanCuti->karyawan_id,
            'tanggal_mulai' => $permintaanCuti->tanggal_mulai,
            'tanggal_selesai' => $permintaanCuti->tanggal_selesai,
            'alasan' => $permintaanCuti->alasan,
            'lampiran' => $permintaanCuti->lampiran,
            'pengganti_karyawan_id' => $permintaanCuti->pengganti_karyawan_id,
            'details' => $permintaanCuti->details->map(function ($detail) {
                return [
                    'id' => $detail->id,
                    'tanggal' => $detail->tanggal,
                    'setengah_hari' => $detail->setengah_hari,
                ];
            }),
        ]);
    }

    //PERMINTAAN LEMBUR EDIT DATA
    public function editDataPermintaanLembur($id)
    {
        $permintaanLembur = PermintaanLembur::findOrFail($id);

        if (
            $permintaanLembur->approved_at ||
            $permintaanLembur->rejected_at
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Permintaan Lembur yang sudah diproses tidak dapat diubah.',
            ], 422);
        }

        //['kode', 'karyawan_id', 'tanggal_tujuan', 'jam_mulai', 'jam_selesai', 'alasan', 'pengali'];
        return response()->json([
            'success' => true,
            'id' => $permintaanLembur->id,
            'kode' => $permintaanLembur->kode,
            'karyawan_id' => $permintaanLembur->karyawan_id,
            'tanggal_tujuan' => $permintaanLembur->tanggal_tujuan,
            'jam_mulai' => $permintaanLembur->jam_mulai
                ? \Carbon\Carbon::parse($permintaanLembur->jam_mulai)->format('H:i')
                : null,

            'jam_selesai' => $permintaanLembur->jam_selesai
                ? \Carbon\Carbon::parse($permintaanLembur->jam_selesai)->format('H:i')
                : null,
            'alasan' => $permintaanLembur->alasan,
            'pengali' => $permintaanLembur->pengali,
        ]);
    }

    //PERMINTAAN RESIGN EDIT DATA
    public function editDataPermintaanResign($id)
    {
        $permintaanResign = PermintaanResign::findOrFail($id);

        if (
            $permintaanResign->approved_at ||
            $permintaanResign->rejected_at
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Permintaan Resign yang sudah diproses tidak dapat diubah.',
            ], 422);
        }

        //['kode', 'karyawan_id', 'tanggal_efektif', 'alasan'];
        return response()->json([
            'success' => true,
            'id' => $permintaanResign->id,
            'kode' => $permintaanResign->kode,
            'karyawan_id' => $permintaanResign->karyawan_id,
            'tanggal_efektif' => $permintaanResign->tanggal_efektif,
            'alasan' => $permintaanResign->alasan,
        ]);
    }

    //PERMINTAAN TUKAR SHIFT EDIT DATA
    public function editDataPermintaanTukarShift($id)
    {
        $permintaanTukarShift = PermintaanTukarShift::findOrFail($id);

        if (
            $permintaanTukarShift->approved_at ||
            $permintaanTukarShift->rejected_at
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Permintaan Tukar Shift yang sudah diproses tidak dapat diubah.',
            ], 422);
        }

        //['kode', 'karyawan_pengaju', 'karyawan_pengganti', 'tanggal_tujuan', 'shift_pengaju', 'shift_pengganti'];
        return response()->json([
            'success' => true,
            'id' => $permintaanTukarShift->id,
            'kode' => $permintaanTukarShift->kode,
            'karyawan_pengaju' => $permintaanTukarShift->karyawan_pengaju,
            'karyawan_pengganti' => $permintaanTukarShift->karyawan_pengganti,
            'tanggal_tujuan' => $permintaanTukarShift->tanggal_tujuan,
            'shift_pengaju' => $permintaanTukarShift->shift_pengaju,
            'shift_pengganti' => $permintaanTukarShift->shift_pengganti,
        ]);
    }
}
