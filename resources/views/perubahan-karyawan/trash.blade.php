@extends('layouts.app')

@section('content')
    @php
        /* TODO BACKEND: kirim $perubahanKaryawans (paginator data yang di-soft delete).
           Key tiap item sama dengan di index: id, karyawan_nama, karyawan_nip, jenis_perubahan,
           status, nomor_sk, tanggal_efektif.
           Selama belum dikirim, halaman memakai DATA CONTOH di bawah (hapus blok ini nanti). */
        if (!isset($perubahanKaryawans)) {
            $mockTrash = [
                ['id' => 7, 'karyawan_nama' => 'Ni Luh Ayu', 'karyawan_nip' => 'NIP-2303', 'jenis_perubahan' => 'rotasi', 'status' => 'nonaktif', 'nomor_sk' => 'SK/HRD/007/VI/2026', 'tanggal_efektif' => '2026-06-01'],
                ['id' => 8, 'karyawan_nama' => 'Kadek Wirawan', 'karyawan_nip' => 'NIP-2305', 'jenis_perubahan' => 'promosi', 'status' => 'aktif', 'nomor_sk' => 'SK/HRD/008/V/2026', 'tanggal_efektif' => '2026-05-10'],
            ];
            $perubahanKaryawans = new \Illuminate\Pagination\LengthAwarePaginator(
                $mockTrash,
                count($mockTrash),
                10,
                1,
                ['path' => request()->url()],
            );
        }

        // Badge jenis perubahan: [variant badge, icon] (sama dengan index)
        $jenisBadge = [
            'promosi' => ['success', 'bi-graph-up-arrow'],
            'demosi' => ['warning', 'bi-graph-down-arrow'],
            'rotasi' => ['neutral', 'bi-arrow-repeat'],
            'mutasi' => ['neutral', 'bi-geo-alt'],
        ];
    @endphp

    <x-page-header eyebrow="Karyawan" title="Perubahan Karyawan"
        description="Perubahan karyawan yang telah dihapus. Pulihkan atau hapus permanen di sini." icon="bi-trash-fill">
        <x-slot:badges>
            <x-badge>{{ $perubahanKaryawans->total() }} perubahan</x-badge>
        </x-slot:badges>

        <x-slot:actions>
            <x-button variant="outline" icon="bi-arrow-left" href="{{ route('perubahan-karyawan.index') }}">
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
                    <th>Jenis Perubahan</th>
                    <th>Tanggal Efektif</th>
                    <th>Nomor SK</th>
                    <th>Status</th>
                    <th class="app-table__col-actions">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($perubahanKaryawans as $i => $row)
                    @php
                        $jenis = data_get($row, 'jenis_perubahan');
                        [$badgeVariant, $badgeIcon] = $jenisBadge[$jenis] ?? ['neutral', 'bi-circle'];
                        $aktif = data_get($row, 'status') === 'aktif';
                    @endphp

                    <tr>
                        <td class="app-table__col-no">
                            {{ $perubahanKaryawans->firstItem() + $i }}
                        </td>

                        <td>
                            <x-table.cell-stack :avatar="data_get($row, 'karyawan_nama')" :lines="array_values(array_filter([data_get($row, 'karyawan_nama'), data_get($row, 'karyawan_nip')], 'filled'))" />
                        </td>

                        <td>
                            <x-badge :variant="$badgeVariant" :icon="$badgeIcon">
                                {{ ucfirst($jenis) }}
                            </x-badge>
                        </td>

                        <td>
                            {{ \Carbon\Carbon::parse(data_get($row, 'tanggal_efektif'))->locale('id')->translatedFormat('d M Y') }}
                        </td>

                        <td class="fw-semibold">
                            {{ data_get($row, 'nomor_sk') }}
                        </td>

                        <td>
                            <x-badge :variant="$aktif ? 'success' : 'neutral'">
                                {{ $aktif ? 'Aktif' : 'Nonaktif' }}
                            </x-badge>
                        </td>

                        <td class="app-table__col-actions">
                            <div class="app-table__actions">
                                {{-- Restore. TODO BACKEND: route PATCH perubahan-karyawan/{id}/restore --}}
                                <form action="{{ url('perubahan-karyawan/' . data_get($row, 'id') . '/restore') }}"
                                    method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <x-button type="submit" variant="icon-success" icon="bi-arrow-counterclockwise"
                                        title="Pulihkan" />
                                </form>

                                {{-- Hapus permanen. TODO BACKEND: route DELETE perubahan-karyawan/{id}/force-delete --}}
                                <form action="{{ url('perubahan-karyawan/' . data_get($row, 'id') . '/force-delete') }}"
                                    method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <x-button type="submit" variant="icon-danger" icon="bi-trash3-fill"
                                        title="Hapus permanen" />
                                </form>
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
                {{ $perubahanKaryawans->firstItem() ?? 0 }}–{{ $perubahanKaryawans->lastItem() ?? 0 }}
                dari
                {{ $perubahanKaryawans->total() }}
                entri
            </span>

            <x-pagination :paginator="$perubahanKaryawans" />
        </div>
    </x-panel>
@endsection