@extends('layouts.app')

@section('content')
    @php
        // Penyusun baris untuk <x-table.cell-stack>: baris kosong dibuang.
        $stack = fn(...$lines) => array_values(array_filter($lines, 'filled'));

        // Sisa = hak cuti - terpakai. Kalau backend sudah menyediakan $row->sisa (accessor/kolom),
        // nilai itu yang dipakai; kalau belum, dihitung di sini. Perhitungan final ditetapkan backend.
        $hitungSisa = fn($row) => $row->sisa ?? ($row->saldo - $row->terpakai);

        // Warna angka Sisa: habis/melebihi = merah, sisa <= 25% dari hak cuti = oranye, selain itu hijau
        $batasHampirHabis = 0.25;
    @endphp

    <x-page-header eyebrow="Cuti" title="Saldo Cuti" description="Kelola dan pantau saldo cuti karyawan."
        icon="bi-calendar2-check-fill">
        <x-slot:badges>
            <x-badge>{{ $saldoCuti->total() }} saldo cuti</x-badge>
        </x-slot:badges>

        <x-slot:actions>
            <x-button variant="outline" icon="bi-trash" href="{{ route('saldo-cuti.trash') }}">
                Trash
            </x-button>

            <x-button variant="primary" icon="bi-plus-lg" data-bs-toggle="offcanvas" data-bs-target="#offcanvas-saldo-cuti">
                Atur Saldo
            </x-button>
        </x-slot:actions>
    </x-page-header>

    <x-panel>
        <div>
            <x-filter.bar :clearable="['search', 'cuti', 'tahun']" ajax-target="#saldo-cuti-table">
                <x-filter.search placeholder="Cari karyawan atau NIP" />

                <x-filter.multiselect name="cuti" label="Jenis Cuti" :options="$cutiOptions" />

                {{-- Tahun: rentang tahun (dari - sampai). Default = tahun berjalan. --}}
                <x-filter.date-range name="tahun" label="Tahun" type="year" :default-from="now()->year"
                    :default-to="now()->year" />
            </x-filter.bar>
        </div>

        <hr class="app-panel__divider">

        <div id="saldo-cuti-table">
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
                        <th>Status</th>
                        <th class="app-table__col-actions">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($saldoCuti as $i => $row)
                        @php
                            $sisa = $hitungSisa($row);

                            if ($sisa <= 0) {
                                $level = 'danger';
                            } elseif ($row->saldo > 0 && $sisa / $row->saldo <= $batasHampirHabis) {
                                $level = 'warning';
                            } else {
                                $level = 'ok';
                            }

                            // Status baris. Kolom `status` belum ada di tabel -> dianggap aktif sampai backend menambahkannya.
                            $aktif = ($row->status ?? 'aktif') === 'aktif';
                        @endphp

                        <tr>
                            <td class="app-table__col-no">
                                {{ $saldoCuti->firstItem() + $i }}
                            </td>

                            {{-- Karyawan: avatar + nama, NIP, departemen --}}
                            <td>
                                <x-table.cell-stack :avatar="$row->karyawan?->nama" :lines="$stack($row->karyawan?->nama, $row->karyawan?->nip, $row->karyawan?->departemen?->nama)" />
                            </td>

                            {{-- Jenis cuti: nama + kode --}}
                            <td>
                                <x-table.cell-stack :lines="$stack($row->cuti?->nama ?? '-', $row->cuti?->kode)" />
                            </td>

                            <td class="sc-col-num fw-semibold">{{ $row->tahun }}</td>

                            <td class="sc-col-num">{{ $row->saldo }} <span class="sc-unit">hari</span></td>

                            <td class="sc-col-num">{{ $row->terpakai }} <span class="sc-unit">hari</span></td>

                            {{-- Sisa: angka saja (warna mengikuti kondisi) --}}
                            <td class="sc-col-num">
                                <span class="sc-sisa sc-sisa--{{ $level }}">{{ $sisa }}</span>
                            </td>

                            {{-- Status --}}
                            <td>
                                <x-badge :variant="$aktif ? 'success' : 'neutral'" data-status-badge>
                                    {{ $aktif ? 'Aktif' : 'Nonaktif' }}
                                </x-badge>
                            </td>

                            <td class="app-table__col-actions">
                                <div class="app-table__actions">
                                    {{-- Edit --}}
                                    <x-button variant="icon-edit" icon="bi-pencil" title="Edit" data-bs-toggle="offcanvas"
                                        data-bs-target="#offcanvas-saldo-cuti-edit"
                                        data-edit-url="{{ route('saldo-cuti.edit-data', $row->id) }}"
                                        data-update-url="{{ route('saldo-cuti.update', $row->id) }}" />

                                    {{-- Hapus (soft delete) --}}
                                    <form action="{{ route('saldo-cuti.destroy', $row->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <x-button type="submit" variant="icon-danger" icon="bi-trash" title="Hapus" />
                                    </form>

                                    {{-- Aktifkan / nonaktifkan status --}}
                                    <x-table.status-toggle :checked="$aktif" id="status-toggle-{{ $row->id }}"
                                        data-id="{{ $row->id }}"
                                        data-url="{{ route('saldo-cuti.toggle-status', $row->id) }}"
                                        title="{{ $aktif ? 'Nonaktifkan' : 'Aktifkan' }}" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <x-table.empty-row colspan="9" />
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

    @include('saldo-cuti.form-create')
    @include('saldo-cuti.form-edit')

    <script>
        // Toggle status (pola sama dengan menu Departemen). Tabel bisa di-render ulang oleh filter AJAX,
        // makanya memakai event delegation di document.
        (function() {
            if (window.__saldoCutiToggleBound) return;
            window.__saldoCutiToggleBound = true;

            document.addEventListener('change', function(e) {
                const toggle = e.target.closest('.app-table-toggle input[type="checkbox"][data-url]');

                if (!toggle) return;

                const diminta = toggle.checked; // status yang diinginkan user
                toggle.disabled = true;

                fetch(toggle.dataset.url, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                        },
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Gagal mengubah status.');
                        }

                        return response.json();
                    })
                    .then(data => {
                        // Pakai status dari server kalau ada; kalau tidak, ikut pilihan user
                        const aktif = data && data.status ? data.status === 'aktif' : diminta;

                        toggle.checked = aktif;

                        const badge = toggle.closest('tr')?.querySelector('[data-status-badge]');
                        if (badge) {
                            badge.classList.toggle('badge-success', aktif);
                            badge.classList.toggle('badge-neutral', !aktif);
                            badge.textContent = aktif ? 'Aktif' : 'Nonaktif';
                        }
                    })
                    .catch(error => {
                        console.error(error);

                        // Kembalikan switch jika request gagal
                        toggle.checked = !diminta;
                    })
                    .finally(() => {
                        toggle.disabled = false;
                    });
            });
        })();
    </script>
@endsection
