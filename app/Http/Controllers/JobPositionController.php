<?php

namespace App\Http\Controllers;

use App\Http\Requests\JobPositionRequest;
use App\Models\JobPosition;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\CodeGenerator;

class JobPositionController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $startIndex = microtime(true);

        $previewKode = CodeGenerator::generate(
            JobPosition::class,
            'JPOS'
        );

        $statusOptions = [
            'aktif' => 'Aktif',
            'nonaktif' => 'Nonaktif',
        ];

        $jobPosition = $this->filter($request)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        if ($request->ajax() || $request->wantsJson()) {
            Log::debug('JobPosition index timings', [
                'ajax' => true,
                'ms' => round(
                    (microtime(true) - $startIndex) * 1000,
                    2
                )
            ]);

            return view(
                'components.table.table',
                compact('jobPosition')
            );
        }

        Log::debug('JobPosition index timings', [
            'ajax' => false,
            'ms' => round(
                (microtime(true) - $startIndex) * 1000,
                2
            )
        ]);

        $sections = Section::where('status', 'aktif')
            ->orderBy('nama')
            ->get();

        return view(
            'job-position.index',
            compact(
                'statusOptions',
                'jobPosition',
                'sections',
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

        return JobPosition::query()
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
        $previewKode = CodeGenerator::generate(JobPosition::class, 'JP');

        $sections = Section::where('status', 'aktif')
            ->orderBy('nama')
            ->get();

        return view('job-position.form-create', compact(
            'previewKode',
            'sections'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
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

        return redirect()->route('job-position.index')->with('success', 'Job Position berhasil ditambahkan.');
    }

    public function edit(JobPosition $jobPosition)
    {
        $sections = Section::where('status', 'aktif')
            ->orderBy('nama')
            ->get();

        return view('job-position.form-edit', compact('jobPosition', 'sections'));
    }
    public function editData($id)
    {
        $jobPosition = JobPosition::findOrFail($id);
        return response()->json([
            'id' => $jobPosition->id,
            'kode' => $jobPosition->kode,
            'section_id' => $jobPosition->section_id,
            'nama' => $jobPosition->nama,
            'status' => $jobPosition->status,
        ]);
    }

    public function update(JobPositionRequest $request, JobPosition $jobPosition)
    {
        $data = $request->validated();
        $data['status'] = $data['status'] === '1' ? 'aktif' : 'nonaktif';

        $jobPosition->update($data);
        return redirect()->route('job-position.index')->with('success', 'Job Position berhasil diperbarui.');
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
        return redirect()->route('job-position.index')->with('success', 'Job Position berhasil dihapus.');
    }

    public function trash()
    {
        $jobPositions = JobPosition::onlyTrashed()->paginate(10);
        return view('job-position.trash', compact('jobPositions'));
    }

    public function restore($id)
    {
        JobPosition::onlyTrashed()->findOrFail($id)->restore();
        return redirect()->route('job-position.index')->with('success', 'Job Position berhasil dipulihkan.');
    }

    public function forceDelete($id)
    {
        JobPosition::onlyTrashed()
            ->findOrFail($id)
            ->forceDelete();

        return redirect()
            ->route('job-position.index')
            ->with('success', 'Job Position berhasil dihapus permanen.');
    }
}
