@props([
'name',
'label' => null,
'accept' => null,
'maxFiles' => 10,
'maxSize' => 20,
'maxTotalSize' => 100,
'multiple' => false,
'required' => false,
'hint' => null,
'id' => null,
])

@php
$inputId = $id ?? str_replace(['[]', '[', ']'], '', $name);

$isMultiple = $multiple || str_ends_with($name, '[]');

$defaultHint = $isMultiple
? 'Maks. ' . $maxFiles . ' file · ' . $maxSize . ' MB/file · Maks. ' . $maxTotalSize . ' MB total'
: 'Maks. ' . $maxSize . ' MB';

$fileHint = $hint ?? $defaultHint;
@endphp

<div
    class="file-upload mb-3"
    data-file-upload
    data-max-files="{{ $maxFiles }}"
    data-max-size="{{ $maxSize }}"
    data-max-total-size="{{ $maxTotalSize }}"
    data-multiple="{{ $isMultiple ? 'true' : 'false' }}">
    @if ($label)
    <label
        for="{{ $inputId }}"
        class="form-label fw-semibold">
        {{ $label }}

        @if ($required)
        <span class="text-danger">*</span>
        @endif
    </label>
    @endif

    <div
        class="file-upload__dropzone"
        data-file-dropzone
        tabindex="0"
        role="button"
        aria-label="Upload {{ $label ?? 'file' }}">
        <input
            type="file"
            id="{{ $inputId }}"
            name="{{ $name }}"
            class="file-upload__input"
            data-file-input
            @if ($accept)
            accept="{{ $accept }}"
            @endif
            @if ($isMultiple)
            multiple
            @endif>

        <div class="file-upload__empty" data-file-empty>
            <div class="file-upload__icon">
                <i class="bi bi-cloud-arrow-up"></i>
            </div>

            <div class="file-upload__title">
                Tarik file ke sini atau <span>klik untuk memilih</span>
            </div>

            <div class="file-upload__hint">
                {{ $fileHint }}
            </div>
        </div>
    </div>

    <div
        class="file-upload__files d-none"
        data-file-list></div>

    <div
        class="file-upload__error d-none"
        data-file-error></div>
</div>