<?php

namespace App\Http\Controllers;

use App\Http\Requests\DepartemenRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Departemen;
use App\Services\CodeGenerator;

class DepartemenController extends Controller
{
    public function index(Request $request)
    {
        $startIndex = microtime(true);
        $statusOptions = [
            'aktif' => 'Aktif',
            'nonaktif' => 'Nonaktif',
        ];

        $departemen = $this->filter($request)->latest()->paginate(10)->withQueryString();

        if ($request->ajax() || $request->wantsJson()) {
            Log::debug('Departemen index timings', ['ajax' => true, 'ms' => round((microtime(true) - $startIndex) * 1000, 2)]);
            return view('components.table.table', compact('departemen'));
        }

        Log::debug('Departemen index timings', ['ajax' => false, 'ms' => round((microtime(true) - $startIndex) * 1000, 2)]);
        return view('departemen.index', compact('statusOptions', 'departemen'));
    }

    private function filter(Request $request)
    {
        $search = $request->query('search');
        $status = $request->has('status')
            ? (array) $request->query('status', [])
            : null;

        return Departemen::query()
            ->when($search, fn($query) => $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('kode', 'like', "%{$search}%");
            }))
            ->when(!is_null($status), fn($query) => $query->whereIn('status', $status));
    }

    public function create()
    {
        $previewKode = \App\Services\CodeGenerator::generate(\App\Models\Departemen::class, 'DEP');
        return view('departemen.form-create', compact('previewKode'));
    }

    public function store(DepartemenRequest $request)
    {
        $start = microtime(true);
        $data = $request->validated();
        $data['status'] = $data['status'] === '1' ? 'aktif' : 'nonaktif';

        $before = microtime(true);
        $departemen = Departemen::create($data);
        $after = microtime(true);

        Log::debug('Departemen store timings', [
            'total_ms' => round((microtime(true) - $start) * 1000, 2),
            'create_ms' => round(($after - $before) * 1000, 2),
            'id' => $departemen->id ?? null,
        ]);

        return redirect()->route('departemen.index')->with('success', 'Departemen berhasil ditambahkan.');
    }

    public function edit(Departemen $departemen)
    {
        return view('departemen.form-edit', compact('departemen'));
    }
    public function editData($id)
    {
        $departemen = Departemen::findOrFail($id);
        return response()->json([
            'id' => $departemen->id,
            'kode' => $departemen->kode,
            'nama' => $departemen->nama,
            'status' => $departemen->status,
        ]);
    }

    public function update(DepartemenRequest $request, Departemen $departemen)
    {
        $data = $request->validated();
        $data['status'] = $data['status'] === '1' ? 'aktif' : 'nonaktif';

        $departemen->update($data);
        return redirect()->route('departemen.index')->with('success', 'Departemen berhasil diperbarui.');
    }

    public function destroy(Departemen $departemen)
    {
        $departemen->delete();
        return redirect()->route('departemen.index')->with('success', 'Departemen berhasil dihapus.');
    }

    public function trash()
    {
        $departemens = Departemen::onlyTrashed()->paginate(10);
        return view('departemen.trash', compact('departemens'));
    }

    public function restore($id)
    {
        Departemen::onlyTrashed()->findOrFail($id)->restore();
        return redirect()->route('departemen.index')->with('success', 'Departemen berhasil dipulihkan.');
    }
}

    // {
    //     /**
    //      * Display a listing of the resource.
    //      */
    //     public function index(Request $request)
    //     {
    //         $statusOptions = [
    //             'aktif' => 'Aktif',
    //             'nonaktif' => 'Nonaktif',
    //         ];

    //         // Dummy data departemen
    //         $departemen = collect([
    //             [
    //                 'id' => 1,
    //                 'kode' => 'DEPT001',
    //                 'nama' => 'Human Resources',
    //                 'status' => 'aktif',
    //             ],
    //             [
    //                 'id' => 2,
    //                 'kode' => 'DEPT002',
    //                 'nama' => 'Finance & Accounting',
    //                 'status' => 'aktif',
    //             ],
    //             [
    //                 'id' => 3,
    //                 'kode' => 'DEPT003',
    //                 'nama' => 'Information Technology',
    //                 'status' => 'aktif',
    //             ],
    //             [
    //                 'id' => 4,
    //                 'kode' => 'DEPT004',
    //                 'nama' => 'Marketing',
    //                 'status' => 'aktif',
    //             ],
    //             [
    //                 'id' => 5,
    //                 'kode' => 'DEPT005',
    //                 'nama' => 'Sales',
    //                 'status' => 'aktif',
    //             ],
    //             [
    //                 'id' => 6,
    //                 'kode' => 'DEPT006',
    //                 'nama' => 'Operations',
    //                 'status' => 'aktif',
    //             ],
    //             [
    //                 'id' => 7,
    //                 'kode' => 'DEPT007',
    //                 'nama' => 'Procurement',
    //                 'status' => 'aktif',
    //             ],
    //             [
    //                 'id' => 8,
    //                 'kode' => 'DEPT008',
    //                 'nama' => 'Legal & Compliance',
    //                 'status' => 'nonaktif',
    //             ],
    //             [
    //                 'id' => 9,
    //                 'kode' => 'DEPT009',
    //                 'nama' => 'Customer Service',
    //                 'status' => 'aktif',
    //             ],
    //             [
    //                 'id' => 10,
    //                 'kode' => 'DEPT010',
    //                 'nama' => 'Research & Development',
    //                 'status' => 'nonaktif',
    //             ],
    //         ]);

    //         $departemen = $this->filterDummy($departemen, $request);

    //         // Request AJAX dari filter
    //         if ($request->ajax() || $request->wantsJson()) {
    //             return view('departemen._table', compact('departemen'));
    //         }

    //         return view('departemen.index', compact(
    //             'statusOptions',
    //             'departemen'
    //         ));
    //     }

    
    //     private function filterDummy($departemen, Request $request)
    //     {
    //         $search = $request->query('search');

    //         $status = $request->has('status_state')
    //             ? (array) $request->query('status', [])
    //             : null;

    //         return $departemen
    //             ->when($search, fn($rows) => $rows->filter(
    //                 fn($row) =>
    //                 str_contains(
    //                     strtolower($row['nama']),
    //                     strtolower($search)
    //                 ) ||
    //                     str_contains(
    //                         strtolower($row['kode']),
    //                         strtolower($search)
    //                     )
    //             ))
    //             ->when(!is_null($status), fn($rows) => $rows->filter(
    //                 fn($row) => in_array($row['status'], $status)
    //             ))
    //             ->values();
    //     }

    //     public function create()
    //     {
    //         //
    //     }

    //     public function store(Request $request)
    //     {
    //         //
    //     }

    //     /**
    //      * Display the specified resource.
    //      */
    //     public function show(Departemen $departemen)
    //     {
    //         //
    //     }


    //     public function edit(Departemen $departemen)
    //     {
    //         return response()->json([
    //             'id'               => $departemen->id,
    //             'kode_departemen'  => $departemen->kode_departemen,
    //             'nama_departemen'  => $departemen->nama_departemen,
    //             'status'           => $departemen->status,
    //         ]);
    //     }

    //     /**
    //      * Update the specified resource in storage.
    //      */
    //     public function update(Request $request, Departemen $departemen)
    //     {
    //         //
    //     }

    //     /**
    //      * Remove the specified resource from storage.
    //      */
    //     public function destroy(Departemen $departemen)
    //     {
    //         //
    //     }
    // }