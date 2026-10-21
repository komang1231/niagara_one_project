{{--
    Input khusus KODE (kode departemen, kode section, dst).
    Selalu disabled + berwarna abu-abu, karena kode di-generate sistem
    dan gak boleh diubah user.

    Contoh pakai:
      <x-form.input-code name="kode_preview" label="Kode Section" :value="$previewKode" />
      <x-form.input-code name="kode" label="Kode Section" />            (form edit, value diisi via JS)
      <x-form.input-code name="kode" label="Kode Shift" inline />

    Catatan: input disabled TIDAK ikut ter-submit ke server. Itu memang
    diinginkan, kode dibuat/dipertahankan oleh backend.
--}}
@props([
    'name' => 'kode',
    'label' => 'Kode',
    'value' => null,
    'placeholder' => 'Otomatis',
    'inline' => false, // true = label di kiri, input di kanan
])

<div class="{{ $inline ? 'form-row-inline' : 'mb-3' }}">
    @if ($label)
        <label for="{{ $attributes->get('id', $name) }}"
            class="form-label fw-semibold {{ $inline ? 'form-row-inline__label' : '' }}">
            {{ $label }}
        </label>
    @endif

    <div class="{{ $inline ? 'form-row-inline__control' : '' }}">
        <input type="text" name="{{ $name }}" value="{{ $value }}" placeholder="{{ $placeholder }}" disabled
            {{ $attributes->merge(['id' => $name, 'class' => 'form-control form-control-code']) }}>
    </div>
</div>
