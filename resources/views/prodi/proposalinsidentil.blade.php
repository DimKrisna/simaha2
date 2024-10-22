@extends('sidebarprodi')
@section('content')
    <div class="container">
        @if ($proposal->isEmpty())
            <div class="alert alert-danger mt-4" role="alert">
                Belum Ada Proposal yang diajukan
            </div>
        @else
            <h1 class="text-center mt-5 mb-4">List Proposal Kegiatan Ormawa</h1>
            <div class="col-4">
            </div><br>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Judul Proposal</th>
                        <th scope="col">Nama Ormawa</th>
                        <th scope="col">Detail Proposal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($proposal as $data)
                        <tr>
                            <td>{{ $data->judul_kegiatan }}</td>
                            <td>{{ $data->nama_ormawa }}</td>
                            <td>
                                <a class="btn btn-success"
                                    href="{{ route('detailproposal', ['id_proposal' => $data->id_proposal]) }}"
                                    role="button">Lihat</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
