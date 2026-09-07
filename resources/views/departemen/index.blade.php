@extends('layouts.app')

@section('content')
    <x-page-header eyebrow="Karyawan" title="Data Karyawan"
        description="Kelola informasi karyawan, status kepegawaian, dan data terkait." icon="bi-diagram-3-fill">
        <x-slot:badges>
            <x-badge>110 employees</x-badge>
        </x-slot:badges>

        <x-slot:actions>
            <x-button variant="outline" icon="bi-trash">Trash</x-button>
            <x-button variant="primary" icon="bi-plus-lg">Tambah Karyawan</x-button>
        </x-slot:actions>
    </x-page-header>

@endsection
