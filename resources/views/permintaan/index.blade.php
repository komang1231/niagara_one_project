@extends('layouts.app')

@section('title', 'Permintaan')

@section('content')

    {{-- data-reopen: kalau validasi create gagal, offcanvas create dibuka lagi oleh JS --}}
    <div id="permintaan-root"
        data-reopen="{{ $errors->any() && !str_ends_with(old('_form', ''), '-edit') ? old('_form') : '' }}">

        {{-- ===== NAV TAB ===== --}}
        <ul class="nav nav-tabs permintaan-tabs" role="tablist">
            @foreach ($tabs as $key => $label)
                <li class="nav-item" role="presentation">
                    <button type="button" class="nav-link @if ($tab === $key) active @endif"
                        data-permintaan-tab="{{ $key }}" data-bs-toggle="tab"
                        data-bs-target="#tab-{{ $key }}" role="tab">
                        {{ $label }}
                    </button>
                </li>
            @endforeach
        </ul>

        {{-- ===== ISI TAB (tiap tab = 1 file include) ===== --}}
        <div class="tab-content">
            @foreach ($tabs as $key => $label)
                <div class="tab-pane fade @if ($tab === $key) show active @endif" id="tab-{{ $key }}"
                    data-tab="{{ $key }}" role="tabpanel">
                    @include('permintaan.tabs.' . $key)
                </div>
            @endforeach
        </div>
    </div>

    {{-- ===== OFFCANVAS CREATE & EDIT (5 x 2) ===== --}}
    @foreach (array_keys($tabs) as $key)
        @include('permintaan.forms.' . $key . '-create')
        @include('permintaan.forms.' . $key . '-edit')
    @endforeach


@endsection
