@extends('sidebaradmin')

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

        <div class="form-container mt-4">
            <h1> Data Proposal Kegiatan</h1>
            <form>
                @csrf

                <label for="tema">Id Proposal:</label>
                <input type="text" id="tema" name="tema" value="{{ $proposal->id_proposal }}">

                <label for="tema">Tema:</label>
                <input type="text" id="tema" name="tema" value="{{ $proposal->tema }}">

                <label for="judul_kegiatan">Judul Kegiatan:</label>
                <input type="text" id="judul_kegiatan" name="judul_kegiatan" value="{{ $proposal->judul_kegiatan }}">

                <label for="judul_kegiatan">Jenis Proposal:</label>
                <input type="text" id="jenis_proposal" name="jenis_proposal" value="{{ $proposal->jenis_proposal }}" readonly>

                <label for="latar_belakang">Latar Belakang:</label>
                <textarea id="latar_belakang" name="latar_belakang" rows="4">{{ $proposal->latar_belakang }}</textarea>

                <label for="deskripsi_kegiatan">Deskripsi Kegiatan:</label>
                <textarea id="deskripsi_kegiatan" name="deskripsi_kegiatan" rows="4">{{ $proposal->deskripsi_kegiatan }}</textarea>

                <label for="tujuan_kegiatan">Tujuan Kegiatan:</label>
                <textarea id="tujuan_kegiatan" name="tujuan_kegiatan" rows="4">{{ $proposal->tujuan_kegiatan }} </textarea>

                <label for="manfaat_kegiatan">Manfaat Kegiatan:</label>
                <textarea id="manfaat_kegiatan" name="manfaat_kegiatan" rows="4">{{ $proposal->manfaat_kegiatan }} </textarea>

                <label for="tempat_pelaksanaan">Tempat Pelaksanaan:</label>
                <input type="text" id="tempat_pelaksanaan" name="tempat_pelaksanaan"
                    value="{{ $proposal->tempat_pelaksanaan }}">

                <label for="anggaran_kegiatan">Anggaran Kegiatan:</label>
                <input type="number" id="anggaran_kegiatan" name="anggaran_kegiatan"
                    value="{{ $proposal->anggaran_kegiatan }}">

                <label for="anggaran_diajukan">Anggaran Diajukan:</label>
                <input type="number" id="anggaran_diajukan" name="anggaran_diajukan"
                    value="{{ $proposal->anggaran_diajukan }}">

                @if ($proposal->waktu_kegiatan)
                    <div class="form-row">
                        <div class="form-group">
                            <label for="waktu_kegiatan">Waktu Kegiatan:</label>
                            <ul>
                                @foreach ($proposal->waktu_kegiatan as $waktu)
                                    <li>
                                        <input type="text"
                                            value="{{ date('d-m-Y', strtotime($waktu->waktu_kegiatan)) }}" readonly>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <div class="folder-container mt-4">
                    <h1>Lampiran Proposal Kegiatan</h1>
                    <ul style="list-style-type: none; padding: 0;">
                        <li style="border: 1px solid black; margin-bottom: 5px; padding: 10px;"><a href="#"
                                download>Lampiran Proposal Kegiatan</a></li>
                    </ul>
                </div>
                <div class="button-container">
                    <!-- Tombol Back -->
                    <a class="btn btn-danger" href="{{ url()->previous() }}" role="button">
                        <i class="lni lni-arrow-left-circle"></i> Back
                    </a>
                </div>
            </form>
              <!-- Tombol ACC -->
                    </div>
    </div>
@endsection
