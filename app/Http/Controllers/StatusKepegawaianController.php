<?php

namespace App\Http\Controllers;

use App\Http\Requests\StatusKepegawaianRequest;
use App\Models\StatusKepegawaian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\CodeGenerator;

class StatusKepegawaianController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $startIndex = microtime(true);

        $previewKode = CodeGenerator::generate(
            StatusKepegawaian::class,
            'SKP'
        );

        $statusOptions = [
            'aktif' => 'Aktif',
            'nonaktif' => 'Nonaktif',
        ];

        $statusKepegawaian = $this->filter($request)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        if ($request->ajax() || $request->wantsJson()) {
            Log::debug('StatusKepegawaian index timings', [
                'ajax' => true,
                'ms' => round(
                    (microtime(true) - $startIndex) * 1000,
                    2
                )
            ]);

            return view(
                'components.table.table',
                compact('statusKepegawaian')
            );
        }

        Log::debug('StatusKepegawaian index timings', [
            'ajax' => false,
            'ms' => round(
                (microtime(true) - $startIndex) * 1000,
                2
            )
        ]);

        return view(
            'status-kepegawaian.index',
            compact(
                'statusOptions',
                'statusKepegawaian',
                'previewKode'
            )
        );
    }

    private function filter(Request $request)
    {
        $search = $request->query('search');
        $status = $request->has('status')
            ? (array) $request->query('status', [])
            : null;

        return StatusKepegawaian::query()
            ->when($search, fn($query) => $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('kode', 'like', "%{$search}%");
            }))
            ->when(!is_null($status), fn($query) => $query->whereIn('status', $status));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $start = microtime(true);
        $data = $request->validated();
        $data['status'] = $data['status'] === '1' ? 'aktif' : 'nonaktif';

        $before = microtime(true);
        $statusKepegawaian = StatusKepegawaian::create($data);
        $after = microtime(true);

        Log::debug('StatusKepegawaian store timings', [
            'total_ms' => round((microtime(true) - $start) * 1000, 2),
            'create_ms' => round(($after - $before) * 1000, 2),
            'id' => $statusKepegawaian->id ?? null,
        ]);

        return redirect()->route('status-kepegawaian.index')->with('success', 'Status Kepegawaian berhasil ditambahkan.');
    }

    public function edit(StatusKepegawaian $statusKepegawaian)
    {
        return view('status-kepegawaian.form-edit', compact('statusKepegawaian'));
    }
    public function editData($id)
    {
        $statusKepegawaian = StatusKepegawaian::findOrFail($id);
        return response()->json([
            'id' => $statusKepegawaian->id,
            'kode' => $statusKepegawaian->kode,
            'nama' => $statusKepegawaian->nama,
            'status' => $statusKepegawaian->status,
        ]);
    }

    public function update(StatusKepegawaianRequest $request, StatusKepegawaian $statusKepegawaian)
    {
        $data = $request->validated();
        $data['status'] = $data['status'] === '1' ? 'aktif' : 'nonaktif';

        $statusKepegawaian->update($data);
        return redirect()->route('status-kepegawaian.index')->with('success', 'Status Kepegawaian berhasil diperbarui.');
    }

    public function toggleStatus(StatusKepegawaian $statusKepegawaian)
    {
        $statusKepegawaian->update([
            'status' => $statusKepegawaian->status === 'aktif' ? 'nonaktif' : 'aktif',
        ]);

        return response()->json([
            'success' => true,
            'status' => $statusKepegawaian->status,
        ]);
    }
    public function destroy(StatusKepegawaian $statusKepegawaian)
    {
        $statusKepegawaian->delete();
        return redirect()->route('status-kepegawaian.index')->with('success', 'Status Kepegawaian berhasil dihapus.');
    }

    public function trash()
    {
        $statusKepegawaians = StatusKepegawaian::onlyTrashed()->paginate(10);
        return view('status-kepegawaian.trash', compact('statusKepegawaians'));
    }

    public function restore($id)
    {
        StatusKepegawaian::onlyTrashed()->findOrFail($id)->restore();
        return redirect()->route('status-kepegawaian.index')->with('success', 'Status Kepegawaian berhasil dipulihkan.');
    }

    public function forceDelete($id)
    {
        StatusKepegawaian::onlyTrashed()
            ->findOrFail($id)
            ->forceDelete();

        return redirect()
            ->route('status-kepegawaian.index')
            ->with('success', 'Status Kepegawaian berhasil dihapus permanen.');
    }
}
