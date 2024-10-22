@extends('sidebarrektor')

@section('content')
    <div class="container mt-4">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="form-container mt-4">
            <h1>Input Data Laporan</h1>
            <form action="{{ route('updatelaporan', $laporan->id_laporan) }}" method="POST">
                @csrf
                @method('PUT')

                <label for="jenis_laporan">Jenis Laporan:</label>
                <select name="jenis_laporan" id="jenis_laporan" class="form-control">
                    <option value="LPJ" selected>LPJ</option>
                </select>

                <!-- Tampilkan data dari query -->
                <label for="judul_kegiatan">Judul Kegiatan:</label>
                <input type="text" id="judul_kegiatan" name="judul_kegiatan" class="form-control" value="{{ $laporan->judul_kegiatan }}" required>

                <label for="rencana_kegiatan">Rencana Kegiatan:</label>
                <textarea id="rencana_kegiatan" name="rencana_kegiatan" rows="4" class="form-control" required>{{ $laporan->rencana_kegiatan }}</textarea>

                <label for="relasi_kegiatan">Relasi Kegiatan:</label>
                <textarea id="relasi_kegiatan" name="relasi_kegiatan" rows="4" class="form-control" required>{{ $laporan->relasi_kegiatan }}</textarea>

                <label for="evaluasi">Evaluasi:</label>
                <textarea id="evaluasi" name="evaluasi" rows="4" class="form-control" required>{{ $laporan->evaluasi }}</textarea>

                <label for="penggunaan_dana">Penggunaan Dana:</label>
                <textarea id="penggunaan_dana" name="penggunaan_dana" rows="4" class="form-control" required>{{ $laporan->penggunaan_dana }}</textarea>

                <label for="penutup">Penutup:</label>
                <textarea id="penutup" name="penutup" rows="4" class="form-control" required>{{ $laporan->penutup }}</textarea>
                <!-- Akhir dari tampilan data dari query -->

                 <!-- Tombol Submit dan Revisi -->
                    <div class="button-container">
                        <a class="btn btn-danger" href="{{ route('laporankegiatanwr3') }}" role="button"><i
                                class="lni lni-arrow-left-circle"></i> Back</a>
                        <button type="submit" class="btn btn-secondary" name="revisi" value="1"><i class=""></i>Revisi</button>
                    </div>
            </form>
            <form action="{{ route('acclaporankegiatanwr3', $laporan->id_laporan) }}" method="POST" class="mt-3">
                @csrf
                <button type="submit" class="btn btn-primary"><i class=""></i>ACC</button>
            </form>
        </div>
    </div>
@endsection
