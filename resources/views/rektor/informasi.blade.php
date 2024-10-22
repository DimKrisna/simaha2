@extends('sidebarrektor')

@section('content')
<div class="container">
    <div class="row mt-3">
        <div class="col">
            <a class="btn btn-secondary" href="{{ url()->previous() }}" role="button"><i class="lni lni-arrow-left-circle"></i> Back</a>
            <h1 class="text-center mt-5 mb-4">Struktur Kepengurusan {{ $nama_singkatan }}</h1>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Jabatan</th>
                        <th>Nama Pengurus</th>
                        <th>Periode</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($jabatan as $data)
                    <tr>
                        <td>{{ $data->nama_jabatan }}</td>
                        <td>{{ $data->nama_mahasiswa }}</td>
                        <td>{{ $data->periode }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <h1 class="text-center mt-5 mb-4">Program Kerja {{ $nama_singkatan }}</h1>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Proker</th>
                        <th>Status Terlaksana</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($informasi as $data)
                    <tr>
                        <td>{{ $data->nama_kegiatan }}</td>
                        <td>
                            @if($data->status == 'Belum Terlaksana')
                                <button type="button" class="btn btn-danger">Belum Terlaksana</button>
                            @elseif($data->status == 'Terlaksana')
                                <button type="button" class="btn btn-success">Terlaksana</button>
                            @else
                                <span class="text-muted">Status tidak valid</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
