@extends('sidebarprodi')

@section('content')
    <div class="container">
        @if ($laporan->isEmpty())
            <div class="alert alert-danger mt-4" role="alert">
                Belum Ada Laporan Tahunan yang diajukan
            </div>
        @else
            <h1 class="text-center mt-5 mb-4">List Laporan Tahunan Ormawa</h1>
            <div class="col-4">
            </div><br>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Judul Laporan</th>
                        <th scope="col">Nama Ormawa</th>
                        <th scope="col">Detail Laporan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($laporan as $data)
                        <tr>
                            <td>{{ $data->judul_kegiatan }}</td>
                            <td>{{ $data->nama_ormawa }}</td>
                            <td><a class="btn btn-success"
                                    href="{{ route('showDetailLaporan', ['id_laporan' => $data->id_laporan]) }}"role="button">Lihat</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                {{ $laporan->links('vendor.pagination') }}
        @endif
    </div>
@endsection
