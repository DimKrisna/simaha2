@extends('sidebarprodi')

@section('content')
    <br>
    <br>
    <div class="container border">
        <h1 class="text-center mt-5 mb-4"> Kepengurusan Inti <b>{{$nama_ormawa}}</b></h1> <!-- Menggunakan $namaOrmawa dari controller -->
        <div class="col-4">
        </div><br>
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
                @foreach($databph as $data)
                    <tr>
                        <td>{{ $data->nama_mahasiswa}}</td>
                        <td>{{ $data->npm}}</td>
                        <td>{{ $data->nama_jabatan}}</td>
                        <td>{{ $data->periode}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <br>
        <hr style="width: 100%; color: navy; height: 3px; background-color:navy;">
        <h1 class="text-center mt-5 mb-4"> Kepengurusan Divisi <b>{{$nama_ormawa}}</b></h1>
        <br>
        <table class="table table-striped border">
            <thead>
                <tr>
                    <th scope="col">Nama Mahasiswa</th>
                    <th scope="col">NPM</th>
                    <th scope="col">Jabatan</th>
                    <th scope="col">Divisi</th>
                    <th scope="col">Periode</th>
                </tr>
            </thead>
            <tbody>
                @foreach($datadivisi as $data)
                    <tr>
                        <td>{{ $data->nama_mahasiswa}}</td>
                        <td>{{ $data->npm}}</td>
                        <td>{{ $data->nama_jabatan}}</td>
                        <td>{{ $data->nama_divisi}}</td>
                        <td>{{ $data->periode}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <br>
        <div class="d-flex justify-content-center">
            {{ $datadivisi->links('vendor.pagination') }}
        </div>
        <br>
    </div>
@endsection
