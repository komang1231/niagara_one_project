<x-page-header eyebrow="Permintaan" title="Cuti"
    description="Kelola pengajuan cuti karyawan beserta periode dan rincian cutinya." icon="bi-calendar-check-fill">
    <x-slot:badges>
        <x-badge>
            {{ $permintaanCuti->total() }} permintaan
        </x-badge>
    </x-slot:badges>

    <x-slot:actions>
        <x-button variant="outline" icon="bi-trash" href="{{ route('permintaan-cuti.trash') }}">
            Trash
        </x-button>

        <x-button variant="primary" icon="bi-plus-lg" data-bs-toggle="offcanvas"
            data-bs-target="#offcanvas-permintaan-cuti">
            Tambah Permintaan Cuti
        </x-button>
    </x-slot:actions>
</x-page-header>


<x-panel>

    {{-- FILTER --}}
    <div>
        <x-filter.bar :clearable="['search']" ajax-target="#cuti-table">
            <x-filter.search name="search_cuti" placeholder="Cari kode, nama pemohon, atau jenis cuti..." />
        </x-filter.bar>
    </div>


    <hr class="app-panel__divider">


    {{-- TABLE --}}
    <div id="cuti-table"></div>

    <x-table>

        <thead>
            <tr>
                <th class="app-table__col-no">NO</th>
                <th>Permohonan</th>
                <th>Jenis Cuti</th>
                <th>Periode & Rincian</th>
                <th>Alasan</th>
                <th>Status</th>
                <th class="text-end">Aksi</th>
            </tr>
        </thead>


        <tbody>

            @forelse ($permintaanCuti as $i => $row)

                @php

                    /*
                    |--------------------------------------------------------------------------
                    | Detail Cuti
                    |--------------------------------------------------------------------------
                    */

                    $details = $row->details ?? collect();

                    /*
                    |--------------------------------------------------------------------------
                    | Jumlah Hari
                    |--------------------------------------------------------------------------
                    */

                    $totalHari = $details->count();

                    $totalSetengahHari = $details->where('setengah_hari', true)->count();

                    $totalHariPenuh = $details->where('setengah_hari', false)->count();

                    /*
                    |--------------------------------------------------------------------------
                    | Format Periode
                    |--------------------------------------------------------------------------
                    */

                    $tanggalMulai = \Carbon\Carbon::parse($row->tanggal_mulai);

                    $tanggalSelesai = \Carbon\Carbon::parse($row->tanggal_selesai);

                    /*
                    |--------------------------------------------------------------------------
                    | Format Bahasa Indonesia
                    |--------------------------------------------------------------------------
                    */

                    $bulan = [
                        1 => 'Jan',
                        2 => 'Feb',
                        3 => 'Mar',
                        4 => 'Apr',
                        5 => 'Mei',
                        6 => 'Jun',
                        7 => 'Jul',
                        8 => 'Agu',
                        9 => 'Sep',
                        10 => 'Okt',
                        11 => 'Nov',
                        12 => 'Des',
                    ];

                    /*
                    |--------------------------------------------------------------------------
                    | Detail Setengah Hari
                    |--------------------------------------------------------------------------
                    */

                    $setengahHariDetails = $details->where('setengah_hari', true)->values();

                @endphp


                <tr>

                    {{-- ==========================================================
                        NO
                    =========================================================== --}}
                    <td class="app-table__col-no">
                        {{ $permintaanCuti->firstItem() + $i }}
                    </td>


                    {{-- ==========================================================
                        PERMOHONAN
                    =========================================================== --}}
                    <td>
                        <x-table.cell-stack :avatar="$row->karyawan?->avatar" :lines="[$row->karyawan?->nama ?? 'Pemohon tidak ditemukan', $row->kode]" />
                    </td>


                    {{-- ==========================================================
                        JENIS CUTI
                    =========================================================== --}}
                    <td>

                        <x-table.cell-stack :lines="[$row->cuti?->nama ?? 'Jenis cuti tidak ditemukan']" />

                    </td>


                    {{-- ==========================================================
                        PERIODE & RINCIAN
                    =========================================================== --}}
                    <td>

                        {{-- PERIODE --}}
                        <div class="fw-semibold">

                            {{ $tanggalMulai->day }}
                            {{ $bulan[$tanggalMulai->month] }}
                            {{ $tanggalMulai->year }}

                            <span class="text-muted mx-1">
                                –
                            </span>

                            {{ $tanggalSelesai->day }}
                            {{ $bulan[$tanggalSelesai->month] }}
                            {{ $tanggalSelesai->year }}

                        </div>


                        {{-- RINGKASAN JUMLAH HARI --}}
                        <div class="d-flex flex-wrap gap-1 mt-2">

                            @if ($totalHari > 0)
                                <span class="badge bg-light text-dark border">
                                    {{ $totalHari }} hari
                                </span>
                            @endif


                            @if ($totalHariPenuh > 0)
                                <span class="badge bg-light text-dark border">
                                    {{ $totalHariPenuh }} hari penuh
                                </span>
                            @endif


                            @if ($totalSetengahHari > 0)
                                <span class="badge bg-light text-dark border">
                                    {{ $totalSetengahHari }} setengah hari
                                </span>
                            @endif

                        </div>


                        {{-- DETAIL SETENGAH HARI --}}
                        @if ($setengahHariDetails->isNotEmpty())
                            <div class="mt-2">

                                <div class="small text-muted mb-1">
                                    <i class="bi bi-clock me-1"></i>
                                    Setengah hari:
                                </div>


                                <div class="d-flex flex-column gap-1">

                                    @foreach ($setengahHariDetails as $detail)
                                        @php
                                            $tanggalDetail = \Carbon\Carbon::parse($detail->tanggal);
                                        @endphp

                                        <div class="small">

                                            <span class="fw-semibold">
                                                {{ $tanggalDetail->day }}
                                                {{ $bulan[$tanggalDetail->month] }}
                                            </span>

                                            <span class="text-muted">
                                                · Setengah hari
                                            </span>

                                        </div>
                                    @endforeach

                                </div>

                            </div>
                        @endif

                    </td>


                    {{-- ==========================================================
                        ALASAN
                    =========================================================== --}}
                    <td>

                        @if ($row->alasan)
                            <div class="text-truncate" style="max-width: 220px;" title="{{ $row->alasan }}">
                                {{ $row->alasan }}
                            </div>
                        @else
                            <span class="text-muted">
                                Tidak ada alasan
                            </span>
                        @endif

                    </td>


                    {{-- ==========================================================
                        STATUS
                    =========================================================== --}}
                    <td>

                        @include('permintaan.partials.status', ['row' => $row])

                    </td>


                    {{-- ==========================================================
                        AKSI
                    =========================================================== --}}
                    <td>

                        @include('permintaan.partials.aksi', [
                            'row' => $row,
                            'slug' => 'permintaan-cuti',
                            'label' => 'Permintaan Cuti',
                        ])

                    </td>

                </tr>


            @empty

                <x-table.empty-row :colspan="7" text="Belum ada permintaan cuti." />

            @endforelse

        </tbody>

    </x-table>


    {{-- PAGINATION --}}
    <div class="mt-3">
        {{ $permintaanCuti->links() }}
    </div>

</x-panel>
```
