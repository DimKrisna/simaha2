@extends('sidebarfakultassaintek')

@section('content')
    <div class="container border">
        <div class="row mt-3">
            <div class="col-8">
                <a class="btn btn-danger" href="{{ url()->previous() }}" role="button"><i class="lni lni-arrow-left-circle"
                        style="vertical-align: middle;"></i> <span style="vertical-align: middle; margin-left: 2px;">Back</span></a>
            </div>
        </div>
        <h1 class="text-center mt-5 mb-4">Detail Program Kerja {{ $nama_singkatan }}</h1>
        <div class="form-container  mt-4">
            <form>
                <div class="form-row">
                    <div class="form-group">
                        <label for="nama_kegiatan">Nama Kegiatan:</label>
                        <input type="text" id="nama_kegiatan" class="form-control" value="{{ $proker->nama_kegiatan }}" readonly>
                    </div>
                    <div class="form-group ">
                        <label for="uraian_kegiatan">Uraian Kegiatan:</label>
                        <textarea id="uraian_kegiatan" class="form-control" rows="4" readonly>{{ $proker->uraian_kegiatan }}</textarea>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group ">
                        <label for="peran_ormawa">Peran Organisasi:</label>
                        <input type="text" id="peran_ormawa" class="form-control" value="{{ $proker->peran_ormawa }}" readonly>
                    </div>
                    <div class="form-group ">
                        <label for="jenis_kegiatan">Jenis Kegiatan:</label>
                        <input type="text" id="jenis_kegiatan" class="form-control" value="{{ $proker->jenis_kegiatan }}" readonly>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group ">
                        <label for="keunggulan">Keunggulan:</label>
                        <textarea id="keunggulan" class="form-control" rows="4" readonly>{{ $proker->keunggulan }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="capaian">Capaian:</label>
                        <textarea id="capaian" class="form-control" rows="4" readonly>{{ $proker->capaian }}</textarea>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group ">
                        <label for="strategi_sosialisasi">Strategi Sosialisasi:</label>
                        <textarea id="strategi_sosialisasi" class="form-control" rows="4" readonly>{{ $proker->strategi_sosialisasi }}</textarea>
                    </div>
                    <div class="form-group ">
                        <label for="personalia_pelaksana">Personalia Pelaksana:</label>
                        <input type="text" id="personalia_pelaksana" class="form-control" value="{{ $proker->personalia_pelaksana }}" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label for="estimasi_anggaran">Estimasi Anggaran:</label>
                    <input type="number" id="estimasi_anggaran" class="form-control" value="{{ $proker->estimasi_anggaran }}" readonly>
                </div>
            </form>
        </div>
    </div>
@endsection
