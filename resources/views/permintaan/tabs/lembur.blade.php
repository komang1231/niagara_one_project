<x-page-header eyebrow="Permintaan" title="Lembur"
    description="Kelola pengajuan lembur karyawan beserta waktu dan status persetujuannya." icon="bi-clock-fill">
    <x-slot:badges>
        <x-badge>{{ $permintaanLembur->total() }} permintaan</x-badge>
    </x-slot:badges>

    <x-slot:actions>
        <x-button variant="outline" icon="bi-trash" href="{{ route('permintaan-lembur.trash') }}">
            Trash
        </x-button>
        <x-button variant="primary" icon="bi-plus-lg" data-bs-toggle="offcanvas"
            data-bs-target="#offcanvas-permintaan-lembur">
            Tambah Permintaan Lembur
        </x-button>
    </x-slot:actions>
</x-page-header>

<x-panel>
    <div>
        <x-filter.bar :clearable="['search', 'status']" ajax-target="#lembur-table">
            <x-filter.search placeholder="Cari nama atau kode Lembur" />

            {{-- 
            <x-filter.multiselect
                name="status"
                label="Status"
                :options="$statusOptions"
            />
            --}}
        </x-filter.bar>
    </div>

    <hr class="app-panel__divider">

    <div id="lembur-table"></div>

    <x-table>
        <thead>
            <tr>
                <th class="app-table__col-no">NO</th>
                <th>Kode</th>
                <th>Pemohon</th>
                <th>Info Utama</th>
                <th>Alasan</th>
                <th>Status</th>
                <th class="text-end">Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($permintaanLembur as $i => $row)
                @php
                    $jamMulai = substr((string) $row->jam_mulai, 0, 5);
                    $jamSelesai = substr((string) $row->jam_selesai, 0, 5);

                    $tanggalTujuan = \Carbon\Carbon::parse($row->tanggal_tujuan);

                    $bulan = [
                        1 => 'Jan',
                        2 => 'Feb',
                        3 => 'Mar',
                        4 => 'Apr',
                        5 => 'Mei',
                        6 => 'Jun',
                        7 => 'Jul',
                        8 => 'Agu',
                        9 => 'Sep',
                        10 => 'Okt',
                        11 => 'Nov',
                        12 => 'Des',
                    ];

                    $lintasHari = $jamSelesai < $jamMulai;

                    [$jamMulaiHour, $jamMulaiMinute] = array_map('intval', explode(':', $jamMulai));
                    [$jamSelesaiHour, $jamSelesaiMinute] = array_map('intval', explode(':', $jamSelesai));

                    $menitMulai = $jamMulaiHour * 60 + $jamMulaiMinute;
                    $menitSelesai = $jamSelesaiHour * 60 + $jamSelesaiMinute;

                    if ($lintasHari) {
                        $menitSelesai += 24 * 60;
                    }

                    $durasiMenit = $menitSelesai - $menitMulai;
                    $durasiJam = intdiv($durasiMenit, 60);
                    $durasiSisaMenit = $durasiMenit % 60;

                    $durasiText = '';

                    if ($durasiJam > 0) {
                        $durasiText .= $durasiJam . ' jam';
                    }

                    if ($durasiSisaMenit > 0) {
                        $durasiText .= ($durasiText ? ' ' : '') . $durasiSisaMenit . ' menit';
                    }

                    if ($durasiText === '') {
                        $durasiText = '0 menit';
                    }
                @endphp

                <tr>
                    <td class="app-table__col-no">
                        {{ $permintaanLembur->firstItem() + $i }}
                    </td>

                    <td class="fw-semibold">
                        {{ $row->kode }}
                    </td>

                    <td>
                        <x-table.cell-stack :avatar="$row->karyawan?->avatar" :lines="[$row->karyawan?->nama ?? 'Pemohon tidak ditemukan']" />
                    </td>

                    <td>
                        <x-table.cell-stack :lines="[
                            $tanggalTujuan->day . ' ' . $bulan[$tanggalTujuan->month] . ' ' . $tanggalTujuan->year,
                            $jamMulai . ' - ' . $jamSelesai,
                        ]" />

                        <div class="mt-2 d-flex flex-wrap gap-1">
                            <span class="badge bg-light text-dark border">
                                <i class="bi bi-clock me-1"></i>
                                {{ $durasiText }}
                            </span>

                            @if ($lintasHari)
                                <span class="badge bg-light text-dark border">
                                    <i class="bi bi-moon-stars me-1"></i>
                                    Lintas hari
                                </span>
                            @endif
                        </div>

                        @if ($lintasHari)
                            <div class="small text-muted mt-1">
                                <i class="bi bi-arrow-return-right me-1"></i>
                                Selesai pada hari berikutnya
                            </div>
                        @endif
                    </td>

                    <td style="width: 260px; max-width: 260px;">
                        <div
                            style="max-width: 260px; white-space: normal; overflow-wrap: anywhere; word-break: break-word; line-height: 1.5;">
                            {{ $row->alasan ?: '-' }}
                        </div>
                    </td>

                    <td>
                        @include('permintaan.partials.status', ['row' => $row])
                    </td>

                    <td>
                        @include('permintaan.partials.aksi', [
                            'row' => $row,
                            'slug' => 'permintaan-lembur',
                            'label' => 'Permintaan Lembur',
                        ])
                    </td>
                </tr>
            @empty
                <x-table.empty-row :colspan="7" text="Belum ada permintaan lembur." />
            @endforelse
        </tbody>
    </x-table>

    <div class="mt-3">
        {{ $permintaanLembur->links() }}
    </div>
</x-panel>
