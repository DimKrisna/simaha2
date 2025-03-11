@extends('sidebarfakultassaintek')
@section('content')
    <div class="container">
        <h1 class="text-center mt-5 mb-4">List Proposal Eksidentil Ormawa</h1>

    <form method="GET" action="{{ route('proposalkegiataninsidentilFST') }}" class="d-flex justify-content-left mb-3">
        <div class="input-group" style="max-width: 300px; gap: 8px;">
            <input type="text" name="search" class="form-control" placeholder="Cari..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary">Cari</button>
            <a href="{{ route('proposalkegiataninsidentilFST') }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>

        @if ($proposal->isEmpty())
            <div class="alert alert-danger mt-4" role="alert">
                Belum Ada Proposal yang diajukan
            </div>
        @else

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
                                    href="{{ route('detail_proposal_fakultas', ['id_proposal' => $data->id_proposal]) }}"
                                    role="button">Lihat</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
