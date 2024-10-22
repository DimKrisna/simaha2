@extends('sidebarprodi')

@section('content')
<div class="container">
    <div class="row mt-5">
        <div class="col-md-8 mx-auto">
            <h1>{{ $detail->judul_kegiatan }}</h1>
            <div class="card mb-3 card-medium">
                <img src="{{ asset($detail->foto) }}" class="card-img-top img-fluid card-img" alt="Foto">
                <div class="card-body">
                    <p class="card-text">{{ $detail->keterangan }}</p>
                    <p class="card-text"><small class="text-body-secondary">Waktu Pelaksanaan {{ $detail->waktu }}</small></p>
                            <a class="btn btn-secondary" href="{{ route('monitoringkegiatan') }}" role="button"><i class="lni lni-arrow-left-circle"></i>  Back</a>
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
