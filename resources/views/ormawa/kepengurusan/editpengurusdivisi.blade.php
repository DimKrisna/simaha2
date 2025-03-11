@extends('sidebarormawa')

@section('content')
    <div class="d-flex justify-content-center">
        <div class="col-md-6">
            <h2 class="mb-4 mt-3">Edit Data Kepengurusan Divisi</h2>

            @if ($errors->any())
                @foreach ($errors->all() as $err)
                    <p class="alert alert-danger">{{ $err }}</p>
                @endforeach
            @endif

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form action="{{ route('kepengurusan-divisi.update') }}" method="POST">
                @csrf
                <input type="hidden" name="id_detail_kepengurusan" value="{{ $data->id_detail_kepengurusan }}">

                <div class="mb-3">
                    <label for="nama_mahasiswa" class="form-label">Nama Mahasiswa <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" name="nama_mahasiswa" id="nama_mahasiswa" value="{{ $data->nama_mahasiswa }}" />
                </div>

                <div class="mb-3">
                    <label for="npm_mahasiswa" class="form-label">NPM Mahasiswa <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" name="npm" id="npm" value="{{ $data->npm }}" />
                </div>

                <div class="mb-3">
                    <label for="id_divisi" class="form-label">Divisi <span class="text-danger">*</span></label>
                    <select class="form-control" name="id_divisi" id="id_divisi">
                        <option value="">Pilih Divisi</option>
                        @foreach($divisi as $id => $nama)
                            <option value="{{ $id }}" {{ $data->id_divisi == $id ? 'selected' : '' }}>{{ $nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="jabatan" class="form-label">Jabatan <span class="text-danger">*</span></label>
                    <select class="form-control" name="jabatan" id="jabatan">
                        <option value="">Pilih Jabatan</option>
                        @foreach($jabatan as $id => $nama)
                            <option value="{{ $id }}" {{ $data->jabatan == $id ? 'selected' : '' }}>{{ $nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-primary"><i class="lni lni-save" style="vertical-align: middle; margin-right: 2px;"></i>
                    <span style="vertical-align: middle;">Update</span></button>
                    <a class="btn btn-danger mx-2" href="{{ route('datapengurusormawa') }}"><i class="lni lni-arrow-left-circle" style="vertical-align: middle;"></i><span style="vertical-align: middle; margin-left: 2px;">Batal</span></a>
                </div>
            </form>
        </div>
    </div>
@endsection
