document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-color-picker]').forEach(setupColorPicker);
});

function setupColorPicker(wrapper) {
    const trigger = wrapper.querySelector('[data-color-trigger]');
    const dropdown = wrapper.querySelector('[data-color-dropdown]');
    const dot = wrapper.querySelector('[data-color-dot]');
    const hiddenInput = wrapper.querySelector('[data-color-value]');

    function refreshDot() {
        const val = hiddenInput.value;
        dot.dataset.color = val;
        dropdown.querySelectorAll('[data-color-option]').forEach(btn => {
            btn.classList.toggle('is-active', btn.dataset.colorOption === val);
        });
    }

    trigger.addEventListener('click', function (e) {
        e.stopPropagation();
        wrapper.classList.toggle('is-open');
    });

    dropdown.querySelectorAll('[data-color-option]').forEach(btn => {
        btn.addEventListener('click', function () {
            hiddenInput.value = btn.dataset.colorOption;
            hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));
            wrapper.classList.remove('is-open');
        });
    });

    hiddenInput.addEventListener('change', refreshDot);

    document.addEventListener('click', function (e) {
        if (!wrapper.contains(e.target)) wrapper.classList.remove('is-open');
    });

    refreshDot();
}