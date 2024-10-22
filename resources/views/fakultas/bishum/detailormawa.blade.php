@extends('sidebarfakultasbishum')

@section('content')
    <div class="container">
        <div class="row mt-3">
            <div class="col">
                <a class="btn btn-danger" href="{{ route('ormawafst')}}" role="button"><i
                        class="lni lni-arrow-left-circle" style="vertical-align: middle;"></i>
                        <span style="vertical-align: middle; margin-left: 2px;"></i> Back</span></a>
                <h1 class="text-center mt-5 mb-4">Struktur Kepengurusan {{ $nama_singkatan }}</h1>
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

                <br>
            </div>
            <br>
            <br>
            <div class="container border">
                <h1 class="text-center mt-5 mb-4">Data Kepengurusan Divisi</h1>
                <div class="col-4">
                </div>
                <br>
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
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-center">
                    {{ $datadivisi->links('vendor.pagination') }}
                </div>
                <br>
            </div>
        </div>
    </div>
    </div>
@endsection
