@extends('sidebarormawa')

@section('content')
    <div class="container">
        <div class="form-container">
            <h1>Detail Laporan Kegiatan</h1>
            @isset($laporan)
                <br>
                <table class="table table-striped table-bordered text-center">
                    <thead>
                        <tr>
                            <th scope="col">Status Prodi</th>
                            <th scope="col">Status Kemahasiswaan</th>
                            <th scope="col">Status Wakil Rektor 3</th>
                            <th scope="col">Status Fakultas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $laporan->statuses ? $laporan->statuses->status_kaprodi : 'N/A' }}</td>
                            <td>{{ $laporan->statuses ? $laporan->statuses->status_kemahasiswaan : 'N/A' }}</td>
                            <td>{{ $laporan->statuses ? $laporan->statuses->status_wr3 : 'N/A' }}</td>
                            <td>{{ $laporan->statuses ? $laporan->statuses->status_dekanat : 'N/A' }}</td>
                        </tr>
                    </tbody>
                </table>
                <br>
                <div class="alert alert-info" role="alert">
                    <strong>Informasi:</strong> Silakan catat nomor registrasi pengesahan untuk dimasukkan dalam lembar pengesahan.
                </div>
                <br>
                <form id="proposal_form" action="#" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-row">
                        <div class="form-group">
                            <label for="id_proposal">No. Registrasi:</label>
                            <input type="text" id="id_laporan" name="id_laporan" value="{{ $laporan->id_laporan }}" readonly>
                        </div>

                        <div class="form-group">
                            <label for="judul">Judul Kegiatan:</label>
                            <input type="text" id="judul" name="judul" value="{{ $laporan->judul_kegiatan }}" readonly>
                        </div>
                    </div>
                    <br>

                        <div class="form-group">
                            <a href="{{ route('download.pengesahan') }}" class="btn btn-primary"><i class="lni lni-download" style="vertical-align: middle;"></i>
                            <span style="vertical-align: middle; margin-left: 2px;">Download Lembar Pengesahan</span></a>
                        </div>

                </form>
                <br>
            @else
                <p>Proposal tidak ditemukan.</p>
            @endisset
        </div>
    </div>
    <br>
@endsection