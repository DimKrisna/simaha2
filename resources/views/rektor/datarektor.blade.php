@extends('sidebarrektor')
@section('content')
<div class="container">
        <div class="row mt-5">
            <div class="col-8">
            </div>
        </div>
        <h1 class="text-center mt-5 mb-4">Data HIMA</h1>
        <div class="col-4">
        </div><br>
        <table class="table table-striped centered">
            <thead>
                <tr>
                    <th scope="col">Id Ormawa</th>
                    <th scope="col">Nama Panjang</th>
                    <th scope="col">Nama Singkatan</th>
                    <th scope="col">Data Lengkap</th>
                </tr>
            </thead>
            <tbody>
            @foreach($data as $ormawa)
            @if ((int)($ormawa->id_ormawa / 1000) === 1)
                <tr>
                    <td>{{ $ormawa->id_ormawa}}</td>
                    <td>{{ $ormawa->nama_ormawa }}</td>
                    <td>{{ $ormawa->nama_singkatan }}</td>
                    <td><a href="{{ route('informasiRektor', $ormawa->id_ormawa) }}" class="btn btn-success">Informasi</a></td>
                </tr>
            @endif
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="container">
        <h1 class="text-center mt-5 mb-4">Data UKM</h1>
        <div class="col-4">
        </div><br>
        <table class="table table-striped centered">
            <thead>
                <tr>
                    <th scope="col">Id Ormawa</th>
                    <th scope="col">Nama Panjang</th>
                    <th scope="col">Nama Singkatan</th>
                    <th scope="col">Data Lengkap</th>
                </tr>
            </thead>
            <tbody>
            @foreach($data as $ormawa)
            @if ((int)($ormawa->id_ormawa / 1000) === 2)
                <tr>
                    <td>{{ $ormawa->id_ormawa }}</td>
                    <td>{{ $ormawa->nama_ormawa }}</td>
                    <td>{{ $ormawa->nama_singkatan }}</td>
                    <td><a href=<a href="{{ route('informasiRektor', $ormawa->id_ormawa) }}" class="btn btn-success">Informasi</a></td>
                </tr>
            @endif
@endforeach
            </tbody>
        </table>
    </div>
@endsection
