@extends('layouts.app')

@section('content')

    <x-page-header
        eyebrow="Rekrutmen"
        title="Trash Rekrutmen"
        description="Kelola data kandidat yang telah dipindahkan ke trash."
        icon="bi-trash"
    >
        <x-slot:actions>

            <x-button
                variant="outline"
                icon="bi-arrow-left"
                href="{{ route('rekrutmen.index') }}"
            >
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
                    <th>Kandidat</th>
                    <th>Tahap Rekrutmen</th>
                    <th>Dihapus</th>
                    <th class="app-table__col-actions">Aksi</th>
                </tr>
            </thead>


            <tbody>

                @forelse ($rekrutmens as $i => $row)

                    <tr>

                        <td class="app-table__col-no">
                            {{ $rekrutmens->firstItem() + $i }}
                        </td>

                        <td>{{ $row->kode }}</td>

                        <td>
                            <x-table.cell-stack
                                :lines="[$row->nama, $row->email]"
                            />
                        </td>

                        <td>
                            <x-badge variant="neutral">
                                {{ ucfirst($row->status_rekrutmen) }}
                            </x-badge>
                        </td>

                        <td>
                            {{ $row->deleted_at?->format('d M Y H:i') }}
                        </td>

                        <td class="app-table__col-actions">

                            <div class="app-table__actions">

                                {{-- Route restore = PATCH, jadi wajib @method('PATCH') --}}
                                <form
                                    action="{{ route('rekrutmen.restore', $row->id) }}"
                                    method="POST"
                                >
                                    @csrf
                                    @method('PATCH')

                                    <x-button
                                        type="submit"
                                        variant="icon"
                                        icon="bi-arrow-counterclockwise"
                                        title="Pulihkan"
                                        onclick="return confirm('Pulihkan kandidat ini?')"
                                    />
                                </form>


                                <form
                                    action="{{ route('rekrutmen.force-delete', $row->id) }}"
                                    method="POST"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <x-button
                                        type="submit"
                                        variant="icon-danger"
                                        icon="bi-trash"
                                        title="Hapus Permanen"
                                        onclick="return confirm('Hapus kandidat secara permanen? Data tidak dapat dipulihkan.')"
                                    />
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
                {{ $rekrutmens->firstItem() ?? 0 }}–{{ $rekrutmens->lastItem() ?? 0 }}
                dari
                {{ $rekrutmens->total() }}
                entri
            </span>

            <x-pagination :paginator="$rekrutmens" />

        </div>

    </x-panel>

@endsection