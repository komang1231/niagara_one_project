import $ from 'jquery';
import select2 from 'select2';

window.$ = $;
window.jQuery = $;

select2($);

document.addEventListener('DOMContentLoaded', () => {
    $('select.select2').each(function () {
        const $select = $(this);

        if ($select.hasClass('select2-hidden-accessible')) {
            return;
        }

        const $offcanvas = $select.closest('.offcanvas');

        const placeholder =
            $select.data('placeholder') || 'Pilih';

        const searchPlaceholder =
            $select.data('search-placeholder') || 'Cari...';

        $select.select2({
            width: '100%',
            placeholder,
            allowClear: true,
            minimumResultsForSearch: 0,

            dropdownParent: $offcanvas.length
                ? $offcanvas
                : $(document.body),

            language: {
                noResults: () => 'Tidak ada hasil',
                searching: () => 'Mencari...',
            },
        });

        $select.on('select2:open', function () {
            const $currentSelect = $(this);

            setTimeout(() => {
                const $search = $('.select2-container--open')
                    .find('.select2-search__field');

                if (!$search.length) {
                    return;
                }

                $search
                    .attr(
                        'placeholder',
                        $currentSelect.data('search-placeholder') || 'Cari...'
                    )
                    .prop('disabled', false)
                    .prop('readonly', false)
                    .trigger('focus');
            }, 50);
        });

        $select.on('change', function () {
            if ($(this).val()) {
                $(this)
                    .removeClass('is-invalid')
                    .next('.select2-container')
                    .find('.select2-selection')
                    .removeClass('select2-is-invalid');
            }
        });
    });
});