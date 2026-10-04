<x-offcanvas.form id="offcanvas-saldo-cuti" title="Atur Saldo Cuti"
    description="Tetapkan hak cuti karyawan untuk satu jenis cuti dan satu tahun." size="md">
    <form id="offcanvas-saldo-cuti-form" action="{{ route('saldo-cuti.store') }}" method="POST" novalidate>
        @csrf

        <x-form.select name="karyawan_id" id="sc_create_karyawan_id" label="Karyawan" :options="$karyawanOptions" nullable
            required />

        <x-form.select name="cuti_id" id="sc_create_cuti_id" label="Jenis Cuti" :options="$cutiOptions" nullable required />

        <x-form.select name="tahun" id="sc_create_tahun" label="Tahun" :options="$tahunOptions"
            :selected="now()->year" required />

        <x-form.input-stepper name="saldo" label="Hak Cuti (hari)" :value="12" :min="0" :max="365"
            required />

        <p class="text-muted small mb-0">
            Satu karyawan hanya punya satu saldo per jenis cuti per tahun. Jumlah terpakai dihitung dari cuti yang
            disetujui.
        </p>
    </form>
</x-offcanvas.form>
