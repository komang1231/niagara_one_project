@extends('layouts.app')

@section('content')
    @php
        /*
         * KONTRAK DATA YANG DIHARAPKAN DARI CONTROLLER (index)
         * ------------------------------------------------------------------
         * $kontrak                 paginator KontrakKaryawan, eager load: karyawan (nama, nip), statusKepegawaian (nama)
         * $karyawanTanpaKontrak    collection Karyawan yang BELUM punya record di kontrak_karyawans
         *                          (eager load statusKepegawaian). Filter-nya di backend, bukan di Blade.
         * $statusKepegawaianOptions  [id => nama] untuk select Status Kepegawaian di form edit
         * $previewNomor            nomor kontrak berikutnya (preview saja, nomor final dibuat backend)
         *
         * OPSIONAL per baris (kalau backend sudah menyediakan, nilai ini yang dipakai;
         * kalau belum, tampilan menghitung sementara dari tanggal_berakhir):
         * $row->bisa_diperpanjang      bool   sudah masuk H-30 atau belum
         * $row->perpanjangan_mulai     date   tanggal pertama perpanjangan boleh dilakukan
         * $row->periode_baru_mulai     date   tanggal mulai setelah diperpanjang
         * $row->periode_baru_akhir     date   tanggal berakhir setelah diperpanjang
         */

        // Aturan kontrak. Ikuti backend: ubah di sini kalau backend memakai angka lain.
        $durasiBulan = 12; // masa satu periode kontrak (bulan)
        $hariSebelumBerakhir = 7; // perpanjangan dibuka H-7

        $stack = fn(...$lines) => array_values(array_filter($lines, 'filled'));
        $tgl = fn($v, $format = 'd F Y') => $v
            ? \Carbon\Carbon::parse($v)->locale('id')->translatedFormat($format)
            : '-';
        $iso = fn($v) => $v ? \Carbon\Carbon::parse($v)->format('Y-m-d') : '';
    @endphp

    <x-page-header eyebrow="Karyawan" title="Kontrak Karyawan"
        description="Kelola periode kontrak dan perpanjangan kontrak karyawan." icon="bi-file-earmark-check-fill">
        <x-slot:badges>
            <x-badge>{{ $kontrak->total() }} kontrak</x-badge>
        </x-slot:badges>

        <x-slot:actions>
            <x-button variant="primary" icon="bi-plus-lg" data-bs-toggle="offcanvas" data-bs-target="#offcanvas-kontrak">
                Buat Kontrak Awal
            </x-button>
        </x-slot:actions>
    </x-page-header>

    <x-panel>
        {{-- Aksi kontrak pertama: tidak ada tombol tambah di page header --}}
        <div class="kontrak-toolbar">
            <div>
                <h2 class="kontrak-toolbar__title">Daftar Kontrak</h2>
                <p class="kontrak-toolbar__desc">
                    Kontrak pertama dibuat dari sini. Kontrak berikutnya lewat aksi Perpanjang.
                </p>
            </div>
        </div>

        <hr class="app-panel__divider">

        <div id="kontrak-table">
            <x-table>
                <thead>
                    <tr>
                        <th class="app-table__col-no">NO</th>
                        <th>Nomor Kontrak</th>
                        <th>Karyawan</th>
                        <th>Status Kepegawaian</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Berakhir</th>
                        <th class="app-table__col-actions">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($kontrak as $i => $row)
                        @php
                            $berakhir = \Carbon\Carbon::parse($row->tanggal_berakhir)->startOfDay();

                            // Mulai kapan perpanjangan dibuka (H-30)
                            $bukaPerpanjangan = $row->perpanjangan_mulai
                                ? \Carbon\Carbon::parse($row->perpanjangan_mulai)->startOfDay()
                                : $berakhir->copy()->subDays($hariSebelumBerakhir);

                            $sudahDiperpanjang = $row->karyawan
                                ?->kontrakKaryawan()
                                ->whereDate('tanggal_mulai', $row->tanggal_berakhir)
                                ->exists();

                            $bisaPerpanjang =
                                !$sudahDiperpanjang &&
                                ($row->bisa_diperpanjang ?? now()->startOfDay()->gte($bukaPerpanjangan));
                        @endphp

                        <tr>
                            <td class="app-table__col-no">
                                {{ $kontrak->firstItem() + $i }}
                            </td>

                            <td class="fw-semibold">{{ $row->nomor_kontrak }}</td>

                            <td>
                                <x-table.cell-stack :avatar="$row->karyawan?->nama" :lines="$stack($row->karyawan?->nama, $row->karyawan?->nip)" />
                            </td>

                            <td>
                                <x-badge>{{ $row->statusKepegawaian?->nama ?? '-' }}</x-badge>
                            </td>

                            <td class="kontrak-col-date">{{ $tgl($row->tanggal_mulai) }}</td>
                            <td class="kontrak-col-date">{{ $tgl($row->tanggal_berakhir) }}</td>

                            <td class="app-table__col-actions">
                                <div class="app-table__actions">
                                    {{-- Edit (offcanvas) --}}
                                    {{-- <x-button variant="icon-edit" icon="bi-pencil" title="Edit" data-bs-toggle="offcanvas"
                                        data-bs-target="#offcanvas-kontrak-edit"
                                        data-edit-url="{{ route('kontrak-karyawan.edit-data', $row->id) }}"
                                        data-update-url="{{ route('kontrak-karyawan.update', $row->id) }}" /> --}}

                                    @if ($sudahDiperpanjang)
                                        <span class="kontrak-hint" style="color: var(--bs-success)" title="Kontrak sudah diperpanjang">
                                            <i class="bi bi-check-circle"></i>
                                            Diperpanjang
                                        </span>
                                    @elseif ($bisaPerpanjang)
                                        {{-- Perpanjang: tidak langsung submit, konfirmasi dulu (kontrak-karyawan.js).
                                             Tidak ada input tanggal/karyawan: backend yang menentukan periode baru. --}}
                                        <form action="{{ route('kontrak-karyawan.perpanjang', $row->id) }}" method="POST"
                                            data-perpanjang-form data-durasi-bulan="{{ $durasiBulan }}"
                                            data-karyawan="{{ $row->karyawan?->nama }}"
                                            data-nomor="{{ $row->nomor_kontrak }}"
                                            data-mulai="{{ $iso($row->tanggal_mulai) }}"
                                            data-akhir="{{ $iso($row->tanggal_berakhir) }}"
                                            data-baru-mulai="{{ $iso($row->periode_baru_mulai) }}"
                                            data-baru-akhir="{{ $iso($row->periode_baru_akhir) }}">
                                            @csrf
                                            @method('PATCH')
                                            <x-button type="submit" variant="outline" icon="bi-arrow-repeat"
                                                class="kontrak-btn-perpanjang">
                                                Perpanjang
                                            </x-button>
                                        </form>
                                    @else
                                        <span class="kontrak-hint" title="Belum masuk periode perpanjangan">
                                            <i class="bi bi-clock"></i>
                                            Tersedia mulai {{ $tgl($bukaPerpanjangan, 'd M Y') }}
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <x-table.empty-row colspan="7" />
                    @endforelse
                </tbody>
            </x-table>

            <div class="app-table-footer">
                <span>
                    Menampilkan
                    {{ $kontrak->firstItem() ?? 0 }}–{{ $kontrak->lastItem() ?? 0 }}
                    dari
                    {{ $kontrak->total() }}
                    entri
                </span>

                <x-pagination :paginator="$kontrak" />
            </div>
        </div>
    </x-panel>

    @include('kontrak-karyawan.form-create')
    @include('kontrak-karyawan.form-edit')
@endsection
