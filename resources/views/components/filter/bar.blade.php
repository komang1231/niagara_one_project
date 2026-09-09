{{--
    Wadah filter bar. Isinya <form> + tombol Search (khusus buat trigger
    kolom text search) + link "Clear All" buat reset semua filter.

    Filter dropdown/checkbox (multiselect) itu AUTO-SUBMIT sendiri pas ada
    perubahan (klik checkbox / hapus chip) — logicnya ada di app-filter.js,
    bukan di sini. Tombol "Search" cuma dibutuhin buat kolom text, soalnya
    kalau tiap ketik huruf langsung auto-submit nanti request-nya spam.

    Props:
    - clearable   : nama-nama field yang dianggap "filter aktif", dipakai
                    buat nentuin kapan link "Clear All" muncul.
                    contoh: :clearable="['search', 'status', 'role']"
    - ajax-target : CSS selector container yang mau di-replace pas hasil
                    filter balik (biasanya wrapper tabel), contoh: "#karyawan-table".
                    Kalau prop ini KOSONG, form fallback ke submit biasa
                    (reload full page) — jadi aman dipake tanpa JS juga.
--}}
@props([
    'clearable' => [],
    'ajaxTarget' => null,
])

<form
    method="GET"
    action="{{ request()->url() }}"
    class="app-filter-bar"
    data-filter-form
    @if ($ajaxTarget) data-filter-ajax-target="{{ $ajaxTarget }}" @endif
>
    <div class="app-filter-bar__fields">
        {{ $slot }}
    </div>

    <div class="app-filter-bar__actions">
        {{-- tombol ini juga jadi fallback: kalau JS-nya belum kepasang/gagal load,
             form tetep bisa disubmit normal lewat sini --}}
        <x-button type="submit" variant="search" icon="bi-search">Search</x-button>

        @if (request()->anyFilled($clearable))
            {{-- sengaja dibiarin reload biasa (bukan AJAX), soalnya reset semua
                 checkbox balik ke kondisi default itu lebih ribet kalau di-AJAX-in,
                 dan tombol ini emang jarang dipencet jadi gpp reload sekali --}}
            <a href="{{ request()->url() }}" class="app-filter-clear">
                <i class="bi bi-x-circle"></i> Clear All
            </a>
        @endif
    </div>
</form>