{{-- Ganti nama layout sesuai @extends yang kamu pakai di index Lowongan --}}
@extends('layouts.app')

@section('title', 'Permintaan')

@section('content')

    <style>
        .permintaan-tabs {
            margin-bottom: 1rem;
        }

        .permintaan-toolbar {
            display: flex;
            justify-content: space-between;
            gap: .75rem;
            flex-wrap: wrap;
            margin-bottom: 1rem;
        }

        .permintaan-status {
            display: inline-block;
            padding: .25rem .65rem;
            border-radius: 999px;
            font-size: .75rem;
            font-weight: 600;
        }

        .permintaan-status--menunggu {
            background: #fff4d6;
            color: #8a6100;
        }

        .permintaan-status--disetujui {
            background: #dcf5e3;
            color: #1b6b3a;
        }

        .permintaan-status--ditolak {
            background: #fde2e2;
            color: #a12626;
        }

        .permintaan-status--dibatalkan {
            background: #e9ecef;
            color: #5c636a;
        }

        .permintaan-detail-list {
            border: 1px solid #dee2e6;
            border-radius: .5rem;
            padding: .5rem .75rem;
            max-height: 260px;
            overflow: auto;
        }

        .permintaan-detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: .4rem 0;
            border-bottom: 1px dashed #e5e7eb;
        }

        .permintaan-detail-row:last-child {
            border-bottom: 0;
        }
    </style>

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
