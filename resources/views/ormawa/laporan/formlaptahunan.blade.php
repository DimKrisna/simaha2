@extends('sidebarormawa')

@section('content')
    <div class="container">
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

        <div class="form-container">
            <h1>Input Data Laporan</h1>
            <form action="{{ route('input_laporanLPJ') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label for="jenis_laporan">Jenis Laporan:</label>
                <select name="jenis_laporan" id="jenis_laporan" class="form-control">
                    <option value="Tahunan" selected>Tahunan</option>
                </select>

                <label for="judul_kegiatan">Judul Kegiatan:</label>
                <input type="text" id="judul_kegiatan" name="judul_kegiatan" class="form-control" required>

                <label for="rencana_kegiatan">Rencana Kegiatan:</label>
                <textarea id="rencana_kegiatan" name="rencana_kegiatan" rows="4" class="form-control" required></textarea>

                <label for="relasi_kegiatan">Relasi Kegiatan:</label>
                <textarea id="relasi_kegiatan" name="relasi_kegiatan" rows="4" class="form-control" required></textarea>

                <label for="evaluasi">Evaluasi:</label>
                <textarea id="evaluasi" name="evaluasi" rows="4" class="form-control" required></textarea>

                <label for="penggunaan_dana">Penggunaan Dana:</label>
                <textarea id="penggunaan_dana" name="penggunaan_dana" rows="4" class="form-control" required></textarea>

                <label for="penutup">Penutup:</label>
                <textarea id="penutup" name="penutup" rows="4" class="form-control" required></textarea>

                <label for="lampiran">Lampiran: *file Pdf</label>
                <input type="file" name="lampiran" id="lampiran" accept="application/pdf" class="form-control-file" required><br>

                 <!-- Tombol Submit dan Revisi -->
                    <div class="button-container">
                        <button type="submit" class="btn btn-primary"><i class=""></i>Submit</button>
                        <a class="btn btn-danger" href="{{ url()->previous() }}" role="button"><i
                                class="lni lni-arrow-left-circle"></i> Back</a>
                        <a class="btn btn-secondary" href="#" role="button"><i class="lni lni-arrow-left-circle"></i>
                            Revisi</a>
                    </div>
            </form>
        </div>
    </div>
@endsection
