@props(['name', 'value' => '', 'label' => null, 'placeholder' => 'Tulis di sini...'])

<div data-rich-text-editor class="rte mb-3">
    @if($label)
        <label class="form-label fw-semibold">{{ $label }}</label>
    @endif

    <input type="hidden" name="{{ $name }}" data-rich-text-input value="{{ $value }}">

    <div class="rte-toolbar" data-rich-text-toolbar>
        <select class="rte-heading-select" data-rte-action="heading">
            <option value="paragraph">Paragraf</option>
            <option value="1">Heading 1</option>
            <option value="2">Heading 2</option>
            <option value="3">Heading 3</option>
        </select>

        <span class="rte-divider"></span>

        <button type="button" class="rte-btn" data-rte-action="bold" title="Bold">
            <i class="bi bi-type-bold"></i>
        </button>
        <button type="button" class="rte-btn" data-rte-action="italic" title="Italic">
            <i class="bi bi-type-italic"></i>
        </button>
        <button type="button" class="rte-btn" data-rte-action="underline" title="Underline">
            <i class="bi bi-type-underline"></i>
        </button>
        <button type="button" class="rte-btn" data-rte-action="strike" title="Strikethrough">
            <i class="bi bi-type-strikethrough"></i>
        </button>

        <span class="rte-divider"></span>

        <button type="button" class="rte-btn" data-rte-action="bulletList" title="Bullet List">
            <i class="bi bi-list-ul"></i>
        </button>
        <button type="button" class="rte-btn" data-rte-action="orderedList" title="Numbered List">
            <i class="bi bi-list-ol"></i>
        </button>
        <button type="button" class="rte-btn" data-rte-action="blockquote" title="Quote">
            <i class="bi bi-quote"></i>
        </button>

        <span class="rte-divider"></span>

        <button type="button" class="rte-btn" data-rte-action="undo" title="Undo">
            <i class="bi bi-arrow-counterclockwise"></i>
        </button>
        <button type="button" class="rte-btn" data-rte-action="redo" title="Redo">
            <i class="bi bi-arrow-clockwise"></i>
        </button>
    </div>

    <div class="rte-container" data-rich-text-container data-placeholder="{{ $placeholder }}"></div>
</div>