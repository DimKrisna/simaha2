@extends('sidebarfakultasbishum')

@section('content')
<br>
<br>
<div class="container border">
    <h1 class="text-center mt-5 mb-4">Data Himpunan Mahasiswa Fakultas Bisnis & Humaniora</h1>
    <div class="col-4">
    </div>
    <br><br>
    <table class="table table-striped border  table-bordered text-center">
        <thead>
            <tr>
                <th scope="col" class="text-center">Nama Himpunan</th>
                <th scope="col" class="text-center">Nama Singkatan</th>
                <th scope="col" class="text-center">Nama Ketua</th>
                <th scope="col" class="text-center">Periode</th>
                <th scope="col" class="text-center">Informasi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($databishum as $data)
            <tr>
                <td class="text-center">{{ $data->nama_ormawa }}</td>
                <td class="text-center">{{ $data->nama_singkatan }}</td>
                <td class="text-center">{{ $data->npm }} - {{ $data->nama_mahasiswa }}</td>
                <td class="text-center">{{ $data->periode }}</td>
                <td>
                    <a href="{{ route('datakepengurusanormawaFakultas', Crypt::encryptString($data->id_ormawa)) }}" class="btn btn-primary">
                        <i class="lni lni-eye" style="vertical-align: middle;"></i>
                        <span style="vertical-align: middle; margin-left: 2px;">Detail</span>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-center">
        {{ $databishum->links('vendor.pagination') }}
    </div>
    <br>
</div>
<br>
<div class="container border">
    <h1 class="text-center mt-5 mb-4">Data Program Kerja</h1>
    <div class="col-4">
    </div><br>
    <table class="table table-striped border  table-bordered text-center">
        <thead>
            <tr>
                <th scope="col">Nama Kegiatan</th>
                <th scope="col">Nama Ormawa</th>
                <th scope="col">Peran Organisasi</th>
                <th scope="col">Jenis Kegiatan</th>
                <th scope="col">Estimasi Anggaran </th>
                <th scope="col">Status Pelaksanaan </th>
                <th scope="col">Informasi </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($dataProker as $proker)
                <tr>
                    <td>{{ $proker->nama_kegiatan }}</td>
                    <td>{{ $proker->nama_ormawa }}</td>
                    <td>{{ $proker->peran_ormawa }}</td>
                    <td>{{ $proker->jenis_kegiatan }}</td>
                    <td>{{ $proker->estimasi_anggaran }}</td>
                    <td>{{ $proker->status }}</td>
                    <td><a href="{{ route('detailProkerFakultas', Crypt::encryptString($proker->id_proker)) }}" class="btn btn-primary"><i class="lni lni-eye"
                                style="vertical-align: middle;"></i>
                            <span style="vertical-align: middle; margin-left: 2px;">Detail</span></a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-center">
        {{ $databishum->links('vendor.pagination') }}
    </div>
    <br>
</div>
<br>
@endsection
