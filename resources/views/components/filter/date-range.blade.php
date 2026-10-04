{{--
    Filter rentang tanggal / bulan / tahun (dari  to  sampai).
    Logika & tampilan kalender ada di resources/js/filter-date-range.js (+ filter.css bagian "DATE RANGE").
    Popup kalender dibuat oleh JS (gaya Traveloka: 2 panel, rentang di-highlight pakai warna accent).

    Prop:
      name         : awalan nama field. Yang terkirim ke server: {name}_from dan {name}_to
      label        : label di atas field
      type         : 'date' (default) | 'month' | 'year'
                     format yang terkirim -> date: 2026-06-08 | month: 2026-06 | year: 2026
                     format yang tampil   -> date: 08 Jun 2026 | month: Jun 2026 | year: 2026
      default-from : nilai awal "dari" kalau form belum pernah di-submit (opsional)
      default-to   : nilai awal "sampai" kalau form belum pernah di-submit (opsional)

    Rentang boleh dikosongkan sebagian (hanya "dari" atau hanya "sampai").
    Contoh:
      <x-filter.date-range name="tahun" label="Tahun" type="year" :default-from="now()->year" :default-to="now()->year" />
      <x-filter.date-range name="periode" label="Periode" type="date" />
--}}
@props([
    'name',
    'label' => 'Date Range',
    'type' => 'date',
    'defaultFrom' => null,
    'defaultTo' => null,
])

@php
    // Sudah pernah di-submit -> pakai isi request (termasuk kosong). Belum -> pakai default.
    $from = request()->has("{$name}_from") ? request("{$name}_from") : $defaultFrom;
    $to = request()->has("{$name}_to") ? request("{$name}_to") : $defaultTo;
@endphp

<div class="app-filter-field app-filter-field--range {{ $type === 'year' ? 'app-filter-field--range-compact' : '' }}"
    data-date-range data-range-type="{{ $type }}" data-range-label="{{ $label }}">
    <label class="app-filter-label">{{ $label }}</label>

    <div class="app-filter-range" data-range-control>
        <button type="button" class="app-filter-range__btn is-empty" data-range-btn="from" aria-haspopup="dialog"
            aria-expanded="false" aria-label="{{ $label }} dari">
            <span data-range-text="from">Dari</span>
        </button>

        <span class="app-filter-range__sep">to</span>

        <button type="button" class="app-filter-range__btn is-empty" data-range-btn="to" aria-haspopup="dialog"
            aria-expanded="false" aria-label="{{ $label }} sampai">
            <span data-range-text="to">Sampai</span>
        </button>

        <i class="bi bi-calendar3 app-filter-range__icon" aria-hidden="true"></i>
    </div>

    {{-- Nilai yang benar-benar dikirim (format ISO), diisi/diubah oleh JS --}}
    <input type="hidden" name="{{ $name }}_from" value="{{ $from }}" data-range-value="from">
    <input type="hidden" name="{{ $name }}_to" value="{{ $to }}" data-range-value="to">
</div>
