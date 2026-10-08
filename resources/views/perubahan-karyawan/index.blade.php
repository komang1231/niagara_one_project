@extends('layouts.app')

@section('content')
    @php

        $isMock = !isset($perubahanKaryawan);

        $jenisOptions ??= [
            'promosi' => 'Promosi',
            'demosi' => 'Demosi',
            'rotasi' => 'Rotasi',
            'mutasi' => 'Mutasi',
        ];
        $statusOptions ??= ['aktif' => 'Aktif', 'nonaktif' => 'Nonaktif'];

        // ---------------- MOCK DATA: HAPUS blok ini kalau backend sudah mengirim data ----------------
        if ($isMock) {
            $departemenOptions = [1 => 'Finance', 2 => 'Human Resources', 3 => 'IT', 4 => 'Operasional'];
            $levelOptions = [1 => 'Staff', 2 => 'Senior Staff', 3 => 'Supervisor', 4 => 'Manager'];
            $cabangOptions = [1 => 'Denpasar', 2 => 'Kuta', 3 => 'Ubud', 4 => 'Singaraja'];

            // [id, nama] untuk tiap field organisasi
            $mockKaryawan = [
                1 => [
                    'nama' => 'Komang Sabrina',
                    'nip' => 'NIP-2301',
                    'departemen' => [1, 'Finance'],
                    'divisi' => [11, 'Accounting'],
                    'section' => [111, 'Finance Section'],
                    'posisi' => [1111, 'Junior Accountant'],
                    'level' => [1, 'Staff'],
                    'cabang' => [1, 'Denpasar'],
                ],
                2 => [
                    'nama' => 'Made Dwi Putra',
                    'nip' => 'NIP-2302',
                    'departemen' => [3, 'IT'],
                    'divisi' => [31, 'Software Development'],
                    'section' => [311, 'Backend Team'],
                    'posisi' => [3111, 'Backend Developer'],
                    'level' => [2, 'Senior Staff'],
                    'cabang' => [1, 'Denpasar'],
                ],
                3 => [
                    'nama' => 'Ni Luh Ayu',
                    'nip' => 'NIP-2303',
                    'departemen' => [2, 'Human Resources'],
                    'divisi' => [21, 'Talent Acquisition'],
                    'section' => [211, 'Recruitment'],
                    'posisi' => [2111, 'Recruiter'],
                    'level' => [1, 'Staff'],
                    'cabang' => [2, 'Kuta'],
                ],
                4 => [
                    'nama' => 'Putu Arya Wiguna',
                    'nip' => 'NIP-2304',
                    'departemen' => [4, 'Operasional'],
                    'divisi' => [41, 'Customer Service'],
                    'section' => [411, 'Front Office'],
                    'posisi' => [4111, 'Front Office Staff'],
                    'level' => [1, 'Staff'],
                    'cabang' => [3, 'Ubud'],
                ],
                5 => [
                    'nama' => 'Kadek Wirawan',
                    'nip' => 'NIP-2305',
                    'departemen' => [1, 'Finance'],
                    'divisi' => [11, 'Accounting'],
                    'section' => [111, 'Finance Section'],
                    'posisi' => [1112, 'Senior Accountant'],
                    'level' => [2, 'Senior Staff'],
                    'cabang' => [2, 'Kuta'],
                ],
                6 => [
                    'nama' => 'Gede Surya Darma',
                    'nip' => 'NIP-2306',
                    'departemen' => [4, 'Operasional'],
                    'divisi' => [41, 'Customer Service'],
                    'section' => [411, 'Front Office'],
                    'posisi' => [4112, 'Supervisor Front Office'],
                    'level' => [3, 'Supervisor'],
                    'cabang' => [4, 'Singaraja'],
                ],
            ];

            $karyawanOptions = collect($mockKaryawan)
                ->map(function ($k, $id) {
                    $o = ['id' => $id, 'nama' => $k['nama'], 'nip' => $k['nip']];
                    foreach (['departemen', 'divisi', 'section', 'posisi', 'level', 'cabang'] as $f) {
                        $o[$f . '_id'] = $k[$f][0];
                        $o[$f] = $k[$f][1];
                    }
                    return $o;
                })
                ->values();

            // $baru = field yang berubah saja ([id, nama]); sisanya sama dengan data lama
            $buatRow = function ($id, $kid, $jenis, $status, $sk, $tgl, $file, array $baru) use ($mockKaryawan) {
                $k = $mockKaryawan[$kid];
                $row = [
                    'id' => $id,
                    'karyawan_id' => $kid,
                    'karyawan_nama' => $k['nama'],
                    'karyawan_nip' => $k['nip'],
                    'jenis_perubahan' => $jenis,
                    'status' => $status,
                    'nomor_sk' => $sk,
                    'tanggal_efektif' => $tgl,
                    'file_sk_name' => $file,
                    'file_sk_url' => $file ? '#' : null,
                ];
                foreach (['departemen', 'divisi', 'section', 'posisi', 'level', 'cabang'] as $f) {
                    $b = $baru[$f] ?? $k[$f];
                    $row[$f . '_lama_id'] = $k[$f][0];
                    $row[$f . '_lama'] = $k[$f][1];
                    $row[$f . '_baru_id'] = $b[0];
                    $row[$f . '_baru'] = $b[1];
                }
                return $row;
            };

            $mockRows = [
                $buatRow(1, 1, 'promosi', 'aktif', 'SK/HRD/001/X/2026', '2026-10-11', 'sk-001-2026.pdf', [
                    'posisi' => [1112, 'Senior Accountant'],
                    'level' => [2, 'Senior Staff'],
                ]),
                $buatRow(2, 5, 'mutasi', 'aktif', 'SK/HRD/002/X/2026', '2026-10-20', 'sk-002-2026.pdf', [
                    'cabang' => [3, 'Ubud'],
                ]),
                $buatRow(3, 3, 'rotasi', 'aktif', 'SK/HRD/003/XI/2026', '2026-11-01', 'sk-003-2026.pdf', [
                    'departemen' => [4, 'Operasional'],
                    'divisi' => [41, 'Customer Service'],
                    'section' => [411, 'Front Office'],
                    'posisi' => [4111, 'Front Office Staff'],
                ]),
                $buatRow(4, 2, 'promosi', 'aktif', 'SK/HRD/004/IX/2026', '2026-09-01', null, [
                    'posisi' => [3112, 'Lead Backend Developer'],
                    'level' => [3, 'Supervisor'],
                ]),
                $buatRow(5, 6, 'demosi', 'nonaktif', 'SK/HRD/005/VIII/2026', '2026-08-15', 'sk-005-2026.pdf', [
                    'posisi' => [4111, 'Front Office Staff'],
                    'level' => [1, 'Staff'],
                ]),
                $buatRow(6, 4, 'mutasi', 'nonaktif', 'SK/HRD/006/VII/2026', '2026-07-01', 'sk-006-2026.pdf', [
                    'cabang' => [1, 'Denpasar'],
                ]),
            ];

            $perubahanKaryawan = new \Illuminate\Pagination\LengthAwarePaginator($mockRows, count($mockRows), 10, 1, [
                'path' => request()->url(),
            ]);
        }
        // ---------------- AKHIR MOCK DATA ----------------

        // ---------------- Helper tampilan ----------------
        // Baris kosong dibuang supaya baris pertama di cell-stack selalu berisi data.
        $stack = fn(...$lines) => array_values(array_filter($lines, 'filled'));

        $labelField = [
            'departemen' => 'Departemen',
            'divisi' => 'Divisi',
            'section' => 'Section',
            'posisi' => 'Job Position',
            'level' => 'Job Level',
            'cabang' => 'Cabang',
        ];

        // Field yang paling relevan ditampilkan duluan, sesuai jenis perubahan.
        $urutan = [
            'promosi' => ['level', 'posisi', 'cabang', 'departemen', 'divisi', 'section'],
            'demosi' => ['level', 'posisi', 'cabang', 'departemen', 'divisi', 'section'],
            'rotasi' => ['posisi', 'departemen', 'divisi', 'section', 'level', 'cabang'],
            'mutasi' => ['cabang', 'posisi', 'departemen', 'divisi', 'section', 'level'],
        ];

        // Daftar field yang benar-benar berubah: [label, lama, baru]
        $daftarPerubahan = function ($row) use ($labelField, $urutan) {
            $hasil = [];

            $relasi = [
                'departemen' => ['departemenLama', 'departemenBaru'],
                'divisi' => ['divisiLama', 'divisiBaru'],
                'section' => ['sectionLama', 'sectionBaru'],
                'posisi' => ['posisiLama', 'posisiBaru'],
                'level' => ['levelLama', 'levelBaru'],
                'cabang' => ['cabangLama', 'cabangBaru'],
            ];

            $kolomId = [
                'departemen' => ['departemen_lama', 'departemen_baru'],
                'divisi' => ['divisi_lama', 'divisi_baru'],
                'section' => ['section_lama', 'section_baru'],
                'posisi' => ['posisi_lama', 'posisi_baru'],
                'level' => ['level_lama', 'level_baru'],
                'cabang' => ['cabang_lama', 'cabang_baru'],
            ];

            foreach ($urutan[data_get($row, 'jenis_perubahan')] ?? array_keys($labelField) as $f) {
                [$lamaKolom, $baruKolom] = $kolomId[$f];
                [$lamaRelasi, $baruRelasi] = $relasi[$f];

                $lamaId = data_get($row, $lamaKolom);
                $baruId = data_get($row, $baruKolom);

                if ((string) $lamaId !== (string) $baruId) {
                    $hasil[] = [
                        $labelField[$f],
                        data_get($row, $lamaRelasi . '.nama') ?: '-',
                        data_get($row, $baruRelasi . '.nama') ?: '-',
                    ];
                }
            }

            return $hasil;
        };

        // Badge jenis perubahan: [variant <x-badge>, icon]. Hanya memakai variant bawaan komponen badge.
        $jenisBadge = [
            'promosi' => ['success', 'bi-graph-up-arrow'],
            'demosi' => ['warning', 'bi-graph-down-arrow'],
            'rotasi' => ['neutral', 'bi-arrow-repeat'],
            'mutasi' => ['neutral', 'bi-geo-alt'],
        ];

        // Info tanggal efektif: [tanggal tampil, teks bantu, level (soon|today|done), icon]
        $infoEfektif = function ($tanggal) {
            $tgl = \Carbon\Carbon::parse($tanggal)->startOfDay();
            $sisa = (int) \Carbon\Carbon::today()->diffInDays($tgl, false); // negatif = sudah lewat
            $tampil = $tgl->copy()->locale('id')->translatedFormat('d M Y');

            if ($sisa > 0) {
                return [$tampil, "Berlaku {$sisa} hari lagi", 'soon', 'bi-clock'];
            }
            if ($sisa === 0) {
                return [$tampil, 'Berlaku hari ini', 'today', 'bi-check-circle'];
            }
            return [$tampil, 'Sudah berlaku', 'done', 'bi-check2'];
        };

        $maksPerubahan = 3; // baris perubahan yang tampil per karyawan, sisanya diringkas "+N lainnya"
    @endphp

    <x-page-header eyebrow="Karyawan" title="Perubahan Karyawan"
        description="Catat dan kelola perubahan data organisasi karyawan." icon="bi-arrow-left-right">
        <x-slot:badges>
            <x-badge>{{ $perubahanKaryawan->total() }} perubahan</x-badge>
        </x-slot:badges>

        <x-slot:actions>
            <x-button variant="outline" icon="bi-trash" href="{{ url('perubahan-karyawan-trash') }}">
                Trash
            </x-button>

            <x-button variant="primary" icon="bi-plus-lg" data-bs-toggle="offcanvas" data-bs-target="#offcanvas-pk">
                Buat Perubahan
            </x-button>
        </x-slot:actions>
    </x-page-header>

    <x-panel>
        <div>
            <x-filter.bar :clearable="['search', 'jenis', 'status', 'efektif']" ajax-target="#perubahan-karyawan-table">
                <x-filter.search placeholder="Cari nama, NIP, atau nomor SK" />

                <x-filter.multiselect name="jenis" label="Jenis Perubahan" :options="$jenisOptions" />

                <x-filter.multiselect name="status" label="Status" :options="$statusOptions" />

                <x-filter.date-range name="efektif" label="Tanggal Efektif" type="date" />
            </x-filter.bar>
        </div>

        <hr class="app-panel__divider">

        <div id="perubahan-karyawan-table">
            @fragment('perubahan-karyawan-table')
                <x-table class="pk-table">
                    <thead>
                        <tr>
                            <th class="app-table__col-no">NO</th>
                            <th>Karyawan</th>
                            <th>Jenis Perubahan</th>
                            <th>Perubahan</th>
                            <th>Tanggal Efektif</th>
                            <th>Nomor SK</th>
                            <th>Status</th>
                            <th class="app-table__col-actions">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($perubahanKaryawan as $i => $row)
                            @php
                                $jenis = data_get($row, 'jenis_perubahan');
                                $aktif = data_get($row, 'status') === 'aktif';
                                [$badgeVariant, $badgeIcon] = $jenisBadge[$jenis] ?? ['neutral', 'bi-circle'];
                                [$tglTampil, $tglBantu, $tglLevel, $tglIcon] = $infoEfektif(
                                    data_get($row, 'tanggal_efektif'),
                                );

                                $perubahan = $daftarPerubahan($row);
                                $tampil = array_slice($perubahan, 0, $maksPerubahan);
                                $sisa = array_slice($perubahan, $maksPerubahan);
                                $sisaTeks = collect($sisa)->map(fn($c) => "{$c[0]}: {$c[1]} → {$c[2]}")->implode("\n");

                                // Semua data baris dikirim ke offcanvas lewat atribut ini (tanpa endpoint show/edit-data)
                                $payload = [
                                    'id' => $row->id,

                                    // Karyawan
                                    'karyawan_id' => $row->karyawan_id,
                                    'karyawan_nama' => $row->karyawan?->nama,
                                    'karyawan_nip' => $row->karyawan?->nip,

                                    // Data lama
                                    'departemen_lama' => $row->departemen_lama,
                                    'departemen_lama_nama' => $row->departemenLama?->nama,

                                    'divisi_lama' => $row->divisi_lama,
                                    'divisi_lama_nama' => $row->divisiLama?->nama,

                                    'section_lama' => $row->section_lama,
                                    'section_lama_nama' => $row->sectionLama?->nama,

                                    'posisi_lama' => $row->posisi_lama,
                                    'posisi_lama_nama' => $row->posisiLama?->nama,

                                    'level_lama' => $row->level_lama,
                                    'level_lama_nama' => $row->levelLama?->nama,

                                    'cabang_lama' => $row->cabang_lama,
                                    'cabang_lama_nama' => $row->cabangLama?->nama,

                                    // Data baru
                                    'departemen_baru' => $row->departemen_baru,
                                    'departemen_baru_nama' => $row->departemenBaru?->nama,

                                    'divisi_baru' => $row->divisi_baru,
                                    'divisi_baru_nama' => $row->divisiBaru?->nama,

                                    'section_baru' => $row->section_baru,
                                    'section_baru_nama' => $row->sectionBaru?->nama,

                                    'posisi_baru' => $row->posisi_baru,
                                    'posisi_baru_nama' => $row->posisiBaru?->nama,

                                    'level_baru' => $row->level_baru,
                                    'level_baru_nama' => $row->levelBaru?->nama,

                                    'cabang_baru' => $row->cabang_baru,
                                    'cabang_baru_nama' => $row->cabangBaru?->nama,

                                    // Perubahan
                                    'jenis_perubahan' => $row->jenis_perubahan,
                                    'tanggal_efektif' => $row->tanggal_efektif,
                                    'status' => $row->status,

                                    // SK
                                    'kode' => $row->kode,
                                    'file_sk' => $row->file_sk,
                                    'file_sk_url' => $row->file_sk ? Storage::disk('public')->url($row->file_sk) : null,
                                    'file_sk_name' => $row->file_sk ? basename($row->file_sk) : null,
                                ];
                            @endphp

                            <tr>
                                <td class="app-table__col-no">
                                    {{ $perubahanKaryawan->firstItem() + $i }}
                                </td>

                                {{-- Karyawan: avatar + nama + NIP --}}
                                <td>
                                    <x-table.cell-stack :avatar="$row->karyawan?->nama" :lines="$stack($row->karyawan?->nama, $row->karyawan?->nip)" />
                                </td>

                                {{-- Jenis perubahan --}}
                                <td>
                                    <x-badge :variant="$badgeVariant" :icon="$badgeIcon">{{ ucfirst($jenis) }}</x-badge>
                                </td>

                                {{-- Ringkasan perubahan: hanya field yang berubah. Label di kiri, "lama → baru" di kanan --}}
                                <td class="pk-col-change">
                                    @if (count($tampil))
                                        <div class="pk-change-list">
                                            @foreach ($tampil as [$label, $lama, $baru])
                                                <div class="pk-change">
                                                    <span class="pk-change__label">{{ $label }}</span>
                                                    <span class="pk-change__flow">
                                                        <span class="pk-change__old">{{ $lama }}</span>
                                                        <i class="bi bi-arrow-right pk-change__arrow"></i>
                                                        <span class="pk-change__new">{{ $baru }}</span>
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>

                                        @if (count($sisa))
                                            <span class="pk-more" title="{{ $sisaTeks }}">+{{ count($sisa) }} perubahan
                                                lainnya</span>
                                        @endif
                                    @else
                                        <span class="text-muted small">Tidak ada perubahan data</span>
                                    @endif
                                </td>

                                {{-- Tanggal efektif: tanggal di atas, teks bantu di bawah --}}
                                <td>
                                    <div class="pk-date pk-date--{{ $tglLevel }}">
                                        <span class="pk-date__value">{{ $tglTampil }}</span>
                                        <span class="pk-date__hint"><i
                                                class="bi {{ $tglIcon }}"></i>{{ $tglBantu }}</span>
                                    </div>
                                </td>

                                {{-- Nomor SK + file (klik untuk membuka) --}}
                                <td>
                                    <div class="pk-sk">
                                        <span class="pk-sk__nomor">{{ $row->kode }}</span>
                                        @if ($row->file_sk)
                                            <a class="pk-sk__file" href="{{ asset('storage/' . $row->file_sk) }}"
                                                target="_blank" rel="noopener" title="{{ basename($row->file_sk) }}">
                                                <i class="bi bi-paperclip"></i>
                                                <span>{{ basename($row->file_sk) }}</span>
                                            </a>
                                        @else
                                            <span class="pk-sk__file pk-sk__file--empty">SK belum tersedia</span>
                                        @endif
                                    </div>
                                </td>

                                {{-- Status: komponen <x-badge> yang sama dengan menu lain --}}
                                <td>
                                    <x-badge :variant="$aktif ? 'success' : 'neutral'" data-pk-status-badge>
                                        <span data-pk-status-text>{{ $aktif ? 'Aktif' : 'Nonaktif' }}</span>
                                    </x-badge>
                                </td>

                                <td class="app-table__col-actions">
                                    <div class="app-table__actions">
                                        {{-- Buka offcanvas mode lihat; tombol Edit ada di footer offcanvas --}}
                                        <x-button variant="icon-edit" icon="bi-pencil" title="Lihat / edit"
                                            data-bs-toggle="offcanvas" data-bs-target="#offcanvas-pk-edit" data-pk-open
                                            data-pk-row="{{ json_encode($payload, JSON_UNESCAPED_UNICODE) }}"
                                            data-update-url="{{ url('perubahan-karyawan/' . data_get($row, 'id')) }}" />

                                        {{-- Hapus (soft delete) -> masuk Trash --}}
                                        <form action="{{ url('perubahan-karyawan/' . data_get($row, 'id')) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <x-button type="submit" variant="icon-danger" icon="bi-trash" title="Hapus" />
                                        </form>

                                        {{-- Aktif / nonaktifkan status --}}
                                        <x-table.status-toggle :checked="$aktif" id="status-toggle-{{ data_get($row, 'id') }}"
                                            data-id="{{ data_get($row, 'id') }}" />
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <x-table.empty-row colspan="8" text="Belum ada perubahan karyawan." />
                        @endforelse
                    </tbody>
                </x-table>

                <div class="app-table-footer">
                    <span>
                        Menampilkan
                        {{ $perubahanKaryawan->firstItem() ?? 0 }}–{{ $perubahanKaryawan->lastItem() ?? 0 }}
                        dari
                        {{ $perubahanKaryawan->total() }}
                        entri
                    </span>

                    <x-pagination :paginator="$perubahanKaryawan" />
                </div>
            @endfragment
        </div>
    </x-panel>

    @include('perubahan-karyawan.form-create')
    @include('perubahan-karyawan.form-edit')

    <script>
        // Toggle status aktif / nonaktif (pola sama dengan menu lain: PATCH lalu reload)
        document.addEventListener('change', function(e) {
            const toggle = e.target.closest('.app-table-toggle input[type="checkbox"]');

            if (!toggle) return;

            // TODO BACKEND: hapus blok mock ini kalau route toggle-status sudah ada.
            if (toggle.dataset.mock === '1') {
                const badge = toggle.closest('tr').querySelector('[data-pk-status-badge]');
                badge.classList.toggle('badge-success', toggle.checked);
                badge.classList.toggle('badge-neutral', !toggle.checked);
                badge.querySelector('[data-pk-status-text]').textContent = toggle.checked ? 'Aktif' : 'Nonaktif';
                return;
            }

            const id = toggle.dataset.id;

            fetch(`/perubahan-karyawan/${id}/toggle-status`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Gagal mengubah status.');
                    }

                    return response.json();
                })
                .then(() => window.location.reload())
                .catch(error => {
                    console.error(error);

                    // Kembalikan switch jika request gagal
                    toggle.checked = !toggle.checked;
                });
        });
    </script>
@endsection
