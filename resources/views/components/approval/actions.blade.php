{{--
    Komponen aksi Persetujuan.
    Pemakaian: <x-approval.actions :row="$row" slug="cuti" label="Permintaan Cuti" />
    slug = karyawan | cuti | lembur | resign | tukar-shift
    (dipakai untuk route approval.{slug}.approve / approval.{slug}.reject)

    Aturan:
    - Menunggu    -> tombol Setujui + Tolak
    - Disetujui / Ditolak / Dibatalkan -> tanpa aksi (cuma strip "-")
--}}
@props(['row', 'slug', 'label' => 'Permintaan'])

@php
    // Menunggu = belum disetujui, belum ditolak, belum dibatalkan (soft delete)
    $menunggu = !$row->trashed() && !$row->approved_at && !$row->rejected_at;
@endphp

<div class="app-table__actions">
    @if ($menunggu)
        {{-- Setujui --}}
        <form method="POST" action="{{ route('approval.' . $slug . '.approve', $row->id) }}"
            data-approval-confirm="Setujui {{ $label }} {{ $row->kode }}?">
            @csrf
            <x-button type="submit" variant="icon-success" icon="bi-check-lg" title="Setujui" />
        </form>

        {{-- Tolak --}}
        <form method="POST" action="{{ route('approval.' . $slug . '.reject', $row->id) }}"
            data-approval-confirm="Tolak {{ $label }} {{ $row->kode }}?">
            @csrf
            <x-button type="submit" variant="icon-danger" icon="bi-x-lg" title="Tolak" />
        </form>
    @else
        <span class="text-muted">-</span>
    @endif
</div>
