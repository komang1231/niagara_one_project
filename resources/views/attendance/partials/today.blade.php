{{--
    Panel "Absensi hari ini": jam masuk, jam keluar, terlambat, pulang cepat, durasi kerja.
    Variabel: $state, $now, $attendance, $message. Kosong ditampilkan "–", bukan angka karangan.
--}}
@php
    $jam = fn($t) => $t ? \Carbon\Carbon::parse($t)->format('H:i') : null;

    $durasi = function ($menit) {
        if ($menit === null) {
            return null;
        }
        $menit = (int) round($menit);
        $h = intdiv($menit, 60);
        $m = $menit % 60;

        return $h > 0 ? "{$h} j {$m} mnt" : "{$m} mnt";
    };

    $terlambat = null;
    $pulangCepat = null;
    $kerja = null;

    if ($attendance && $attendance->jam_masuk) {
        $terlambat = $attendance->total_menit_terlambat ? $durasi($attendance->total_menit_terlambat) : 'Tepat waktu';
    }

    if ($attendance && $attendance->jam_keluar) {
        $pulangCepat = $attendance->total_menit_pulang_cepat ? $durasi($attendance->total_menit_pulang_cepat) : 'Sesuai jadwal';
        $kerja = $durasi($attendance->total_menit_kerja);
    } elseif ($state === 'working') {
        $kerja = 'Sedang berjalan';
    }

    $items = [
        ['Jam masuk', $attendance ? $jam($attendance->jam_masuk) : null],
        ['Jam keluar', $attendance ? $jam($attendance->jam_keluar) : null],
        ['Terlambat', $terlambat],
        ['Pulang cepat', $pulangCepat],
        ['Durasi kerja', $kerja],
    ];

    $notice = $message;

    if ($state === 'recorded' && $attendance) {
        $notice = 'Absensi hari ini sudah dicatat' . ($attendance->is_manual ? ' secara manual' : '') . ', jadi check-in dan check-out tidak tersedia.';
    }
@endphp

<x-panel class="attendance-today">

    <div class="attendance-today__head">
        <div>
            <h2 class="attendance-today__title">Absensi hari ini</h2>
            <p class="attendance-today__meta">{{ $now->copy()->locale('id')->translatedFormat('l, d F Y') }}</p>
        </div>

        <div class="attendance-today__clock-wrap">
            @if ($attendance && $attendance->is_manual)
                <x-badge icon="bi-pencil-square">Manual</x-badge>
            @endif

            <span class="attendance-today__clock" data-server-clock data-ts="{{ $now->timestamp }}"
                data-offset="{{ $now->utcOffset() }}">{{ $now->format('H:i:s') }}</span>
            <span class="attendance-today__tz">{{ $now->format('T') }}</span>
        </div>
    </div>

    @if ($errors->any())
        <div class="attendance-notice attendance-notice--danger" role="alert">
            <i class="bi bi-exclamation-circle"></i>
            <span>{{ $errors->first() }}</span>
        </div>
    @endif

    @if ($notice)
        <div class="attendance-notice" role="status">
            <i class="bi bi-info-circle"></i>
            <span>{{ $notice }}</span>
        </div>
    @endif

    <div class="attendance-today__grid">
        @foreach ($items as [$label, $value])
            <div class="attendance-today__item">
                <span class="attendance-today__label">{{ $label }}</span>
                <span class="attendance-today__value {{ $value === null ? 'is-empty' : '' }}">{{ $value ?? '–' }}</span>
            </div>
        @endforeach
    </div>

    @if ($attendance && $attendance->latitude !== null && $attendance->longitude !== null)
        <p class="attendance-today__loc">
            <i class="bi bi-geo-alt"></i>
            Lokasi tercatat · Latitude {{ $attendance->latitude }} · Longitude {{ $attendance->longitude }}
        </p>
    @endif

</x-panel>
