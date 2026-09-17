<?php

namespace App\Http\Controllers;

use App\Http\Requests\SectionRequest;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\CodeGenerator;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $startIndex = microtime(true);
        $statusOptions = [
            'aktif' => 'Aktif',
            'nonaktif' => 'Nonaktif',
        ];

        $section = $this->filter($request)->latest()->paginate(10)->withQueryString();

        if ($request->ajax() || $request->wantsJson()) {
            Log::debug('Section index timings', ['ajax' => true, 'ms' => round((microtime(true) - $startIndex) * 1000, 2)]);
            return view('components.table.table', compact('section'));
        }

        Log::debug('Section index timings', ['ajax' => false, 'ms' => round((microtime(true) - $startIndex) * 1000, 2)]);
        return view('section.index', compact('statusOptions', 'section'));
    }

    private function filter(Request $request)
    {
        $search = $request->query('search');
        $status = $request->has('status')
            ? (array) $request->query('status', [])
            : null;

        return Section::query()
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
        $previewKode = \App\Services\CodeGenerator::generate(\App\Models\Section::class, 'SEC');
        return view('section.form-create', compact('previewKode'));
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
        $section = Section::create($data);
        $after = microtime(true);

        Log::debug('Section store timings', [
            'total_ms' => round((microtime(true) - $start) * 1000, 2),
            'create_ms' => round(($after - $before) * 1000, 2),
            'id' => $section->id ?? null,
        ]);

        return redirect()->route('section.index')->with('success', 'Section berhasil ditambahkan.');
    }

    public function edit(Section $section)
    {
        return view('section.form-edit', compact('section'));
    }
    public function editData($id)
    {
        $section = Section::findOrFail($id);
        return response()->json([
            'id' => $section->id,
            'kode' => $section->kode,
            'nama' => $section->nama,
            'status' => $section->status,
        ]);
    }

    public function update(SectionRequest $request, Section $section)
    {
        $data = $request->validated();
        $data['status'] = $data['status'] === '1' ? 'aktif' : 'nonaktif';

        $section->update($data);
        return redirect()->route('section.index')->with('success', 'Section berhasil diperbarui.');
    }

    public function toggleStatus(Section $section)
    {
        $section->update([
            'status' => $section->status === 'aktif' ? 'nonaktif' : 'aktif',
        ]);

        return response()->json([
            'success' => true,
            'status' => $section->status,
        ]);
    }

    public function destroy(Section $section)
    {
        $section->delete();
        return redirect()->route('section.index')->with('success', 'Section berhasil dihapus.');
    }

    public function trash()
    {
        $sections = Section::onlyTrashed()->paginate(10);
        return view('section.trash', compact('sections'));
    }

    public function restore($id)
    {
        Section::onlyTrashed()->findOrFail($id)->restore();
        return redirect()->route('section.index')->with('success', 'Section berhasil dipulihkan.');
    }
}
