@extends('sidebarrektor')

@section('content')
    <div class="container">
        @if ($proposal->isEmpty())
            <div class="alert alert-danger mt-4" role="alert">
                Belum Ada Proposal yang diajukan
            </div>
        @else
            <h1 class="text-center mt-5 mb-4">List Proposal Kegiatan Proker Ormawa</h1>
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
                            <td>{{ \Carbon\Carbon::parse($data->tanggal_pengajuan)->translatedFormat('d F Y') }}</td>
                            <td><a href="{{route('tampilpropprokerrektor', Crypt::encryptString($data->id_proposal)) }}"
                                    class="btn btn-primary"><i class="lni lni-eye" style="vertical-align: middle;"></i>
                                    <span style="vertical-align: middle; margin-left: 2px;">Detail</span></a>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
