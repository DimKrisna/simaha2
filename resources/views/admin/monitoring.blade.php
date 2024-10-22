@extends('sidebaradmin')

@section('content')
<div class="container">
    <div class="row mt-3">
        <div class="col-4">
            @if(session('message'))
                <p class="alert alert-success">{{ session('message') }}</p>
            @endif
            @if(session('error'))
                <p class="alert alert-danger">{{ session('error') }}</p>
            @endif
        </div>
    </div>
    
    <!-- Tombol Back -->
    <div class="row mt-3">
        <div class="col-8">
            <a class="btn btn-secondary" href="{{ url()->previous() }}" role="button"><i class="lni lni-arrow-left-circle"></i> Back</a>
        </div>
    </div>

    <!-- Tampilkan Daftar Kegiatan -->
    @if(!$data->isEmpty())
        <div class="container">
            <h1 class="text-center mt-5 mb-4">Daftar Kegiatan</h1>
            <div class="row">
                <div class="col-10 mx-auto">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Kegiatan</th>
                                <th scope="col">Lihat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $kegiatan)
                                <tr>
                                    <td>{{ $kegiatan->judul_kegiatan }}</td>
                                    <td><a class="btn btn-success" href="{{ route('detailmonitor', ['id' => $kegiatan->id_monitoring]) }}" role="button">Detail</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <!-- Jika Belum Ada Kegiatan yang Dilaporkan -->
        <div class="row mt-3">
            <div class="col-10 mx-auto">
                <p class="alert alert-danger">Belum ada kegiatan yang dilaporkan.</p>
            </div>
        </div>
    @endif
</div>
@endsection
