@extends('sidebarormawa')
@section('content')

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Input Data Program Kerja</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/line-awesome/1.3.0/css/line-awesome.min.css">
        <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    </head>
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
            <h1>Input Data Program Kerja</h1>
            @if ($errors->any())
                <div style="color: red;">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('inputDataProker') }}" method="POST">
                @csrf
                <label for="judul_kegiatan">Judul Kegiatan:</label>
                <input type="text" id="nama_kegiatan" name="nama_kegiatan">

                <label for="uraian_kegiatan">Uraian Kegiatan:</label>
                <textarea id="uraian_kegiatan" name="uraian_kegiatan" rows="4"></textarea>

                <label for="peran_ormawa">Peran Organisasi:</label>
                <select id="peran_ormawa" name="peran_ormawa">
                    <option value="">-- Peranan Organisasi --</option>
                    <option value="Pelaksana">Pelaksana</option>
                    <option value="Peserta">Peserta</option>
                </select>

                <label for="jenis_kegiatan">Jenis Kegiatan:</label>
                <select id="jenis_kegiatan" name="jenis_kegiatan">
                    <option value="Kebidangan">-- Jenis Kegiatan --</option>
                    <option value="Kebidangan">Kebidangan</option>
                    <option value="Unggulan">Unggulan</option>
                    <option value="Wajib">Wajib</option>
                </select>

                <label for="keunggulan">Keunggulan:</label>
                <textarea id="keunggulan" name="keunggulan" rows="4"></textarea>

                <label for="capaian">Capaian:</label>
                <textarea id="capaian" name="capaian" rows="4"></textarea>

                <label for="strategi_sosialisasi">Strategi Sosialisasi:</label>
                <textarea id="strategi_sosialisasi" name="strategi_sosialisasi" rows="4"></textarea>

                <label for="personalia_pelaksana">Personalia Pelaksana:</label>
                <input type="text" id="personalia_pelaksana" name="personalia_pelaksana">

                <label for="estimasi_anggaran">Estimasi Anggaran:</label>
                <input type="number" id="estimasi_anggaran" name="estimasi_anggaran">

                <button type="submit"class="btn btn-primary">Submit</button>

                <!-- Tombol Submit dan Revisi -->
                <div class="button-container">
                    <a class="btn btn-danger" href="#" role="button"><i class="lni lni-arrow-left-circle"></i>
                        Back</a>
                    <button type="submit" class="btn btn-secondary" name="revisi" value="1"><i
                            class=""></i>Revisi</button>
                </div>
            </form>
        </div>
    </div>
@endsection
