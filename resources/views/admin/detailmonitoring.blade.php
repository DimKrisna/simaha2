@extends('sidebaradmin')

@section('content')
<div class="container">
    <div class="row mt-5">
        <div class="col-md-8 mx-auto">
            <h1>{{ $detailMonitoring->judul_kegiatan }}</h1>
            <div class="card mb-3 card-medium">
                <img src="{{ asset($detailMonitoring->foto) }}" class="card-img-top img-fluid card-img" alt="Foto">
                <div class="card-body">
                    <p class="card-text">{{ $detailMonitoring->keterangan }}</p>
                    <p class="card-text"><small class="text-body-secondary">Waktu Pelaksanaan {{ $detailMonitoring->waktu }}</small></p>
                    <form action="{{ route('updateMonitor', ['id' => $detailMonitoring->id_monitoring]) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success">OKE</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card-medium {
    width: 100%;
    max-width: 600px; /* Anda bisa menyesuaikan nilai ini */
    margin: 0 auto; /* Agar card berada di tengah */
}

.card-img {
    width: 100%;
    height: 300px; /* Anda bisa menyesuaikan nilai ini */
    object-fit: cover; /* Menyesuaikan gambar dengan card tanpa distorsi */
    object-position: center; /* Memusatkan gambar */
}
</style>
@endsection
