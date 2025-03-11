@extends('sidebarprodi')

@section('content')
    <div class="container">
        {{-- Notifikasi Sukses atau Error --}}
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

        {{-- Form Input Data Laporan --}}
        <div class="form-container mt-4">
            <h1>Input Data Laporan</h1>
            <form action="{{ route('updatelaporanprodi', ['id' => $laporan->id_laporan]) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Jenis Laporan --}}
                <div class="form-group">
                    <label for="jenis_laporan">Jenis Laporan:</label>
                    <input type="text" id="jenis_laporan" name="jenis_laporan" class="form-control"
                        value="{{ $laporan->jenis_laporan }}" readonly>
                </div>

                {{-- Judul Kegiatan --}}
                <div class="form-group">
                    <label for="judul_kegiatan">Judul Kegiatan:</label>
                    <input type="text" id="judul_kegiatan" name="judul_kegiatan" class="form-control"
                        value="{{ $laporan->judul_kegiatan }}" required>
                </div>

                {{-- Rencana Kegiatan --}}
                <div class="form-group">
                    <label for="rencana_kegiatan">Rencana Kegiatan:</label>
                    <textarea id="rencana_kegiatan" name="rencana_kegiatan" rows="4" class="form-control" required>{{ $laporan->rencana_kegiatan }}</textarea>
                </div>

                {{-- Realisasi Kegiatan --}}
                <div class="form-group">
                    <label for="relasi_kegiatan">Realisasi Kegiatan:</label>
                    <textarea id="relasi_kegiatan" name="relasi_kegiatan" rows="4" class="form-control" required>{{ $laporan->relasi_kegiatan }}</textarea>
                </div>

                {{-- Evaluasi --}}
                <div class="form-group">
                    <label for="evaluasi">Evaluasi:</label>
                    <textarea id="evaluasi" name="evaluasi" rows="4" class="form-control" required>{{ $laporan->evaluasi }}</textarea>
                </div>

                {{-- Penggunaan Dana --}}
                <div class="form-group">
                    <label for="penggunaan_dana">Penggunaan Dana:</label>
                    <textarea id="penggunaan_dana" name="penggunaan_dana" rows="4" class="form-control" required>{{ $laporan->penggunaan_dana }}</textarea>
                </div>

                {{-- Penutup --}}
                <div class="form-group">
                    <label for="penutup">Penutup:</label>
                    <textarea id="penutup" name="penutup" rows="4" class="form-control" required>{{ $laporan->penutup }}</textarea>
                </div>
                
                <!-- Lampiran -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="lampiran">Lampiran :</label>
                        <a href="{{ route('showlampiran', $laporan->id_laporan) }}" class="form-control text-primary" readonly>{{ $laporan->lampiran }}</a>
                    </div>
                </div><br>

                {{-- Status Kemahasiswaan --}}
                <div class="form-group mb-4 p-3 border rounded shadow-sm bg-light">
                    <label for="status_kaprodi" class="form-label">Status Prodi:</label>
                    <select class="form-select" id="status_kaprodi" name="status_kaprodi" required>
                        <option value="" disabled selected>---</option>
                        <option value="Acc">Acc</option>
                        <option value="Revisi">Revisi</option>
                        <option value="Tolak">Tolak</option>
                    </select>
                </div>

                {{-- Catatan Prodi --}}
                <div class="form-group">
                    <label for="catatan_prodi" class="form-label">Catatan Rektor:</label>
                    <textarea name="catatan_prodi" id="catatan_prodi" class="form-control" rows="4">{{ $laporan->catatan_prodi }}</textarea>
                </div>

                {{-- Tombol --}}
                <div class="d-flex justify-content-end gap-3 mt-4">
                    <a class="btn btn-danger" href="{{ route('ListLaporanRektor') }}">Back</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="lni lni-save me-2"></i>Update
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
