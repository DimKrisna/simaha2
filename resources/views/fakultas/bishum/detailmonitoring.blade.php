@extends('sidebarfakultasbishum')

@section('content')
    <div class="container border ">
        <div class="row mt-5">
            <div class="col-md-8 mx-auto">
                @foreach ($detail as $item)
                    <h1>{{ $item->judul_kegiatan }}</h1>
                    <div class="card mb-3">
                        <img src="{{ $item->foto }}" class="card-img-top" alt="Foto" style="max-width: 100px;">
                        <div class="card-body">
                            <p class="card-text">{{ $item->keterangan }}</p>
                            <p class="card-text"><small class="text-body-secondary">Waktu Pelaksanaan
                                    {{ $item->waktu }}</small></p>
                                    <br>
                        </div>
                    </div>
                    <a href="{{ route('monitoringkegiatanbishum') }}" class="btn btn-danger"><i class="lni lni-arrow-left-circle"
                        style="vertical-align: middle;"></i> <span style="vertical-align: middle; margin-left: 2px;">Back</span></a>
                    <br><br>
                @endforeach
            </div>
        </div>
    </div>
@endsection
