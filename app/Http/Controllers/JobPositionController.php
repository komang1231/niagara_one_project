<?php

namespace App\Http\Controllers;

use App\Http\Requests\JobPositionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\JobPosition;
use App\Services\CodeGenerator;

class JobPositionController extends Controller
{
    public function index(Request $request)
    {
        $startIndex = microtime(true);
        $previewKode = \App\Services\CodeGenerator::generate(\App\Models\JobPosition::class, 'JPOS');
        $statusOptions = [
            'aktif' => 'Aktif',
            'nonaktif' => 'Nonaktif',
        ];

        $jobPosition = $this->filter($request)->latest()->paginate(10)->withQueryString();

        if ($request->ajax() || $request->wantsJson()) {
            Log::debug('JobPosition index timings', ['ajax' => true, 'ms' => round((microtime(true) - $startIndex) * 1000, 2)]);
            return view('components.table.table', compact('jobPosition'));
        }

        Log::debug('JobPosition index timings', ['ajax' => false, 'ms' => round((microtime(true) - $startIndex) * 1000, 2)]);
        return view('job-position.index', compact('statusOptions', 'jobPosition', 'previewKode'));
    }

    private function filter(Request $request)
    {
        $search = $request->query('search');
        $status = $request->has('status')
            ? (array) $request->query('status', [])
            : null;

        return JobPosition::query()
            ->when($search, fn($query) => $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('kode', 'like', "%{$search}%");
            }))
            ->when(!is_null($status), fn($query) => $query->whereIn('status', $status));
    }

    public function create()
    {
        // $previewKode = \App\Services\CodeGenerator::generate(\App\Models\JobPosition::class, 'JPOS');
        return view(
            'job-position.form-create'
            // ,compact('previewKode')
        );
    }

    public function store(JobPositionRequest $request)
    {
        $start = microtime(true);
        $data = $request->validated();
        $data['status'] = $data['status'] === '1' ? 'aktif' : 'nonaktif';

        $before = microtime(true);
        $jobPosition = JobPosition::create($data);
        $after = microtime(true);

        Log::debug('JobPosition store timings', [
            'total_ms' => round((microtime(true) - $start) * 1000, 2),
            'create_ms' => round(($after - $before) * 1000, 2),
            'id' => $jobPosition->id ?? null,
        ]);

        return redirect()->route('job-position.index')->with('success', 'Posisi Pekerjaan berhasil ditambahkan.');
    }

    public function edit(JobPosition $jobPosition)
    {
        return view('job-position.form-edit', compact('jobPosition'));
    }
    public function editData($id)
    {
        $jobPosition = JobPosition::findOrFail($id);
        return response()->json([
            'id' => $jobPosition->id,
            'kode' => $jobPosition->kode,
            'nama' => $jobPosition->nama,
            'status' => $jobPosition->status,
        ]);
    }

    public function update(JobPositionRequest $request, JobPosition $jobPosition)
    {
        $data = $request->validated();
        $data['status'] = $data['status'] === '1' ? 'aktif' : 'nonaktif';

        Log::debug('Data update job position', [
            'id' => $jobPosition->id,
            'data' => $data,
        ]);

        $jobPosition->update($data);
        return redirect()->route('job-position.index')->with('success', 'Posisi Pekerjaan berhasil diperbarui.');
    }

    public function toggleStatus(JobPosition $jobPosition)
    {
        $jobPosition->update([
            'status' => $jobPosition->status === 'aktif' ? 'nonaktif' : 'aktif',
        ]);

        return response()->json([
            'success' => true,
            'status' => $jobPosition->status,
        ]);
    }

    public function destroy(JobPosition $jobPosition)
    {
        $jobPosition->delete();
        return redirect()->route('job-position.index')->with('success', 'Posisi Pekerjaan berhasil dihapus.');
    }

    public function trash()
    {
        $jobPositions = JobPosition::onlyTrashed()->paginate(10);
        return view('job-position.trash', compact('jobPositions'));
    }

    public function restore($id)
    {
        JobPosition::onlyTrashed()->findOrFail($id)->restore();
        return redirect()->route('job-position.index')->with('success', 'Posisi Pekerjaan berhasil dipulihkan.');
    }
}