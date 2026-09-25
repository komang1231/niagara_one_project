@extends('layouts.app')

@section('content')
    <x-page-header eyebrow="Kehadiran" title="Hari Libur"
        description="Hari libur yang telah dihapus. Pulihkan atau hapus permanen di sini." icon="bi-trash-fill">
        <x-slot:badges>
            <x-badge>{{ $hariLiburs->total() }} hari libur</x-badge>
        </x-slot:badges>

        <x-slot:actions>
            <x-button variant="outline" icon="bi-arrow-left" href="{{ route('hari-libur.index') }}">
                Kembali
            </x-button>
        </x-slot:actions>

    </x-page-header>

    <x-panel>
            <x-table>
                <thead>
                    <tr>
                        <th class="app-table__col-no">NO</th>
                        <th>Kode</th>
                        <th>Nama Hari Libur</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th class="app-table__col-actions">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($hariLiburs as $i => $row)
                        <tr>
                            <td class="app-table__col-no">
                                {{ $hariLiburs->firstItem() + $i }}
                            </td>

                            <td class="fw-semibold">
                                {{ $row->kode }}
                            </td>

                            <td>
                                {{ $row->nama }}
                            </td>

                            <td>
                                {{ \Carbon\Carbon::parse($row->tanggal)->translatedFormat('d F') }}
                            </td>

                            <td>
                                <x-badge :variant="$row->status === 'aktif' ? 'success' : 'neutral'">
                                    {{ ucfirst($row->status) }}
                                </x-badge>
                            </td>

                            <td class="app-table__col-actions">
                                <div class="app-table__actions">
                                {{-- Restore --}}
                                <form action="{{ route('hari-libur.restore', $row->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <x-button type="submit" variant="icon-success" icon="bi-arrow-counterclockwise"
                                        onclick="return confirm('Pulihkan hari libur ini?')" />
                                </form>

                                {{-- Hapus permanen --}}
                                <form action="{{ route('hari-libur.force-delete', $row->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <x-button type="submit" variant="icon-danger" icon="bi-trash3-fill"
                                        onclick="return confirm('Hari libur akan dihapus permanen dan tidak bisa dikembalikan lagi. Lanjutkan?')" />
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
                    {{ $hariLiburs->firstItem() ?? 0 }}–{{ $hariLiburs->lastItem() ?? 0 }}
                    dari
                    {{ $hariLiburs->total() }}
                    entri
                </span>

                <x-pagination :paginator="$hariLiburs" />
            </div>
    </x-panel>
@endsection
