{{--
    Tombol Check-in / Check-out di page header.
    Variabel: $canCheckIn, $canCheckOut, $earlyCheckout
    POST ke attendance.check-in / attendance.check-out; latitude & longitude diisi resources/js/attendance.js dari GPS browser.
--}}
<form method="POST" action="{{ route('attendance.check-in') }}" class="attendance-punch-form" data-punch-form>
    @csrf
    <input type="hidden" name="latitude">
    <input type="hidden" name="longitude">

    <x-button type="submit" variant="primary" icon="bi-box-arrow-in-right" :disabled="!$canCheckIn">
        Check-in
    </x-button>
</form>

<form method="POST" action="{{ route('attendance.check-out') }}" class="attendance-punch-form" data-punch-form
    @if ($earlyCheckout) data-confirm="Jam kerja shift belum selesai. Check-out sekarang akan tercatat sebagai pulang cepat." @endif>
    @csrf
    <input type="hidden" name="latitude">
    <input type="hidden" name="longitude">

    <x-button type="submit" variant="outline" icon="bi-box-arrow-right" :disabled="!$canCheckOut">
        Check-out
    </x-button>
</form>
