<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PermintaanKaryawan;
use App\Models\PermintaanCuti;
use App\Models\PermintaanLembur;
use App\Models\PermintaanResign;
use App\Models\PermintaanTukarShift;
use App\Services\CodeGenerator;

class PermintaanController extends Controller
{
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Tab Aktif
        |--------------------------------------------------------------------------
        */

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
        | View
        |--------------------------------------------------------------------------
        */

        return view('permintaan.index', compact(
            'tab',

            'previewKodeKaryawan',
            'previewKodeCuti',
            'previewKodeLembur',
            'previewKodeResign',
            'previewKodeTukarShift',

            'permintaanKaryawan',
            'permintaanCuti',
            'permintaanLembur',
            'permintaanResign',
            'permintaanTukarShift'
        ));
    }
}