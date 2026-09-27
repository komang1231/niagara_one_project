@extends('layouts.app')

@section('content')
    @php
        $stack = fn(...$lines) => array_values(array_filter($lines, 'filled'));
    @endphp

    <x-page-header eyebrow="Kehadiran" title="Data Shift" description="Kelola jam kerja shift karyawan." icon="bi-clock-fill">
        <x-slot:badges>
            <x-badge>{{ $shift->total() }} shift</x-badge>
        </x-slot:badges>

        <x-slot:actions>
            <x-button variant="outline" icon="bi-trash" href="{{ route('shift.trash') }}">
                Trash
            </x-button>

            <x-button variant="primary" icon="bi-plus-lg" data-bs-toggle="offcanvas" data-bs-target="#offcanvas-tambah-shift">
                Tambah Shift
            </x-button>
        </x-slot:actions>
    </x-page-header>

    <x-panel>
        <div>
            <x-filter.bar :clearable="['search', 'status']" ajax-target="#shift-table">
                <x-filter.search placeholder="Cari nama atau kode shift" />

                <x-filter.multiselect name="status" label="Status" :options="$statusOptions" />
            </x-filter.bar>
        </div>

        <hr class="app-panel__divider">

        <div id="shift-table">
            <x-table>
                <thead>
                    <tr>
                        <th class="app-table__col-no">NO</th>
                        <th>Kode</th>
                        <th>Nama Shift</th>
                        <th>Jam Kerja</th>
                        <th>Status</th>
                        <th class="app-table__col-actions">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($shift as $i => $row)
                        <tr>
                            <td class="app-table__col-no">{{ $shift->firstItem() + $i }}</td>

                            <td>{{ $row->kode }}</td>

                            {{-- Nama shift jadi badge berwarna sesuai preset --}}
                            <td>
                                <span class="shift-badge"
                                    style="background: var(--shift-{{ $row->warna }}-bg); color: var(--shift-{{ $row->warna }}-text); border-color: var(--shift-{{ $row->warna }}-border);">
                                    {{ $row->nama }}
                                </span>
                            </td>

                            {{-- Baris 1: jam masuk-pulang (+ tanda lintas hari). Baris 2: istirahat & toleransi --}}
                            <td>
                                <x-table.cell-stack :lines="$stack(
                                    \Carbon\Carbon::parse($row->jam_masuk)->format('H:i') .
                                        ' - ' .
                                        \Carbon\Carbon::parse($row->jam_pulang)->format('H:i') .
                                        ($row->lintas_hari ? ' (Lintas hari)' : ''),
                                    'Istirahat ' . $row->istirahat_menit . ' menit · Toleransi ' . $row->toleransi_keterlambatan . ' menit',
                                )" />
                            </td>

                            <td>
                                <x-badge :variant="$row->status === 'aktif' ? 'success' : 'neutral'">
                                    {{ ucfirst($row->status) }}
                                </x-badge>
                            </td>

                            <td class="app-table__col-actions">
                                <div class="app-table__actions">
                                    <x-button variant="icon-edit" icon="bi-pencil" title="Edit" data-bs-toggle="offcanvas"
                                        data-bs-target="#offcanvas-shift-edit"
                                        data-edit-url="{{ route('shift.edit-data', $row->id) }}"
                                        data-update-url="{{ route('shift.update', $row->id) }}" />

                                    <form action="{{ route('shift.destroy', $row->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <x-button type="submit" variant="icon-danger" icon="bi-trash" title="Hapus"
                                            onclick="return confirm('Hapus shift ini?')" />
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
                    {{ $shift->firstItem() ?? 0 }}–{{ $shift->lastItem() ?? 0 }}
                    dari
                    {{ $shift->total() }}
                    entri
                </span>

                <x-pagination :paginator="$shift" />
            </div>
        </div>
    </x-panel>

    @includeIf('shift.form-create')
    @includeIf('shift.form-edit')

    <script>
        document.addEventListener('change', function (e) {
            const toggle = e.target.closest('.app-table-toggle input[type="checkbox"]');
            if (!toggle) return;

            const id = toggle.dataset.id;

            fetch(`/shift/${id}/toggle-status`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                })
                .then(response => {
                    if (!response.ok) throw new Error('Gagal mengubah status.');
                    return response.json();
                })
                .then(() => window.location.reload())
                .catch(error => {
                    console.error(error);
                    toggle.checked = !toggle.checked;
                });
        });
    </script>
@endsection