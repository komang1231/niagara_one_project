{{-- Badge di page header: shift hari ini + status attendance. Variabel: $shift, $attendance --}}
@if ($shift)
    <x-badge icon="bi-clock">{{ $shift->nama }} · {{ \Carbon\Carbon::parse($shift->jam_masuk)->format('H:i') }} – {{ \Carbon\Carbon::parse($shift->jam_pulang)->format('H:i') }}</x-badge>
@endif

@if ($attendance)
    <x-attendance.status-badge :status="$attendance->status" />
@endif
