@extends('layouts.app')

@section('content')
    @php
        $stack = fn(...$lines) => array_values(array_filter($lines, 'filled'));
        $hitungSisa = fn($row) => $row->sisa ?? ($row->saldo - $row->terpakai);
    @endphp

    <x-page-header eyebrow="Cuti" title="Saldo Cuti"
        description="Saldo cuti yang telah dihapus. Pulihkan atau hapus permanen di sini." icon="bi-trash-fill">
        <x-slot:badges>
            <x-badge>{{ $saldoCutis->total() }} saldo cuti</x-badge>
        </x-slot:badges>

        <x-slot:actions>
            <x-button variant="outline" icon="bi-arrow-left" href="{{ route('saldo-cuti.index') }}">
                Kembali
            </x-button>
        </x-slot:actions>
    </x-page-header>

    <x-panel>
        <x-table>
            <thead>
                <tr>
                    <th class="app-table__col-no">NO</th>
                    <th>Karyawan</th>
                    <th>Jenis Cuti</th>
                    <th class="sc-col-num">Tahun</th>
                    <th class="sc-col-num">Hak Cuti</th>
                    <th class="sc-col-num">Terpakai</th>
                    <th class="sc-col-num">Sisa</th>
                    <th class="app-table__col-actions">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($saldoCutis as $i => $row)
                    <tr>
                        <td class="app-table__col-no">
                            {{ $saldoCutis->firstItem() + $i }}
                        </td>

                        <td>
                            <x-table.cell-stack :avatar="$row->karyawan?->nama" :lines="$stack($row->karyawan?->nama, $row->karyawan?->nip, $row->karyawan?->departemen?->nama)" />
                        </td>

                        <td>
                            <x-table.cell-stack :lines="$stack($row->cuti?->nama ?? '-', $row->cuti?->kode)" />
                        </td>

                        <td class="sc-col-num fw-semibold">{{ $row->tahun }}</td>
                        <td class="sc-col-num">{{ $row->saldo }} <span class="sc-unit">hari</span></td>
                        <td class="sc-col-num">{{ $row->terpakai }} <span class="sc-unit">hari</span></td>
                        <td class="sc-col-num fw-semibold">{{ $hitungSisa($row) }} <span class="sc-unit">hari</span></td>

                        <td class="app-table__col-actions">
                            <div class="app-table__actions">
                                {{-- Restore --}}
                                <form action="{{ route('saldo-cuti.restore', $row->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <x-button type="submit" variant="icon-success" icon="bi-arrow-counterclockwise"
                                        title="Pulihkan" />
                                </form>

                                {{-- Hapus permanen --}}
                                <form action="{{ route('saldo-cuti.force-delete', $row->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <x-button type="submit" variant="icon-danger" icon="bi-trash3-fill"
                                        title="Hapus permanen" />
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <x-table.empty-row colspan="8" />
                @endforelse
            </tbody>
        </x-table>

        <div class="app-table-footer">
            <span>
                Menampilkan
                {{ $saldoCutis->firstItem() ?? 0 }}–{{ $saldoCutis->lastItem() ?? 0 }}
                dari
                {{ $saldoCutis->total() }}
                entri
            </span>

            <x-pagination :paginator="$saldoCutis" />
        </div>
    </x-panel>
@endsection
