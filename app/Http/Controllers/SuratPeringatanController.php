<?php

namespace App\Http\Controllers;

use App\Http\Requests\SuratPeringatanRequest;
use App\Models\Karyawan;
use App\Models\SuratPeringatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SuratPeringatanController extends Controller
{
    public function index(Request $request)
    {
        $startIndex = microtime(true);

        $jenisOptions = [
            'SP1' => 'SP1',
            'SP2' => 'SP2',
            'SP3' => 'SP3',
        ];

        $statusOptions = [
            'aktif' => 'Aktif',
            'nonaktif' => 'Nonaktif',
        ];

        $karyawanOptions = Karyawan::query()
            ->where('status', 'aktif')
            ->orderBy('nama')
            ->pluck('nama', 'id');

        $suratPeringatan = $this->filter($request)
            ->with([
                'karyawan.jobPosition',
                'karyawan.departemen',
            ])
            ->orderByDesc('kode')
            ->paginate(10)
            ->withQueryString();

        if ($request->ajax() || $request->wantsJson()) {
            Log::debug('Surat Peringatan index timings', [
                'ajax' => true,
                'ms' => round((microtime(true) - $startIndex) * 1000, 2),
            ]);

            return view(
                'components.table.table',
                compact('suratPeringatan')
            );
        }

        Log::debug('Surat Peringatan index timings', [
            'ajax' => false,
            'ms' => round((microtime(true) - $startIndex) * 1000, 2),
        ]);

        return view('surat-peringatan.index', compact(
            'suratPeringatan',
            'jenisOptions',
            'statusOptions',
            'karyawanOptions'
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

        return SuratPeringatan::query()
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
                $query->whereIn('jenis_surat', $jenis);
            })
            ->when(!is_null($status), function ($query) use ($status) {
                $query->whereIn('status', $status);
            });
    }

    public function store(SuratPeringatanRequest $request)
    {
        $data = $request->validated();

        DB::transaction(function () use ($request, $data) {
            $data['masa_berlaku'] = $this->hitungMasaBerlaku(
                $data['jenis_surat'],
                now()
            );

            if ($request->hasFile('file')) {
                $data['file'] = $request->file('file')
                    ->store('surat-peringatan', 'public');
            }

            $suratPeringatan = SuratPeringatan::create($data);

            if ($suratPeringatan->jenis_surat === 'SP3') {
                Karyawan::whereKey($suratPeringatan->karyawan_id)
                    ->update([
                        'status' => 'nonaktif',
                    ]);
            }
        });

        return redirect()
            ->route('surat-peringatan.index')
            ->with('success', 'Surat peringatan berhasil diterbitkan.');
    }

    public function editData($id)
    {
        $suratPeringatan = SuratPeringatan::findOrFail($id);

        return response()->json([
            'id' => $suratPeringatan->id,
            'kode' => $suratPeringatan->kode,
            'karyawan_id' => $suratPeringatan->karyawan_id,
            'jenis_surat' => $suratPeringatan->jenis_surat,
            'masa_berlaku' => $suratPeringatan->masa_berlaku,
            'status' => $suratPeringatan->status,
            'file_name' => $suratPeringatan->file
                ? basename($suratPeringatan->file)
                : null,
            'file_url' => $suratPeringatan->file
                ? Storage::disk('public')->url($suratPeringatan->file)
                : null,
        ]);
    }

    public function update(
        SuratPeringatanRequest $request,
        SuratPeringatan $suratPeringatan
    ) {
        $data = $request->validated();

        $fileLama = $suratPeringatan->file;
        $fileBaru = null;

        DB::beginTransaction();

        try {
            if ($request->hasFile('file')) {
                $fileBaru = $request->file('file')
                    ->store('surat-peringatan', 'public');

                $data['file'] = $fileBaru;
            }

            /*
             * Masa berlaku merupakan data turunan dari jenis SP.
             *
             * Jika jenis SP berubah, hitung ulang dari tanggal
             * penerbitan awal (created_at), bukan dari tanggal edit.
             */
            if ($suratPeringatan->jenis_surat !== $data['jenis_surat']) {
                $tanggalTerbit = $suratPeringatan->created_at ?? now();

                $data['masa_berlaku'] = $this->hitungMasaBerlaku(
                    $data['jenis_surat'],
                    $tanggalTerbit
                );
            } else {
                $data['masa_berlaku'] = $suratPeringatan->masa_berlaku;
            }

            $suratPeringatan->update($data);

            if ($suratPeringatan->jenis_surat === 'SP3') {
                Karyawan::whereKey($suratPeringatan->karyawan_id)
                    ->update([
                        'status' => 'nonaktif',
                    ]);
            }

            DB::commit();

            if ($fileBaru && $fileLama && $fileLama !== $fileBaru) {
                Storage::disk('public')->delete($fileLama);
            }
        } catch (\Throwable $e) {
            DB::rollBack();

            if ($fileBaru) {
                Storage::disk('public')->delete($fileBaru);
            }

            throw $e;
        }

        return redirect()
            ->route('surat-peringatan.index')
            ->with('success', 'Surat peringatan berhasil diperbarui.');
    }

    public function toggleStatus(SuratPeringatan $suratPeringatan)
    {
        $suratPeringatan->update([
            'status' => $suratPeringatan->status === 'aktif'
                ? 'nonaktif'
                : 'aktif',
        ]);

        return response()->json([
            'success' => true,
            'status' => $suratPeringatan->status,
        ]);
    }

    public function destroy($id)
    {
        $suratPeringatan = SuratPeringatan::findOrFail($id);

        $suratPeringatan->delete();

        return redirect()
            ->route('surat-peringatan.index')
            ->with('success', 'Surat peringatan berhasil dipindahkan ke Trash.');
    }

    public function trash()
    {
        $suratPeringatans = SuratPeringatan::onlyTrashed()
            ->with('karyawan')
            ->orderByDesc('deleted_at')
            ->paginate(10);

        return view(
            'surat-peringatan.trash',
            compact('suratPeringatans')
        );
    }

    public function restore($id)
    {
        SuratPeringatan::onlyTrashed()
            ->findOrFail($id)
            ->restore();

        return redirect()
            ->route('surat-peringatan.trash')
            ->with('success', 'Surat peringatan berhasil dipulihkan.');
    }

    public function forceDelete($id)
    {
        $suratPeringatan = SuratPeringatan::onlyTrashed()
            ->findOrFail($id);

        $file = $suratPeringatan->file;

        $suratPeringatan->forceDelete();

        if ($file) {
            Storage::disk('public')->delete($file);
        }

        return redirect()
            ->route('surat-peringatan.trash')
            ->with('success', 'Surat peringatan berhasil dihapus permanen.');
    }

    private function hitungMasaBerlaku(string $jenisSurat, $tanggalTerbit)
    {
        $bulan = match ($jenisSurat) {
            'SP1' => 3,
            'SP2', 'SP3' => 6,
            default => throw new \InvalidArgumentException(
                'Jenis surat peringatan tidak valid.'
            ),
        };

        return \Carbon\Carbon::parse($tanggalTerbit)
            ->addMonths($bulan)
            ->toDateString();
    }
}