<?php

namespace App\Http\Controllers;

use App\Http\Requests\JadwalKaryawanRequest;
use App\Models\JadwalKaryawan;
use App\Models\Karyawan;
use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class JadwalKaryawanController extends Controller
{
    public function index(Request $request)
    {
        $tanggal = $request->query('tanggal');

        $mulai = $tanggal
            ? Carbon::parse($tanggal)->startOfWeek()
            : now()->startOfWeek();

        $rentang = (int) $request->query('rentang', 1);

        if (!in_array($rentang, [1, 2])) {
            $rentang = 1;
        }

        $jumlahHari = $rentang * 7;

        $akhir = $mulai->copy()->addDays($jumlahHari - 1);

        /*
    |--------------------------------------------------------------------------
    | Daftar karyawan aktif
    |--------------------------------------------------------------------------
    */

        $karyawans = Karyawan::query()
            ->with('departemen')
            ->where('status', 'aktif')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->query('search');

                $query->where(function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                        ->orWhere('nip', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('departemen'), function ($query) use ($request) {
                $departemen = (array) $request->query('departemen');

                $query->whereIn('departemen_id', $departemen);
            })
            ->orderBy('nama')
            ->get();

        /*
    |--------------------------------------------------------------------------
    | Pilihan departemen untuk filter
    |--------------------------------------------------------------------------
    */

        $departemenOptions = \App\Models\Departemen::query()
            ->where('status', 'aktif')
            ->orderBy('nama')
            ->pluck('nama', 'id')
            ->toArray();

        /*
    |--------------------------------------------------------------------------
    | Daftar hari dalam periode
    |--------------------------------------------------------------------------
    */

        $hari = collect();

        for ($i = 0; $i < $jumlahHari; $i++) {
            $tanggalHari = $mulai->copy()->addDays($i);

            $hari->push([
                'iso' => $tanggalHari->toDateString(),
                'tanggal' => $tanggalHari->day,
                'nama' => $tanggalHari->translatedFormat('D'),
                'bulan' => $tanggalHari->translatedFormat('M Y'),
                'isMinggu' => $tanggalHari->isSunday(),
                'isHariIni' => $tanggalHari->isToday(),
            ]);
        }

        /*
    |--------------------------------------------------------------------------
    | Jadwal karyawan dalam periode
    |--------------------------------------------------------------------------
    |
    | Ambil satu hari sebelum periode untuk kebutuhan
    | shift lintas hari dari hari sebelumnya.
    |
    */

        $jadwalKaryawan = JadwalKaryawan::query()
            ->with([
                'karyawan.departemen',
                'shift',
            ])
            ->whereBetween('tanggal', [
                $mulai->copy()->subDay()->toDateString(),
                $akhir->toDateString(),
            ])
            ->where('status', 'aktif')
            ->whereIn('karyawan_id', $karyawans->pluck('id'))
            ->get();

        /*
    |--------------------------------------------------------------------------
    | Bentuk data jadwal untuk kalender
    |--------------------------------------------------------------------------
    */

        $jadwal = collect();

        foreach ($jadwalKaryawan as $item) {
            if (!$item->shift) {
                continue;
            }

            $jamMasuk = Carbon::createFromFormat(
                'H:i:s',
                $item->shift->jam_masuk
            );

            $jamPulang = Carbon::createFromFormat(
                'H:i:s',
                $item->shift->jam_pulang
            );

            $lintasHari = (bool) $item->shift->lintas_hari;

            $menitMasuk = ($jamMasuk->hour * 60) + $jamMasuk->minute;
            $menitPulang = ($jamPulang->hour * 60) + $jamPulang->minute;

            if ($lintasHari) {
                $durasi = (1440 - $menitMasuk) + $menitPulang;
            } else {
                $durasi = $menitPulang - $menitMasuk;
            }

            if ($durasi <= 0) {
                continue;
            }

            $start = ($menitMasuk / 1440) * 100;
            $lebar = ($durasi / 1440) * 100;

            $chip = [
                'id' => $item->id,
                'nama' => $item->shift->nama,
                'masuk' => $jamMasuk->format('H:i'),
                'pulang' => $jamPulang->format('H:i'),
                'lintas' => $lintasHari,
                'warna' => $item->shift->warna,
                'start' => $start,
                'lebar' => $lebar,
            ];

            $key = $item->karyawan_id . '|' . Carbon::parse($item->tanggal)->format('Y-m-d');

            $jadwal->put(
                $key,
                collect($jadwal->get($key, []))
                    ->push($chip)
                    ->values()
            );
        }

        /*
    |--------------------------------------------------------------------------
    | Kelompokkan karyawan berdasarkan departemen
    |--------------------------------------------------------------------------
    */

        $kelompok = $karyawans->groupBy(function ($karyawan) {
            return $karyawan->departemen?->nama ?? 'Tanpa Departemen';
        });

        /*
    |--------------------------------------------------------------------------
    | Informasi periode
    |--------------------------------------------------------------------------
    */

        if ($rentang === 1) {
            $periode = $mulai->translatedFormat('d M Y')
                . ' - '
                . $akhir->translatedFormat('d M Y');
        } else {
            $periode = $mulai->translatedFormat('d M Y')
                . ' - '
                . $akhir->translatedFormat('d M Y');
        }

        /*
    |--------------------------------------------------------------------------
    | Navigasi kalender
    |--------------------------------------------------------------------------
    */

        $queryParams = $request->query();

        $buatUrl = function (Carbon $tanggalBaru) use ($queryParams, $rentang) {
            $params = $queryParams;

            $params['tanggal'] = $tanggalBaru->toDateString();
            $params['rentang'] = $rentang;

            return route('jadwal-karyawan.index', $params);
        };

        $nav = [
            'prev' => $buatUrl(
                $mulai->copy()->subDays($jumlahHari)
            ),

            'next' => $buatUrl(
                $mulai->copy()->addDays($jumlahHari)
            ),

            'today' => $buatUrl(
                now()->startOfWeek()
            ),

            'minggu1' => $buatUrl($mulai),

            'minggu2' => $buatUrl($mulai),
        ];

        $nav['minggu1'] = route(
            'jadwal-karyawan.index',
            array_merge($queryParams, [
                'tanggal' => $mulai->toDateString(),
                'rentang' => 1,
            ])
        );

        $nav['minggu2'] = route(
            'jadwal-karyawan.index',
            array_merge($queryParams, [
                'tanggal' => $mulai->toDateString(),
                'rentang' => 2,
            ])
        );

        /*
    |--------------------------------------------------------------------------
    | Posisi awal timeline
    |--------------------------------------------------------------------------
    */
        $legend = Shift::query()
            ->where('status', 'aktif')
            ->orderBy('nama')
            ->get();

        $karyawanOptions = Karyawan::query()
            ->where('status', 'aktif')
            ->orderBy('nama')
            ->pluck('nama', 'id')
            ->toArray();

        $kursorAwal = now();

        return view('jadwal-karyawan.index', compact(
            'jadwalKaryawan',
            'karyawans',
            'departemenOptions',
            'mulai',
            'rentang',
            'nav',
            'periode',
            'jadwal',
            'hari',
            'legend',
            'karyawanOptions',
            'kursorAwal',
            'kelompok'
        ));
    }

    private function filter(Request $request)
    {
        $search = $request->query('search');

        return JadwalKaryawan::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('karyawan', function ($karyawan) use ($search) {
                        $karyawan->where('nama', 'like', "%{$search}%")
                            ->orWhere('nip', 'like', "%{$search}%");
                    })
                        ->orWhereHas('shift', function ($shift) use ($search) {
                            $shift->where('nama', 'like', "%{$search}%");
                        })
                        ->orWhere('tanggal', 'like', "%{$search}%");
                });
            });
    }

    public function create()
    {
        //
    }

    public function store(JadwalKaryawanRequest $request)
    {
        $data = $request->validated();

        foreach ($data['jadwal'] as $jadwal) {
            foreach ($jadwal['karyawan_id'] as $karyawanId) {
                $sudahAda = JadwalKaryawan::query()
                    ->where('karyawan_id', $karyawanId)
                    ->whereDate('tanggal', $jadwal['tanggal'])
                    ->exists();

                if ($sudahAda) {
                    return back()
                        ->withInput()
                        ->with(
                            'error',
                            'Karyawan tersebut sudah memiliki jadwal pada tanggal ' .
                                Carbon::parse($jadwal['tanggal'])->translatedFormat('d M Y') . '.'
                        );
                }
            }
        }

        foreach ($data['jadwal'] as $jadwal) {
            foreach ($jadwal['karyawan_id'] as $karyawanId) {
                JadwalKaryawan::create([
                    'karyawan_id' => $karyawanId,
                    'shift_id' => $jadwal['shift_id'],
                    'tanggal' => $jadwal['tanggal'],
                    'status' => 'aktif',
                ]);
            }
        }

        return redirect()
            ->route('jadwal-karyawan.index')
            ->with('success', 'Jadwal karyawan berhasil ditambahkan.');
    }

    public function edit(JadwalKaryawan $jadwalKaryawan)
    {
        return view('jadwal-karyawan.form-edit', compact('jadwalKaryawan'));
    }

    public function editData($id)
    {
        $jadwalKaryawan = JadwalKaryawan::findOrFail($id);

        return response()->json([
            'id' => $jadwalKaryawan->id,
            'karyawan_id' => $jadwalKaryawan->karyawan_id,
            'shift_id' => $jadwalKaryawan->shift_id,
            'tanggal' => $jadwalKaryawan->tanggal,
            'status' => $jadwalKaryawan->status,
        ]);
    }

    public function update(
        JadwalKaryawanRequest $request,
        JadwalKaryawan $jadwalKaryawan
    ) {
        $data = $request->validated();

        $sudahAda = JadwalKaryawan::query()
            ->where('karyawan_id', $data['karyawan_id'])
            ->whereDate('tanggal', $data['tanggal'])
            ->where('id', '!=', $jadwalKaryawan->id)
            ->exists();

        if ($sudahAda) {
            return back()
                ->withInput()
                ->with('error', 'Karyawan tersebut sudah memiliki jadwal pada tanggal yang dipilih.');
        }

        $jadwalKaryawan->update($data);

        return redirect()
            ->route('jadwal-karyawan.index')
            ->with('success', 'Jadwal karyawan berhasil diperbarui.');
    }

    public function toggleStatus(JadwalKaryawan $jadwalKaryawan)
    {
        $jadwalKaryawan->update([
            'status' => $jadwalKaryawan->status === 'aktif'
                ? 'nonaktif'
                : 'aktif',
        ]);

        return response()->json([
            'success' => true,
            'status' => $jadwalKaryawan->status,
        ]);
    }

    public function destroy($id)
    {
        $jadwalKaryawan = JadwalKaryawan::findOrFail($id);

        $jadwalKaryawan->delete();

        return redirect()
            ->route('jadwal-karyawan.index')
            ->with('success', 'Jadwal karyawan berhasil dipindahkan ke Trash.');
    }

    public function trash()
    {
        $jadwalKaryawans = JadwalKaryawan::onlyTrashed()
            ->with([
                'karyawan',
                'shift',
            ])
            ->latest('tanggal')
            ->paginate(10);

        return view('jadwal-karyawan.trash', compact('jadwalKaryawans'));
    }

    public function restore($id)
    {
        JadwalKaryawan::onlyTrashed()
            ->findOrFail($id)
            ->restore();

        return redirect()
            ->route('jadwal-karyawan.trash')
            ->with('success', 'Jadwal karyawan berhasil dipulihkan.');
    }

    public function forceDelete($id)
    {
        JadwalKaryawan::onlyTrashed()
            ->findOrFail($id)
            ->forceDelete();

        return redirect()
            ->route('jadwal-karyawan.trash')
            ->with('success', 'Jadwal karyawan berhasil dihapus permanen.');
    }
}
