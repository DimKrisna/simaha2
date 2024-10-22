@extends('sidebarormawa')
@section('content')
    <div class="container">
        <h1 class="text-center mt-5 mb-4">Data Pengajuan Proposal</h1>
        <div class="col-4">
        </div><br>
        <table class="table table-striped table-bordered text-center">
            <thead>
                <tr>
                    <th scope="col">Tema Pengajuan</th>
                    <th scope="col">Jenis Proposal</th>
                    <th scope="col">Tempat Pelaksanaan</th>
                    <th scope="col">Anggaran Kegiatan </th>
                    <th scope="col">Informasi </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dataProposal as $data)
                    <tr>
                        <td>{{ $data->tema }}</td>
                        <td>{{ $data->jenis_proposal }}</td>
                        <td>{{ $data->tempat_pelaksanaan }}</td>
                        <td>Rp {{ number_format($data->anggaran_kegiatan, 0, ',', '.') }}</td>
                        <td><a href="{{ route('tampilprop', Crypt::encryptString($data->id_proposal)) }}"
                                class="btn btn-primary"><i class="lni lni-eye" style="vertical-align: middle;"></i>
                                <span style="vertical-align: middle; margin-left: 2px;">Detail</span></a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <br>
        <div class="d-flex justify-content-center">
            {{ $dataProposal->links('vendor.pagination') }}
        </div>
    </div>
@endsection
