{{--
    Badge status Attendance - SATU tempat untuk label & warna status.
    Pemakaian: <x-attendance.status-badge :status="$attendance->status" />
    Status mengikuti enum di migration attendances:
    hadir, terlambat, sakit, cuti, alpa, izin, libur, libur_nasional
--}}
@props(['status'])

@php
    $map = [
        'hadir' => ['Hadir', 'success'],
        'terlambat' => ['Terlambat', 'warning'],
        'alpa' => ['Alpa', 'danger'],
        'sakit' => ['Sakit', 'neutral'],
        'izin' => ['Izin', 'neutral'],
        'cuti' => ['Cuti', 'neutral'],
        'libur' => ['Libur', 'neutral'],
        'libur_nasional' => ['Libur Nasional', 'neutral'],
    ];

    [$label, $variant] = $map[$status] ?? [ucfirst(str_replace('_', ' ', (string) $status)), 'neutral'];
@endphp

<x-badge :variant="$variant" {{ $attributes }}>{{ $label }}</x-badge>
