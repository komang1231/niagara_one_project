@extends('layouts.app')

@section('title', 'Attendance')

@section('content')
    <x-page-header eyebrow="Attendance" title="Attendance" description="Pantau kehadiran karyawan secara terpusat."
        icon="bi-clock-fill">
        <x-slot:badges>
            @include('attendance.partials.badges')
        </x-slot:badges>

        <x-slot:actions>
            @include('attendance.partials.actions')
        </x-slot:actions>
    </x-page-header>

    @include('attendance.partials.monitoring')
@endsection
