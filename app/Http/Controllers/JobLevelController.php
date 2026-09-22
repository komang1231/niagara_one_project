<?php

namespace App\Http\Controllers;

use App\Http\Requests\JobLevelRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\JobLevel;
use App\Services\CodeGenerator;

class JobLevelController extends Controller
{
    public function index(Request $request)
    {
        $startIndex = microtime(true);
        $previewKode = \App\Services\CodeGenerator::generate(\App\Models\JobLevel::class, 'JLEV');
        $statusOptions = [
            'aktif' => 'Aktif',
            'nonaktif' => 'Nonaktif',
        ];

        $jobLevel = $this->filter($request)
            ->orderByDesc('kode')
            // ->latest()
            ->paginate(10)
            ->withQueryString();

        if ($request->ajax() || $request->wantsJson()) {
            Log::debug('JobLevel index timings', ['ajax' => true, 'ms' => round((microtime(true) - $startIndex) * 1000, 2)]);
            return view('components.table.table', compact('jobLevel'));
        }

        Log::debug('JobLevel index timings', ['ajax' => false, 'ms' => round((microtime(true) - $startIndex) * 1000, 2)]);
        return view('job-level.index', compact('statusOptions', 'jobLevel', 'previewKode'));
    }

    private function filter(Request $request)
    {
        $search = $request->query('search');
        $status = $request->has('status')
            ? (array) $request->query('status', [])
            : null;

        return JobLevel::query()
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

    public function store(JobLevelRequest $request)
    {
        $start = microtime(true);
        $data = $request->validated();
        $data['status'] = $data['status'] === '1' ? 'aktif' : 'nonaktif';

        $before = microtime(true);
        $jobLevel = JobLevel::create($data);
        $after = microtime(true);

        Log::debug('JobLevel store timings', [
            'total_ms' => round((microtime(true) - $start) * 1000, 2),
            'create_ms' => round(($after - $before) * 1000, 2),
            'id' => $jobLevel->id ?? null,
        ]);

        return redirect()->route('job-level.index')->with('success', 'Level Pekerjaan berhasil ditambahkan.');
    }

    public function edit(JobLevel $jobLevel)
    {
        return view('job-level.form-edit', compact('jobLevel'));
    }
    public function editData($id)
    {
        $jobLevel = JobLevel::findOrFail($id);
        return response()->json([
            'id' => $jobLevel->id,
            'kode' => $jobLevel->kode,
            'nama' => $jobLevel->nama,
            'status' => $jobLevel->status,
        ]);
    }

    public function update(JobLevelRequest $request, JobLevel $jobLevel)
    {
        $data = $request->validated();
        $data['status'] = $data['status'] === '1' ? 'aktif' : 'nonaktif';

        Log::debug('Data update job level', [
            'id' => $jobLevel->id,
            'data' => $data,
        ]);

        $jobLevel->update($data);
        return redirect()->route('job-level.index')->with('success', 'Level Pekerjaan berhasil diperbarui.');
    }

    public function toggleStatus(JobLevel $jobLevel)
    {
        $jobLevel->update([
            'status' => $jobLevel->status === 'aktif' ? 'nonaktif' : 'aktif',
        ]);

        return response()->json([
            'success' => true,
            'status' => $jobLevel->status,
        ]);
    }

    public function destroy($id)
    {
        $jobLevel = JobLevel::findOrFail($id);

        if ($jobLevel->karyawan()->exists()) {
            return redirect()
                ->route('job-level.index')
                ->with(
                    'error',
                    'Job Level tidak dapat dihapus karena masih digunakan oleh data Karyawan.'
                );
        }

        $jobLevel->delete();

        return redirect()
            ->route('job-level.index')
            ->with(
                'success',
                'Job Level berhasil dipindahkan ke Trash.'
            );
    }

    public function trash()
    {
        $jobLevels = JobLevel::onlyTrashed()->paginate(10);
        return view('job-level.trash', compact('jobLevels'));
    }

    public function restore($id)
    {
        JobLevel::onlyTrashed()->findOrFail($id)->restore();
        return redirect()->route('job-level.trash')->with('success', 'Level Pekerjaan berhasil dipulihkan.');
    }

    public function forceDelete($id)
    {
        JobLevel::onlyTrashed()
            ->findOrFail($id)
            ->forceDelete();

        return redirect()
            ->route('job-level.trash')
            ->with('success', 'Job Level berhasil dihapus permanen.');
    }
}
