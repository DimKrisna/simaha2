@extends('sidebarprodi')

@section('content')
    <!-- Data Kepengurusan Inti -->
    <div class="container border">
        <h1 class="text-center mt-5 mb-4">Data Kepengurusan Inti</h1>
        <table class="table table-striped border">
            <thead>
                <tr>
                    <th scope="col">Nama Mahasiswa</th>
                    <th scope="col">NPM</th>
                    <th scope="col">Jabatan</th>
                    <th scope="col">Periode</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($databph as $data)
                    <tr>
                        <td>{{ $data->nama_mahasiswa }}</td>
                        <td>{{ $data->npm }}</td>
                        <td>{{ $data->nama_jabatan }}</td>
                        <td>{{ $data->periode }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <br>

    <!-- Data Kepengurusan Divisi -->
    <div class="container border">
        <h1 class="text-center mt-5 mb-4">Data Kepengurusan Divisi</h1>
        <table class="table table-striped border">
            <thead>
                <tr>
                    <th scope="col">Nama Mahasiswa</th>
                    <th scope="col">NPM</th>
                    <th scope="col">Jabatan</th>
                    <th scope="col">Divisi</th>
                    <th scope="col">Periode </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($datadivisi as $data)
                    <tr>
                        <td>{{ $data->nama_mahasiswa }}</td>
                        <td>{{ $data->npm }}</td>
                        <td>{{ $data->nama_jabatan }}</td>
                        <td>{{ $data->nama_divisi }}</td>
                        <td>{{ $data->periode }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="d-flex justify-content-center">
            {{ $datadivisi->appends(request()->except('divisiPage'))->links('vendor.pagination') }}
        </div>
        <br>
    </div>
    <br>

    <!-- Data Program Kerja -->
    <div class="container border">
        <h1 class="text-center mt-5 mb-4">Data Program Kerja</h1>
        <table class="table table-striped border">
            <thead>
                <tr>
                    <th scope="col">Nama Kegiatan</th>
                    <th scope="col">Peran Organisasi</th>
                    <th scope="col">Jenis Kegiatan</th>
                    <th scope="col">Estimasi Anggaran</th>
                    <th scope="col">Status Pelaksanaan</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($prokers as $data)
                    <tr>
                        <td>{{ $data->nama_kegiatan }}</td>
                        <td>{{ $data->peran_ormawa }}</td>
                        <td>{{ $data->jenis_kegiatan }}</td>
                        <td>{{ $data->estimasi_anggaran }}</td>
                        <td>{{ $data->status }}</td>
                        <td><a href="{{ route('datadetailproker', ['id_proker' => $data->id_proker]) }}" class="btn btn-success">
                            <i class="lni lni-eye"></i> Detail</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="d-flex justify-content-center">
            {{ $prokers->appends(request()->except('prokerPage'))->links('vendor.pagination') }}
        </div>
        <br>
    </div>
    <br>
    <br>
@endsection
