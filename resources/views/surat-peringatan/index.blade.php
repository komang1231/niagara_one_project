@extends('layouts.app')

@section('content')
    @php
        // Penyusun baris untuk <x-table.cell-stack>: baris kosong dibuang.
        $stack = fn(...$lines) => array_values(array_filter($lines, 'filled'));

        // Berapa hari sebelum berakhir baris masa berlaku diberi peringatan kuning.
        $batasPeringatan = 14;

        /**
         * Teks bantu masa berlaku. Murni tampilan, dihitung dari tanggal hari ini
         * (tidak mengubah struktur database).
         * Return: [tanggal tampil, teks bantu, level (ok|warning|danger), icon]
         */
        $infoMasaBerlaku = function ($tanggal) use ($batasPeringatan) {
            $akhir = \Carbon\Carbon::parse($tanggal)->startOfDay();
            $sisa = (int) \Carbon\Carbon::today()->diffInDays($akhir, false); // negatif = sudah lewat
            $tampil = $akhir->translatedFormat('d M Y');

            if ($sisa < 0) {
                return [$tampil, 'Sudah berakhir', 'danger', 'bi-exclamation-triangle-fill'];
            }
            if ($sisa === 0) {
                return [$tampil, 'Berakhir hari ini', 'warning', 'bi-exclamation-triangle-fill'];
            }
            if ($sisa <= $batasPeringatan) {
                return [$tampil, "Berakhir {$sisa} hari lagi", 'warning', 'bi-exclamation-triangle-fill'];
            }

            return [$tampil, "Aktif {$sisa} hari lagi", 'ok', null];
        };
    @endphp

    <x-page-header eyebrow="Karyawan" title="Surat Peringatan"
        description="Kelola surat peringatan karyawan dan masa berlaku surat." icon="bi-file-earmark-text-fill">
        <x-slot:badges>
            <x-badge>{{ $suratPeringatan->total() }} surat</x-badge>
        </x-slot:badges>

        <x-slot:actions>
            <x-button variant="outline" icon="bi-trash" href="{{ route('surat-peringatan.trash') }}">
                Trash
            </x-button>

            <x-button variant="primary" icon="bi-plus-lg" data-bs-toggle="offcanvas" data-bs-target="#offcanvas-sp">
                Buat Surat Peringatan
            </x-button>
        </x-slot:actions>
    </x-page-header>

    <x-panel>
        <div>
            <x-filter.bar :clearable="['search', 'jenis', 'status']" ajax-target="#surat-peringatan-table">
                <x-filter.search placeholder="Cari nama karyawan, NIP, atau kode surat" />

                <x-filter.multiselect name="jenis" label="Jenis SP" :options="$jenisOptions" />

                <x-filter.multiselect name="status" label="Status" :options="$statusOptions" />
            </x-filter.bar>
        </div>

        <hr class="app-panel__divider">

        <div id="surat-peringatan-table">
            <x-table>
                <thead>
                    <tr>
                        <th class="app-table__col-no">NO</th>
                        <th>Karyawan</th>
                        <th>Surat</th>
                        <th>Masa Berlaku</th>
                        <th>Status</th>
                        <th class="app-table__col-actions">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($suratPeringatan as $i => $row)
                        @php
                            [$tglTampil, $teksBantu, $level, $iconBantu] = $infoMasaBerlaku($row->masa_berlaku);
                            $jenis = strtolower($row->jenis_surat); // sp1 | sp2 | sp3
                        @endphp

                        <tr>
                            <td class="app-table__col-no">
                                {{ $suratPeringatan->firstItem() + $i }}
                            </td>

                            {{-- Karyawan: avatar + nama, NIP, jabatan · departemen --}}
                            <td>
                                <x-table.cell-stack :avatar="$row->karyawan?->nama" :lines="$stack(
                                    $row->karyawan?->nama,
                                    $row->karyawan?->nip,
                                    collect([$row->karyawan?->jobPosition?->nama, $row->karyawan?->departemen?->nama])->filter()->implode(' · '),
                                )" />
                            </td>

                            {{-- Surat: badge jenis, kode, file --}}
                            <td>
                                <div class="sp-surat">
                                    <x-badge class="sp-badge sp-badge--{{ $jenis }}">{{ $row->jenis_surat }}</x-badge>
                                    <span class="sp-surat__kode">{{ $row->kode }}</span>
                                    @if ($row->file)
                                        <a href="{{ $row->file_url ?? '#' }}" target="_blank" rel="noopener"
                                            class="sp-surat__file" data-sp-file title="Buka {{ basename($row->file) }}">
                                            <i class="bi bi-paperclip"></i>{{ basename($row->file) }}
                                        </a>
                                    @endif
                                </div>
                            </td>

                            {{-- Masa berlaku: tanggal + teks bantu --}}
                            <td>
                                <div class="sp-expiry sp-expiry--{{ $level }}">
                                    <span class="sp-expiry__date">{{ $tglTampil }}</span>
                                    <span class="sp-expiry__hint">
                                        @if ($iconBantu)
                                            <i class="bi {{ $iconBantu }}"></i>
                                        @endif
                                        {{ $teksBantu }}
                                    </span>
                                </div>
                            </td>

                            <td>
                                <x-badge class="sp-status" :variant="$row->status === 'aktif' ? 'success' : 'neutral'"
                                    icon="bi-circle-fill">
                                    {{ ucfirst($row->status) }}
                                </x-badge>
                            </td>

                            <td class="app-table__col-actions">
                                <div class="app-table__actions">
                                    {{-- Edit: offcanvas terbuka dalam mode lihat (field terkunci), tombol Edit ada di footer --}}
                                    <x-button variant="icon-edit" icon="bi-pencil" title="Lihat / edit"
                                        data-bs-toggle="offcanvas" data-bs-target="#offcanvas-sp-edit" data-sp-edit
                                        data-edit-url="{{ route('surat-peringatan.edit-data', $row->id) }}"
                                        data-update-url="{{ route('surat-peringatan.update', $row->id) }}" />

                                    {{-- Hapus (soft delete) --}}
                                    <form action="{{ route('surat-peringatan.destroy', $row->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <x-button type="submit" variant="icon-danger" icon="bi-trash" title="Hapus" />
                                    </form>

                                    <x-table.status-toggle :checked="$row->status === 'aktif'"
                                        id="status-toggle-{{ $row->id }}" data-id="{{ $row->id }}" />
                                </div>
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
                    {{ $suratPeringatan->firstItem() ?? 0 }}–{{ $suratPeringatan->lastItem() ?? 0 }}
                    dari
                    {{ $suratPeringatan->total() }}
                    entri
                </span>

                <x-pagination :paginator="$suratPeringatan" />
            </div>
        </div>
    </x-panel>

    @include('surat-peringatan.form-create')
    @include('surat-peringatan.form-edit')

    <script>
        document.addEventListener('change', function(e) {
            const toggle = e.target.closest('.app-table-toggle input[type="checkbox"]');

            if (!toggle) return;

            const id = toggle.dataset.id;

            fetch(`/surat-peringatan/${id}/toggle-status`, {
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
                .then(() => window.location.reload())
                .catch(error => {
                    console.error(error);

                    // Kembalikan switch jika request gagal
                    toggle.checked = !toggle.checked;
                });
        });
    </script>
@endsection
