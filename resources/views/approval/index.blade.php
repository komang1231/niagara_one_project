{{-- Ganti nama layout kalau @extends di halaman Permintaan beda --}}
@extends('layouts.app')

@section('title', 'Persetujuan')

@section('content')
    @php
        $activeTab = array_key_exists((string) request('tab'), $tabs) ? request('tab') : array_key_first($tabs);
    @endphp

    <div id="approval-root">

        {{-- ===== NAV TAB ===== --}}
        <ul class="nav nav-tabs approval-tabs" role="tablist">
            @foreach ($tabs as $key => $label)
                <li class="nav-item" role="presentation">
                    <button type="button" class="nav-link @if ($activeTab === $key) active @endif"
                        data-approval-tab="{{ $key }}" data-bs-toggle="tab"
                        data-bs-target="#tab-{{ $key }}" role="tab">
                        {{ $label }}
                    </button>
                </li>
            @endforeach
        </ul>

        {{-- ===== ISI TAB (tiap tab = 1 file include) ===== --}}
        <div class="tab-content">
            @foreach ($tabs as $key => $label)
                <div class="tab-pane fade @if ($activeTab === $key) show active @endif" id="tab-{{ $key }}"
                    data-tab="{{ $key }}" role="tabpanel">
                    @include('approval.tabs.' . $key)
                </div>
            @endforeach
        </div>
    </div>

    <script>
        // Simpan tab aktif di URL (?tab=...) biar tidak balik ke tab pertama saat refresh
        document.addEventListener('shown.bs.tab', function(e) {
            var tombol = e.target.closest('[data-approval-tab]');
            if (!tombol) return;

            var url = new URL(window.location.href);
            url.searchParams.set('tab', tombol.dataset.approvalTab);
            window.history.replaceState({}, '', url);
        });

        // Link pagination tiap tab harus membawa ?tab= milik tab-nya sendiri
        // (tiap tab punya halaman sendiri: karyawan_page, cuti_page, dst), jadi pindah halaman tidak loncat ke tab lain
        document.querySelectorAll('#approval-root .tab-pane').forEach(function(pane) {
            pane.querySelectorAll('.app-pagination a[href]').forEach(function(a) {
                if (a.getAttribute('href') === '#') return;
                var u = new URL(a.href);
                u.searchParams.set('tab', pane.dataset.tab);
                a.href = u.toString();
            });
        });

        // Konfirmasi sebelum Setujui / Tolak: pakai popup SweetAlert yang sama dengan popup global (components/popup)
        // (form approve/reject itu POST biasa tanpa method spoofing, jadi tidak ditangkap popup global)
        document.addEventListener('submit', function(e) {
            var form = e.target.closest('[data-approval-confirm]');
            if (!form) return;

            e.preventDefault();

            var tolak = (form.getAttribute('action') || '').includes('/reject');
            var swalBtn = Swal.mixin({ buttonsStyling: false });

            swalBtn.fire({
                title: tolak ? 'Tolak permintaan?' : 'Setujui permintaan?',
                text: form.dataset.approvalConfirm,
                icon: tolak ? 'warning' : 'question',
                showCancelButton: true,
                confirmButtonText: tolak ? 'Ya, tolak!' : 'Ya, setujui!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    confirmButton: 'btn btn-success popup-confirm',
                    cancelButton: 'btn btn-danger popup-cancel'
                }
            }).then(function(result) {
                if (result.isConfirmed) {
                    form.submit(); // submit() bawaan tidak memicu event submit lagi
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swalBtn.fire({
                        title: 'Dibatalkan',
                        text: 'Tidak ada perubahan yang dilakukan.',
                        icon: 'error',
                        confirmButtonText: 'OK',
                        customClass: { confirmButton: 'btn btn-danger popup-ok-danger' }
                    });
                }
            });
        });
    </script>
@endsection