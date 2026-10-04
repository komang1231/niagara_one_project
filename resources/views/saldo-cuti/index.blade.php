@extends('layouts.app')

@section('content')
    <x-page-header eyebrow="Cuti" title="Saldo Cuti"
        description="Kelola dan pantau saldo cuti tahunan karyawan."
        icon="bi-calendar2-check-fill">

        <x-slot:badges>
            <x-badge>{{ $saldoCuti->total() }} saldo cuti</x-badge>
        </x-slot:badges>
    </x-page-header>

    <x-panel>
        <x-filter.bar :clearable="['search', 'tahun']" ajax-target="#saldo-cuti-table">

            <x-filter.search placeholder="Cari karyawan atau NIP" />

            <x-filter.date-range
                name="tahun"
                label="Tahun"
                type="year"
                :default-from="$tahun"
                :default-to="$tahun"
            />

        </x-filter.bar>

        <hr class="app-panel__divider">

        <div id="saldo-cuti-table">
            <x-table>
                <thead>
                    <tr>
                        <th class="app-table__col-no">NO</th>
                        <th>Karyawan</th>
                        <th class="sc-col-num">Tahun</th>
                        <th class="sc-col-num">Kuota</th>
                        <th class="sc-col-num">Terpakai</th>
                        <th class="sc-col-num">Sisa</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($saldoCuti as $i => $row)
                        @php
                            $sisa = max(0, $row->saldo - $row->terpakai);
                        @endphp

                        <tr>
                            <td class="app-table__col-no">
                                {{ $saldoCuti->firstItem() + $i }}
                            </td>

                            <td>
                                <x-table.cell-stack
                                    :avatar="$row->karyawan?->nama"
                                    :lines="array_values(array_filter([
                                        $row->karyawan?->nama,
                                        $row->karyawan?->nip,
                                        $row->karyawan?->departemen?->nama,
                                    ], 'filled'))"
                                />
                            </td>

                            <td class="sc-col-num fw-semibold">
                                {{ $row->tahun }}
                            </td>

                            <td class="sc-col-num">
                                {{ $row->saldo }}
                                <span class="sc-unit">hari</span>
                            </td>

                            <td class="sc-col-num">
                                {{ $row->terpakai }}
                                <span class="sc-unit">hari</span>
                            </td>

                            <td class="sc-col-num">
                                <span class="fw-semibold">
                                    {{ $sisa }}
                                </span>
                                <span class="sc-unit">hari</span>
                            </td>
                        </tr>
                    @empty
                        <x-table.empty-row colspan="6" />
                    @endforelse
                </tbody>
            </x-table>

            <div class="app-table-footer">
                <span>
                    Menampilkan
                    {{ $saldoCuti->firstItem() ?? 0 }}–{{ $saldoCuti->lastItem() ?? 0 }}
                    dari
                    {{ $saldoCuti->total() }}
                    entri
                </span>

                <x-pagination :paginator="$saldoCuti" />
            </div>
        </div>
    </x-panel>
@endsection