<?php

namespace App\Http\Controllers;

use App\Http\Requests\BankRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use App\Models\Bank;
use App\Services\CodeGenerator;

class BankController extends Controller
{
    public function index(Request $request)
    {
        $startIndex = microtime(true);
        $previewKode = \App\Services\CodeGenerator::generate(\App\Models\Bank::class, 'BANK');
        $statusOptions = [
            'aktif' => 'Aktif',
            'nonaktif' => 'Nonaktif',
        ];

        $bank = $this->filter($request)
            ->orderByDesc('kode') 
            // ->latest()
            ->paginate(10)
            ->withQueryString();

        if ($request->ajax() || $request->wantsJson()) {
            Log::debug('Bank index timings', ['ajax' => true, 'ms' => round((microtime(true) - $startIndex) * 1000, 2)]);
            return view('components.table.table', compact('bank'));
        }

        Log::debug('Bank index timings', ['ajax' => false, 'ms' => round((microtime(true) - $startIndex) * 1000, 2)]);
        return view('bank.index', compact('statusOptions', 'bank', 'previewKode'));
    }

    private function filter(Request $request)
    {
        $search = $request->query('search');
        $status = $request->has('status')
            ? (array) $request->query('status', [])
            : null;

        return Bank::query()
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

    public function store(BankRequest $request)
    {
        $start = microtime(true);
        $data = $request->validated();
        $data['status'] = $data['status'] === '1' ? 'aktif' : 'nonaktif';

        $before = microtime(true);
        $bank = Bank::create($data);
        $after = microtime(true);

        Log::debug('Bank store timings', [
            'total_ms' => round((microtime(true) - $start) * 1000, 2),
            'create_ms' => round(($after - $before) * 1000, 2),
            'id' => $bank->id ?? null,
        ]);

        return redirect()->route('bank.index')->with('success', 'Bank berhasil ditambahkan.');
    }

    public function edit(Bank $bank)
    {
        return view('bank.form-edit', compact('bank'));
    } 
    public function editData($id)
    {
        $bank = Bank::findOrFail($id);
        return response()->json([
            'id' => $bank->id,
            'kode' => $bank->kode,
            'nama' => $bank->nama,
            'kuota_hari_default' => $bank->kuota_hari_default,
            'status' => $bank->status,
        ]);
    }

    public function update(BankRequest $request, Bank $bank)
    {
        $data = $request->validated();
        $data['status'] = $data['status'] === '1' ? 'aktif' : 'nonaktif';

        Log::debug('Data update bank', [
            'id' => $bank->id,
            'data' => $data,
        ]);

        $bank->update($data);
        return redirect()->route('bank.index')->with('success', 'Bank berhasil diperbarui.');
    }

    public function toggleStatus(Bank $bank)
    {
        $bank->update([
            'status' => $bank->status === 'aktif' ? 'nonaktif' : 'aktif',
        ]);

        return response()->json([
            'success' => true,
            'status' => $bank->status,
        ]);
    }

    public function destroy($id)
    {
        $bank = Bank::findOrFail($id);

        // if ($bank->divisi()->exists()) {
        //     return redirect()
        //         ->route('bank.index')
        //         ->with(
        //             'error',
        //             'Bank tidak dapat dihapus karena masih digunakan oleh data Divisi.'
        //         );
        // }

        $bank->delete();

        return redirect()
            ->route('bank.index')
            ->with(
                'success',
                'Bank berhasil dipindahkan ke Trash.'
            );
    }


    public function trash()
    {
        $banks = Bank::onlyTrashed()->paginate(10);
        return view('bank.trash', compact('banks'));
    }

    public function restore($id)
    {
        Bank::onlyTrashed()->findOrFail($id)->restore();
        return redirect()->route('bank.trash')->with('success', 'Bank berhasil dipulihkan.');
    }

    public function forceDelete($id)
    {
        Bank::onlyTrashed()
            ->findOrFail($id)
            ->forceDelete();

        return redirect()
            ->route('bank.trash')
            ->with('success', 'Bank berhasil dihapus permanen.');
    }
}
