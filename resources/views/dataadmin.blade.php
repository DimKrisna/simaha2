@extends('sidebaradmin')
@section('content')
    <div class="container">
        <h1 class="text-center mt-5 mb-4">Data HIMA</h1>
        <div class="col-4">
            <a href="#" class="btn btn-primary" id="btnTambahHIMA">Tambah Data</a>
        </div><br>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">Nama Singkatan</th>
                    <th scope="col">Nama Panjang</th>
                    <th scope="col">Data Lengkap</th>
                    <th scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @foreach($ormawas as $ormawa)
            @if ((int)($ormawa->id_ormawa / 100) === 1)
                <tr>
                    <td>{{ $ormawa->nama_singkatan }}</td>
                    <td>{{ $ormawa->nama_ormawa }}</td>
                    <td><button type="button" class="btn btn-success">Informasi</button></td>
                    <td><button type="button" class="btn btn-warning"><i class="lni lni-pencil"></i></button>
                        <button type="button" class="btn btn-danger"><i class="lni lni-trash-can"></i></button>
                    </td>
                </tr>
            @endif
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="container">
        <h1 class="text-center mt-5 mb-4">Data UKM</h1>
        <div class="col-4">
            <a href="#formTambah" class="btn btn-primary" id="btnTambah">Tambah Data</a>
        </div><br>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">Nama Singkatan</th>
                    <th scope="col">Nama Panjang</th>
                    <th scope="col">Data Lengkap</th>
                </tr>
            </thead>
            <tbody>
            @foreach($ormawas as $ormawa)
            @if ((int)($ormawa->id_ormawa / 100) === 2)
                <tr>
                    <td>{{ $ormawa->nama_singkatan }}</td>
                    <td>{{ $ormawa->nama_ormawa }}</td>
                    <td><button type="button" class="btn btn-success">Informasi</button></td>
                    <td><button type="button" class="btn btn-warning"><i class="lni lni-pencil"></i></button>
                    <button type="button" class= "btn btn-danger"><i class="lni lni-trash-can"></i></button>
                    </td>
                </tr>
            @endif
                @endforeach    
            </tbody>
        </table>
    </div>
    <!--Form untuk memasukkan data ormawa-->
<div id="formTambah" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Form Tambah Data</h2>
        <form id="formTambahData">
            <div class="form-group">
                <label for="idOrmawa">ID Ormawa:</label>
                <input type="text" class="form-control" id="idOrmawa" name="idOrmawa">
            </div>
            <div class="form-group">
                <label for="namaOrmawa">Nama Ormawa:</label>
                <input type="text" class="form-control" id="namaOrmawa" name="namaOrmawa">
            </div>
            <div class="form-group">
                <label for="singkatan">Singkatan:</label>
                <input type="text" class="form-control" id="singkatan" name="singkatan">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</div>

@endsection