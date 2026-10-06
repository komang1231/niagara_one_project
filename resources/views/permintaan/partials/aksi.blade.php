{{-- Butuh: $row, $slug (contoh: 'permintaan-cuti'), $label (contoh: 'Permintaan Cuti') --}}
@php
    // Edit & Batalkan hanya boleh saat masih Menunggu
    $bisaDiubah = !$row->trashed() && !$row->approved_at && !$row->rejected_at;
@endphp

<div class="app-table__actions">
    @if ($bisaDiubah)
        {{-- Edit (dibaca handler global offcanvas-edit.js) --}}
        <x-button variant="icon-edit" icon="bi-pencil" title="Edit" data-bs-toggle="offcanvas"
            data-bs-target="#offcanvas-{{ $slug }}-edit"
            data-edit-url="{{ route($slug . '.edit-data', $row->id) }}"
            data-update-url="{{ route($slug . '.update', $row->id) }}" />

        {{-- Batalkan (buka modal konfirmasi bersama) --}}
        <x-button variant="icon-danger" icon="bi-x-circle" title="Batalkan" data-delete-url="{{ route($slug . '.destroy', $row->id) }}"
            data-delete-label="{{ $label }} {{ $row->kode }}" />
        {{-- <x-button variant="icon-danger" icon="bi-x-circle" title="Batalkan" 
            data-bs-toggle="modal" data-bs-target="#modal-batalkan-permintaan"
            data-delete-url="{{ route($slug . '.destroy', $row->id) }}"
            data-delete-label="{{ $label }} {{ $row->kode }}" /> --}}
        <form action="{{ route($slug . '.destroy', $row->id) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')

            <x-button variant="icon-danger" icon="bi-x-circle" title="Batalkan" type="submit" />
        </form>
    @else
        <span class="text-muted">-</span>
    @endif
</div>
