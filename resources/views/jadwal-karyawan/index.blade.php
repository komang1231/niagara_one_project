@extends('layouts.app')

@section('content')
    @if (session('error'))
        <script>
            alert(@json(session('error')));
        </script>
    @endif

    <x-page-header eyebrow="Kehadiran" title="Jadwal Karyawan"
        description="Atur dan pantau jadwal shift karyawan per minggu." icon="bi-calendar-week-fill">
        <x-slot:badges>
            <x-badge>{{ $karyawan->count() }} karyawan</x-badge>
        </x-slot:badges>

        <x-slot:actions>
            <x-button variant="primary" icon="bi-plus-lg" id="btn-tambah-jadwal" data-bs-toggle="offcanvas"
                data-bs-target="#offcanvas-tambah-jadwal">
                Tambah Jadwal
            </x-button>
        </x-slot:actions>
    </x-page-header>

    <x-panel>
        {{-- ===================== FILTER ===================== --}}
        <div>
            <x-filter.bar>
                <x-filter.search placeholder="Cari nama atau NIP karyawan" />

                <x-filter.multiselect name="departemen" label="Departemen" :options="$departemenOptions" />

                {{-- biar pindah minggu / mode tampilan tidak hilang saat filter di-submit --}}
                <input type="hidden" name="tanggal" value="{{ $mulai->toDateString() }}">
                <input type="hidden" name="rentang" value="{{ $rentang }}">
            </x-filter.bar>
        </div>

        <hr class="app-panel__divider">

        {{-- ===================== TOOLBAR: navigasi minggu + mode tampilan ===================== --}}
        <div class="jadwal-toolbar">
            <div class="jadwal-toolbar__nav">
                <a href="{{ $nav['prev'] }}" class="jadwal-nav-btn" aria-label="Periode sebelumnya">
                    <i class="bi bi-chevron-left"></i>
                </a>
                <a href="{{ $nav['next'] }}" class="jadwal-nav-btn" aria-label="Periode berikutnya">
                    <i class="bi bi-chevron-right"></i>
                </a>

                <h2 class="jadwal-toolbar__title">{{ $periode }}</h2>

                <x-button variant="outline" href="{{ $nav['today'] }}">Hari ini</x-button>
            </div>

            <div class="jadwal-segmented" role="group" aria-label="Rentang tampilan">
                <a href="{{ $nav['minggu1'] }}" class="{{ $rentang === 1 ? 'is-active' : '' }}">1 Minggu</a>
                <a href="{{ $nav['minggu2'] }}" class="{{ $rentang === 2 ? 'is-active' : '' }}">2 Minggu</a>
            </div>
        </div>

        {{-- ===================== LEGEND + ringkasan garis waktu ===================== --}}
        @php
            // Legend = shift yang BENAR-BENAR dipakai di jadwal periode ini (bukan hard code).
            // Jenis shift bertambah -> legend ikut bertambah, dan bisa di-scroll horizontal.
            $shiftTerpakai = collect();
            foreach ($jadwal as $daftarChip) {
                foreach ($daftarChip as $c) {
                    $shiftTerpakai->put($c['nama'] . '|' . $c['masuk'] . '|' . $c['pulang'], $c);
                }
            }
            $shiftTerpakai = $shiftTerpakai->sortBy('masuk')->values();
        @endphp

        <div class="jadwal-legend">
            <div class="jadwal-legend__items">
                <span class="jadwal-legend__title">Shift</span>

                <button type="button" class="jadwal-legend__nav" data-legend-prev hidden aria-label="Geser ke kiri">
                    <i class="bi bi-chevron-left"></i>
                </button>

                <div class="jadwal-legend__scroll" data-legend-scroll>
                    <div class="jadwal-legend__track">
                        @forelse ($shiftTerpakai as $c)
                            <span class="jadwal-legend__item"
                                style="--c-bg: var(--shift-{{ $c['warna'] }}-bg); --c-text: var(--shift-{{ $c['warna'] }}-text); --c-border: var(--shift-{{ $c['warna'] }}-border);">
                                <span class="jadwal-legend__dot"></span>
                                {{ $c['nama'] }}
                                <small>{{ $c['masuk'] }} - {{ $c['pulang'] }}</small>
                                @if ($c['lintas'])
                                    <i class="bi bi-moon-stars-fill" title="Lintas hari"></i>
                                @endif
                            </span>
                        @empty
                            <span class="jadwal-legend__empty">Belum ada jadwal di periode ini</span>
                        @endforelse
                    </div>
                </div>

                <button type="button" class="jadwal-legend__nav" data-legend-next hidden aria-label="Geser ke kanan">
                    <i class="bi bi-chevron-right"></i>
                </button>

                {{-- Penanda tetap (tidak ikut scroll) --}}
                <div class="jadwal-legend__fixed">
                    <span class="jadwal-legend__item jadwal-legend__item--lintas">
                        <i class="bi bi-moon-stars-fill"></i>
                        Lintas hari
                    </span>

                    <span class="jadwal-legend__item jadwal-legend__item--sunday">
                        <span class="jadwal-legend__dot"></span>
                        Hari Minggu
                    </span>
                </div>
            </div>

            <div class="jadwal-live" data-timeline-summary aria-live="polite"></div>
        </div>

        {{-- ===================== KALENDER ===================== --}}
        @php
            // tanggal "kemarin" tiap kolom -> buat nampilin lanjutan shift lintas hari
            $kemarin = $hari->mapWithKeys(fn($h) => [$h['iso'] => \Carbon\Carbon::parse($h['iso'])->subDay()->toDateString()]);
        @endphp

        <div class="jadwal-scroller" data-jadwal-scroller>
            <div class="jadwal-grid {{ $rentang === 2 ? 'jadwal-grid--2w' : '' }}" style="--days: {{ $hari->count() }};"
                data-jadwal-grid data-start="{{ $mulai->toDateString() }}" data-days="{{ $hari->count() }}"
                data-initial="{{ $kursorAwal->format('Y-m-d\TH:i') }}">

                {{-- ---------- Header tanggal (sticky atas) ---------- --}}
                <div class="jadwal-row jadwal-row--head">
                    <div class="jadwal-emp jadwal-emp--head">
                        Karyawan
                        <span class="jadwal-count">{{ $karyawan->count() }}</span>
                    </div>

                    @foreach ($hari as $i => $h)
                        <div class="jadwal-day-head @if ($h['isMinggu']) is-sunday @endif @if ($h['isHariIni']) is-today @endif"
                            data-day-head>
                            <span class="jadwal-day-head__num">{{ $h['tanggal'] }}</span>
                            <span class="jadwal-day-head__name">{{ $h['nama'] }}</span>
                            @if ($i === 0 || $h['tanggal'] === 1)
                                <span class="jadwal-day-head__month">{{ $h['bulan'] }}</span>
                            @endif
                        </div>
                    @endforeach
                </div>

                {{-- ---------- Baris karyawan, dikelompokkan per departemen ---------- --}}
                @forelse ($kelompok as $namaDepartemen => $anggota)
                    <div class="jadwal-group">
                        <span class="jadwal-group__label">
                            {{ $namaDepartemen }}
                            <span class="jadwal-count">{{ $anggota->count() }}</span>
                        </span>
                    </div>

                    @foreach ($anggota as $k)
                        @php
                            $inisial = \Illuminate\Support\Str::of($k->nama)
                                ->explode(' ')
                                ->filter()
                                ->map(fn($w) => mb_substr($w, 0, 1))
                                ->take(2)
                                ->implode('');
                        @endphp

                        <div class="jadwal-row">
                            <div class="jadwal-emp">
                                <span class="jadwal-avatar">{{ mb_strtoupper($inisial) }}</span>
                                <span class="jadwal-emp__text">
                                    <span class="jadwal-emp__name">{{ $k->nama }}</span>
                                    <span class="jadwal-emp__nip">{{ $k->nip }}</span>
                                </span>
                            </div>

                            @foreach ($hari as $h)
                                <div class="jadwal-cell @if ($h['isMinggu']) is-sunday @endif"
                                    data-date="{{ $h['iso'] }}" data-karyawan-id="{{ $k->id }}">
                                    {{-- Lanjutan shift lintas hari dari kemarin --}}
                                    @foreach ($jadwal->get($k->id . '|' . $kemarin[$h['iso']], []) as $c)
                                        @if ($c['lintas'])
                                            <div class="jadwal-cont"
                                                title="Lanjutan shift {{ $c['nama'] }} dari hari sebelumnya, sampai {{ $c['pulang'] }}">
                                                <span class="jadwal-cont__name">
                                                    <i class="bi bi-moon-stars-fill"></i> Lanjutan
                                                </span>
                                                <span class="jadwal-cont__time">s/d {{ $c['pulang'] }}</span>
                                            </div>
                                        @endif
                                    @endforeach

                                    @foreach ($jadwal->get($k->id . '|' . $h['iso'], []) as $c)
                                        <div class="jadwal-chip @if ($c['lintas']) is-lintas @endif"
                                            style="--c-bg: var(--shift-{{ $c['warna'] }}-bg); --c-text: var(--shift-{{ $c['warna'] }}-text); --c-border: var(--shift-{{ $c['warna'] }}-border); --s: {{ $c['start'] }}%; --w: {{ $c['lebar'] }}%;"
                                            data-jadwal-id="{{ $c['id'] }}" data-date="{{ $h['iso'] }}"
                                            data-karyawan="{{ $k->id }}" data-masuk="{{ $c['masuk'] }}"
                                            data-pulang="{{ $c['pulang'] }}" data-lintas="{{ $c['lintas'] ? 1 : 0 }}"
                                            title="{{ $k->nama }} · {{ $c['nama'] }} {{ $c['masuk'] }} - {{ $c['pulang'] }}{{ $c['lintas'] ? ' (+1 hari)' : '' }}">
                                            <span class="jadwal-chip__name">{{ $c['nama'] }}@if ($c['lintas'])<i class="bi bi-moon-stars-fill jadwal-chip__moon"></i>@endif</span>
                                            <span class="jadwal-chip__time">
                                                {{ $c['masuk'] }}–{{ $c['pulang'] }}@if ($c['lintas'])
                                                    <sup>+1</sup>
                                                @endif
                                            </span>
                                            <span class="jadwal-chip__bar"></span>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                @empty
                    <div class="jadwal-empty">
                        <i class="bi bi-calendar2-x"></i>
                        <p>Tidak ada karyawan yang cocok dengan filter.</p>
                    </div>
                @endforelse

                {{-- ---------- Garis merah (bisa digeser) ---------- --}}
                <div class="jadwal-timeline" data-timeline>
                    <div class="jadwal-timeline__cursor" data-timeline-cursor role="slider" tabindex="0"
                        aria-label="Garis waktu. Geser atau gunakan tombol panah kiri/kanan untuk mengubah jam."
                        aria-valuemin="0" aria-valuemax="{{ $hari->count() * 1440 }}">
                        <span class="jadwal-timeline__label" data-timeline-label>--:--</span>
                        <span class="jadwal-timeline__line"></span>
                    </div>
                </div>
            </div>
        </div>
    </x-panel>

    @includeIf('jadwal-karyawan.form-create')
@endsection