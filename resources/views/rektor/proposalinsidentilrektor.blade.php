@extends('sidebarrektor')

@section('content')
    <div class="container">
        <h1 class="text-center mt-5 mb-4">List Proposal Kegiatan Insidentil Ormawa</h1>
        <form method="GET" action="{{ route('proposalinsidentilrektor') }}" class="d-flex justify-content-left mb-3">
            <div class="input-group" style="max-width: 300px; gap: 8px;">
                <input type="text" name="search" class="form-control" placeholder="Cari..."
                    value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">Cari</button>
                <a href="{{ route('proposalinsidentilrektor') }}" class="btn btn-secondary">Reset</a>
            </div>
        </form>

        <br>


        @if ($proposal->isEmpty())
            <div class="alert alert-danger mt-4" role="alert">
                Belum Ada Proposal yang diajukan
            </div>
        @else
            <div class="col-4">
            </div><br>
            <table class="table table-striped centered">
                <thead>
                    <tr>
                        <th scope="col">Judul Proposal</th>
                        <th scope="col">Nama Ormawa</th>
                        <th scope="col">Tanggal Pengajuan</th>
                        <th scope="col">Detail Proposal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($proposal as $data)
                        <tr>
                            <td>{{ $data->judul_kegiatan }}</td>
                            <td>{{ $data->nama_ormawa }}</td>
                            <td>{{ $data->tanggal_pengajuan }}</td>
                            <td><a href="{{ route('tampilpropinsidentilrektor', Crypt::encryptString($data->id_proposal)) }}"
                                    class="btn btn-primary"><i class="lni lni-eye" style="vertical-align: middle;"></i>
                                    <span style="vertical-align: middle; margin-left: 2px;">Detail</span></a>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
