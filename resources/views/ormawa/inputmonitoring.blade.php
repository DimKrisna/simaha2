@extends('sidebarormawa')

@section('content')
    <div class="container mt-3">
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

        @if ($kegiatans->isEmpty())
            <div class="alert alert-danger mt-4" role="alert">
                Belum Ada kegiatan yang Di ajukan berlangsung
            </div>
        @else
            <div class="form-container mt-4">
                <h1>Input Data Kegiatan Berjalan</h1>
                <form action="{{ route('inputmonitoring') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <label for="jenis_laporan">Kegiatan :</label>
                    <select name="jenis_laporan" id="jenis_laporan" class="form-control">
                        <option value="" selected disabled>--Pilih Kegiatan--</option>
                        @foreach ($kegiatans as $kegiatan)
                            <option value="{{ $kegiatan->id_proposal }}">{{ $kegiatan->judul_kegiatan }}</option>
                        @endforeach
                    </select>

                    <label for="tanggal">Tanggal :</label>
                    <input type="date" id="tanggal" name="tanggal" class="form-control" required>

                    <label for="rencana_kegiatan">Keterangan Kegiatan:</label>
                    <textarea id="rencana_kegiatan" name="rencana_kegiatan" rows="4" class="form-control" required></textarea>

                    <label for="foto_kegiatan">Foto Kegiatan:</label>
                    <input type="file" id="foto_kegiatan" name="foto_kegiatan" class="form-control-file" required>

                    <div class="button-container mt-3">
                        <button id="submit_button" type="submit" class="btn btn-primary">
                            <i class="lni lni-save" style="vertical-align: middle; margin-right: 2px;"></i>
                            <span style="vertical-align: middle;">Simpan</span>
                        </button>
                        <button type="button" class="btn btn-danger float-left" onclick="goBack()">
                            <i class="lni lni-arrow-left-circle" style="vertical-align: middle; margin-right: 2px;"></i>
                            <span style="vertical-align: middle;">Batal</span>
                        </button>
                    </div>
                </form>
            </div>
        @endif
    </div>
@endsection

<script>
    function goBack() {
        window.history.back();
    }
</script>
