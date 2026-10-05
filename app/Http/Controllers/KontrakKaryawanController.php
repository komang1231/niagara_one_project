<?php

namespace App\Http\Controllers;

use App\Http\Requests\KontrakKaryawanRequest;
use App\Models\Karyawan;
use App\Models\KontrakKaryawan;
use App\Models\StatusKepegawaian;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\CodeGenerator;
use Illuminate\Support\Facades\Log;

class KontrakKaryawanController extends Controller
{
    public function index(Request $request)
    {
        $previewNomor = CodeGenerator::generate(
            KontrakKaryawan::class,
            'KTR',
            'nomor_kontrak'
        );

        $query = KontrakKaryawan::with([
            'karyawan',
            'statusKepegawaian',
        ])
            ->orderByDesc('tanggal_mulai');

        if ($request->filled('search')) {
            $search = $request->search;

            $query->whereHas('karyawan', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%");
            });
        }

        $kontrak = $query
            ->paginate(10)
            ->withQueryString();

        $karyawanTanpaKontrak = Karyawan::query()
            ->whereDoesntHave('kontrakKaryawan')
            ->orderBy('nama')
            ->get();

        $statusKepegawaianOptions = StatusKepegawaian::query()
            ->orderBy('nama')
            ->pluck('nama', 'id');

        return view('kontrak-karyawan.index', compact(
            'kontrak',
            'karyawanTanpaKontrak',
            'statusKepegawaianOptions',
            'previewNomor'
        ));
    }

    public function store(KontrakKaryawanRequest $request)
    {
        $data = $request->validated();

        /*
         * Karyawan yang sudah memiliki kontrak
         * tidak boleh membuat kontrak baru melalui Create.
         */
        $sudahMemilikiKontrak = KontrakKaryawan::where(
            'karyawan_id',
            $data['karyawan_id']
        )->exists();

        if ($sudahMemilikiKontrak) {
            return redirect()
                ->route('kontrak-karyawan.index')
                ->with(
                    'error',
                    'Karyawan tersebut sudah memiliki kontrak. Gunakan fitur Perpanjang Kontrak.'
                );
        }

        /*
         * Tanggal berakhir otomatis 1 tahun
         * setelah tanggal mulai.
         */
        $tanggalMulai = Carbon::parse($data['tanggal_mulai']);

        $data['tanggal_berakhir'] = $tanggalMulai
            ->copy()
            ->addYear();

        $data['status'] = 'aktif';

        DB::transaction(function () use ($data) {
            KontrakKaryawan::create($data);
        });

        return redirect()
            ->route('kontrak-karyawan.index')
            ->with(
                'success',
                'Kontrak karyawan berhasil ditambahkan.'
            );
    }

    public function editData($id)
    {
        $kontrak = KontrakKaryawan::with([
            'karyawan',
            'statusKepegawaian',
        ])->findOrFail($id);

        return response()->json([
            'id' => $kontrak->id,
            'nomor_kontrak' => $kontrak->nomor_kontrak,
            'karyawan_id' => $kontrak->karyawan_id,
            'karyawan_nama' => $kontrak->karyawan?->nama,
            'status_kepegawaian_id' => $kontrak->status_kepegawaian_id,
            'tanggal_mulai' => Carbon::parse($kontrak->tanggal_mulai)->format('Y-m-d'),
            'tanggal_berakhir' => Carbon::parse($kontrak->tanggal_berakhir)->format('Y-m-d'),
        ]);
    }

    public function update(KontrakKaryawanRequest $request, $id)
    {
        $kontrak = KontrakKaryawan::findOrFail($id);

        $data = $request->validated();

        Log::debug('UPDATE KONTRAK', [
            'id' => $id,
            'data' => $data,
        ]);

        $tanggalMulai = Carbon::parse($data['tanggal_mulai']);

        $data['tanggal_berakhir'] = $tanggalMulai
            ->copy()
            ->addYear();

        $data['karyawan_id'] = $kontrak->karyawan_id;

        $kontrak->update($data);

        return redirect()
            ->route('kontrak-karyawan.index')
            ->with(
                'success',
                'Kontrak karyawan berhasil diperbarui.'
            );
    }

    public function perpanjang($id)
    {
        $kontrakLama = KontrakKaryawan::findOrFail($id);

        /*
     * Perpanjangan hanya boleh dilakukan
     * ketika sudah masuk H-30.
     */
        $tanggalBerakhirLama = Carbon::parse($kontrakLama->tanggal_berakhir);
        $batasPerpanjangan = $tanggalBerakhirLama->copy()->subDays(30);

        if (now()->startOfDay()->lt($batasPerpanjangan->startOfDay())) {
            return redirect()
                ->route('kontrak-karyawan.index')
                ->with(
                    'error',
                    'Kontrak belum dapat diperpanjang. Perpanjangan hanya dapat dilakukan saat H-30.'
                );
        }

        /*
     * Kontrak baru dimulai tepat pada tanggal
     * berakhirnya kontrak lama.
     */
        $tanggalMulaiBaru = $tanggalBerakhirLama->copy();

        /*
     * Kontrak baru berlaku selama 1 tahun.
     */
        $tanggalBerakhirBaru = $tanggalMulaiBaru->copy()->addYear();

        DB::transaction(function () use (
            $kontrakLama,
            $tanggalMulaiBaru,
            $tanggalBerakhirBaru
        ) {
            KontrakKaryawan::create([
                'karyawan_id' => $kontrakLama->karyawan_id,
                'status_kepegawaian_id' => $kontrakLama->status_kepegawaian_id,
                'tanggal_mulai' => $tanggalMulaiBaru->toDateString(),
                'tanggal_berakhir' => $tanggalBerakhirBaru->toDateString(),
                'status' => 'aktif',
            ]);
        });

        return redirect()
            ->route('kontrak-karyawan.index')
            ->with(
                'success',
                'Kontrak karyawan berhasil diperpanjang.'
            );
    }
}
