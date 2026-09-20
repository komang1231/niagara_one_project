@props(['paginator'])

@if ($paginator->hasPages())
    <nav aria-label="Pagination" class="app-pagination">
        <ul class="app-pagination__list">
            {{-- Sebelumnya --}}
            <li class="app-pagination__item {{ $paginator->onFirstPage() ? 'is-disabled' : '' }}">
                <a class="app-pagination__link app-pagination__link--nav"
                   href="{{ $paginator->onFirstPage() ? '#' : $paginator->previousPageUrl() }}">
                    <i class="bi bi-chevron-left"></i>
                </a>
            </li>

            {{-- Nomor halaman --}}
            @foreach (range(1, $paginator->lastPage()) as $page)
                <li class="app-pagination__item">
                    <a class="app-pagination__link {{ $paginator->currentPage() == $page ? 'is-active' : '' }}"
                       href="{{ $paginator->url($page) }}">
                        {{ $page }}
                    </a>
                </li>
            @endforeach

            {{-- Berikutnya --}}
            <li class="app-pagination__item {{ $paginator->hasMorePages() ? '' : 'is-disabled' }}">
                <a class="app-pagination__link app-pagination__link--nav"
                   href="{{ $paginator->hasMorePages() ? $paginator->nextPageUrl() : '#' }}">
                    <i class="bi bi-chevron-right"></i>
                </a>
            </li>
        </ul>
    </nav>
@endif