@extends('layouts.app')

@section('content')
    <x-page-header eyebrow="Karyawan" title="Surat Peringatan"
        description="Surat peringatan yang telah dihapus. Pulihkan atau hapus permanen di sini." icon="bi-trash-fill">
        <x-slot:badges>
            <x-badge>{{ $suratPeringatans->total() }} surat</x-badge>
        </x-slot:badges>

        <x-slot:actions>
            <x-button variant="outline" icon="bi-arrow-left" href="{{ route('surat-peringatan.index') }}">
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
                    <th>Surat</th>
                    <th>Masa Berlaku</th>
                    <th>Status</th>
                    <th class="app-table__col-actions">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($suratPeringatans as $i => $row)
                    <tr>
                        <td class="app-table__col-no">
                            {{ $suratPeringatans->firstItem() + $i }}
                        </td>

                        <td>
                            <x-table.cell-stack :avatar="$row->karyawan?->nama" :lines="array_values(array_filter([$row->karyawan?->nama, $row->karyawan?->nip], 'filled'))" />
                        </td>

                        <td>
                            <div class="sp-surat">
                                <x-badge class="sp-badge sp-badge--{{ strtolower($row->jenis_surat) }}">{{ $row->jenis_surat }}</x-badge>
                                <span class="sp-surat__kode">{{ $row->kode }}</span>
                            </div>
                        </td>

                        <td>
                            {{ \Carbon\Carbon::parse($row->masa_berlaku)->translatedFormat('d M Y') }}
                        </td>

                        <td>
                            <x-badge class="sp-status" :variant="$row->status === 'aktif' ? 'success' : 'neutral'"
                                icon="bi-circle-fill">
                                {{ ucfirst($row->status) }}
                            </x-badge>
                        </td>

                        <td class="app-table__col-actions">
                            <div class="app-table__actions">
                                {{-- Restore --}}
                                <form action="{{ route('surat-peringatan.restore', $row->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <x-button type="submit" variant="icon-success" icon="bi-arrow-counterclockwise"
                                        title="Pulihkan" />
                                </form>

                                {{-- Hapus permanen --}}
                                <form action="{{ route('surat-peringatan.force-delete', $row->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <x-button type="submit" variant="icon-danger" icon="bi-trash3-fill"
                                        title="Hapus permanen" />
                                </form>
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
                {{ $suratPeringatans->firstItem() ?? 0 }}–{{ $suratPeringatans->lastItem() ?? 0 }}
                dari
                {{ $suratPeringatans->total() }}
                entri
            </span>

            <x-pagination :paginator="$suratPeringatans" />
        </div>
    </x-panel>
@endsection
