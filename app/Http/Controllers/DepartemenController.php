<?php

namespace App\Http\Controllers;

use App\Models\Departemen;
use Illuminate\Http\Request;

class DepartemenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $statusOptions = [
            'aktif' => 'Aktif',
            'nonaktif' => 'Nonaktif',
        ];

        // Dummy data departemen
        $departemen = collect([
            [
                'id' => 1,
                'kode' => 'DEPT001',
                'nama' => 'Human Resources',
                'status' => 'aktif',
            ],
            [
                'id' => 2,
                'kode' => 'DEPT002',
                'nama' => 'Finance & Accounting',
                'status' => 'aktif',
            ],
            [
                'id' => 3,
                'kode' => 'DEPT003',
                'nama' => 'Information Technology',
                'status' => 'aktif',
            ],
            [
                'id' => 4,
                'kode' => 'DEPT004',
                'nama' => 'Marketing',
                'status' => 'aktif',
            ],
            [
                'id' => 5,
                'kode' => 'DEPT005',
                'nama' => 'Sales',
                'status' => 'aktif',
            ],
            [
                'id' => 6,
                'kode' => 'DEPT006',
                'nama' => 'Operations',
                'status' => 'aktif',
            ],
            [
                'id' => 7,
                'kode' => 'DEPT007',
                'nama' => 'Procurement',
                'status' => 'aktif',
            ],
            [
                'id' => 8,
                'kode' => 'DEPT008',
                'nama' => 'Legal & Compliance',
                'status' => 'nonaktif',
            ],
            [
                'id' => 9,
                'kode' => 'DEPT009',
                'nama' => 'Customer Service',
                'status' => 'aktif',
            ],
            [
                'id' => 10,
                'kode' => 'DEPT010',
                'nama' => 'Research & Development',
                'status' => 'nonaktif',
            ],
        ]);

        $departemen = $this->filterDummy($departemen, $request);

        // Request AJAX dari filter
        if ($request->ajax() || $request->wantsJson()) {
            return view('departemen._table', compact('departemen'));
        }

        return view('departemen.index', compact(
            'statusOptions',
            'departemen'
        ));
    }

   
    private function filterDummy($departemen, Request $request)
    {
        $search = $request->query('search');

        $status = $request->has('status_state')
            ? (array) $request->query('status', [])
            : null;

        return $departemen
            ->when($search, fn($rows) => $rows->filter(
                fn($row) =>
                str_contains(
                    strtolower($row['nama']),
                    strtolower($search)
                ) ||
                    str_contains(
                        strtolower($row['kode']),
                        strtolower($search)
                    )
            ))
            ->when(!is_null($status), fn($rows) => $rows->filter(
                fn($row) => in_array($row['status'], $status)
            ))
            ->values();
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Departemen $departemen)
    {
        //
    }


    public function edit(Departemen $departemen)
    {
        return response()->json([
            'id'               => $departemen->id,
            'kode_departemen'  => $departemen->kode_departemen,
            'nama_departemen'  => $departemen->nama_departemen,
            'status'           => $departemen->status,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Departemen $departemen)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Departemen $departemen)
    {
        //
    }
}