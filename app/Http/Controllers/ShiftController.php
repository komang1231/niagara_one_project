<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShiftRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use App\Models\Shift;
use App\Services\CodeGenerator;

class ShiftController extends Controller
{
    public function index(Request $request)
    {
        $startIndex = microtime(true);
        $previewKode = \App\Services\CodeGenerator::generate(\App\Models\Shift::class, 'SHIFT');
        $statusOptions = [
            'aktif' => 'Aktif',
            'nonaktif' => 'Nonaktif',
        ];

        $shift = $this->filter($request)
            ->orderByDesc('kode')
            // ->latest()
            ->paginate(10)
            ->withQueryString();

        if ($request->ajax() || $request->wantsJson()) {
            Log::debug('Shift index timings', ['ajax' => true, 'ms' => round((microtime(true) - $startIndex) * 1000, 2)]);
            return view('components.table.table', compact('shift'));
        }

        Log::debug('Shift index timings', ['ajax' => false, 'ms' => round((microtime(true) - $startIndex) * 1000, 2)]);
        return view('shift.index', compact('statusOptions', 'shift', 'previewKode'));
    }

    private function filter(Request $request)
    {
        $search = $request->query('search');
        $status = $request->has('status')
            ? (array) $request->query('status', [])
            : null;

        return Shift::query()
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

    public function store(ShiftRequest $request)
    {
        $start = microtime(true);
        $data = $request->validated();
        $data['status'] = $data['status'] === '1' ? 'aktif' : 'nonaktif';
        $data['lintas_hari'] = $data['jam_masuk'] > $data['jam_pulang'];

        $before = microtime(true);
        $shift = Shift::create($data);
        $after = microtime(true);

        Log::debug('Shift store timings', [
            'total_ms' => round((microtime(true) - $start) * 1000, 2),
            'create_ms' => round(($after - $before) * 1000, 2),
            'id' => $shift->id ?? null,
        ]);

        return redirect()->route('shift.index')->with('success', 'Shift berhasil ditambahkan.');
    }

    public function edit(Shift $shift)
    {
        return view('shift.form-edit', compact('shift'));
    }
    public function editData($id)
    {
        //$table->string('kode', 20)->unique();
        // $table->string('nama', 100);
        // $table->time('jam_masuk');
        // $table->time('jam_pulang');
        // $table->boolean('lintas_hari')->default(false);
        // $table->unsignedInteger('istirahat_menit')->default(60);
        // $table->unsignedInteger('toleransi_keterlambatan')->default(0);
        // $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
        // $table->string('warna', 10);

        $shift = Shift::findOrFail($id);
        return response()->json([
            'id' => $shift->id,
            'kode' => $shift->kode,
            'nama' => $shift->nama,
            'jam_masuk' => $shift->jam_masuk,
            'jam_pulang' => $shift->jam_pulang,
            'lintas_hari' => $shift->lintas_hari,
            'istirahat_menit' => $shift->istirahat_menit,
            'toleransi_keterlambatan' => $shift->toleransi_keterlambatan,
            'status' => $shift->status,
            'warna' => $shift->warna,
        ]);
    }

    public function update(ShiftRequest $request, Shift $shift)
    {
        $data = $request->validated();
        $data['status'] = $data['status'] === '1' ? 'aktif' : 'nonaktif';
        $data['lintas_hari'] = $data['jam_masuk'] > $data['jam_pulang'];

        Log::debug('Data update shift', [
            'id' => $shift->id,
            'data' => $data,
        ]);

        $shift->update($data);
        return redirect()->route('shift.index')->with('success', 'Shift berhasil diperbarui.');
    }

    public function toggleStatus(Shift $shift)
    {
        $shift->update([
            'status' => $shift->status === 'aktif' ? 'nonaktif' : 'aktif',
        ]);

        return response()->json([
            'success' => true,
            'status' => $shift->status,
        ]);
    }

    public function destroy($id)
    {
        $shift = Shift::findOrFail($id);

        // if ($shift->divisi()->exists()) {
        //     return redirect()
        //         ->route('shift.index')
        //         ->with(
        //             'error',
        //             'Shift tidak dapat dihapus karena masih digunakan oleh data Divisi.'
        //         );
        // }

        $shift->delete();

        return redirect()
            ->route('shift.index')
            ->with(
                'success',
                'Shift berhasil dipindahkan ke Trash.'
            );
    }


    public function trash()
    {
        $shifts = Shift::onlyTrashed()->paginate(10);
        return view('shift.trash', compact('shifts'));
    }

    public function restore($id)
    {
        Shift::onlyTrashed()->findOrFail($id)->restore();
        return redirect()->route('shift.trash')->with('success', 'Shift berhasil dipulihkan.');
    }

    public function forceDelete($id)
    {
        Shift::onlyTrashed()
            ->findOrFail($id)
            ->forceDelete();

        return redirect()
            ->route('shift.trash')
            ->with('success', 'Shift berhasil dihapus permanen.');
    }
}
