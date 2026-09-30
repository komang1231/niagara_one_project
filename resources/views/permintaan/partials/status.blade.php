@php
    // Urutan pengecekan penting: Dibatalkan (soft delete) menang paling atas
    if ($row->trashed()) {
        [$kelas, $teks] = ['dibatalkan', 'Dibatalkan'];
    } elseif ($row->approved_at) {
        [$kelas, $teks] = ['disetujui', 'Disetujui'];
    } elseif ($row->rejected_at) {
        [$kelas, $teks] = ['ditolak', 'Ditolak'];
    } else {
        [$kelas, $teks] = ['menunggu', 'Menunggu'];
    }
@endphp

<span class="permintaan-status permintaan-status--{{ $kelas }}">{{ $teks }}</span>