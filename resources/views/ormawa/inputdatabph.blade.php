@extends('sidebaradmin')
@section('content')
    <div class="d-flex justify-content-center">
        <div class="col-md-6 ">
            <h2 class="mb-4 mt-3">Data Kepengurusa Inti Organisasi</h2>

            @if ($errors->any())
                @foreach ($errors->all() as $err)
                    <p class="alert alert-danger">{{ $err }}</p>
                @endforeach
            @endif

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <form action="{{ route('inputdatabph') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label for="nama_ormawa" class="form-label">Nama Ormawa <span class="text-danger">*</span></label>
                    <select class="form-control" name="nama_ormawa" id="nama_ormawa">
                        <option value="">Pilih Nama Ormawa</option>
                        @foreach ($ormawas as $ormawa)
                            <option value="{{ $ormawa->id_ormawa }}">{{ $ormawa->nama_ormawa }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="id_ormawa" class="form-label">ID Ormawa <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" name="id_ormawa" id="id_ormawa" readonly />
                </div>
                
                <div class="mb-3">
                    <label for="nama" class="form-label">Nama Mahasiswa <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" name="nama_mahasiswa" id="nama_mahasiswa" />
                </div>

                <div class="mb-3">
                    <label for="npm_mahasiswa" class="form-label">NPM Mahasiswa <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" name="npm" id="npm" />
                </div>

                <div class="mb-3">
                    <label for="jabatan" class="form-label">Jabatan <span class="text-danger">*</span></label>
                    <select class="form-control" name="jabatan" id="jabatan">
                        <option value="">Pilih Jabatan</option>
                        @foreach ($jabatan as $id => $nama)
                            <option value="{{ $id }}">{{ $nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-primary"><i class="lni lni-save"
                            style="vertical-align: middle; margin-right: 2px;"></i>
                        <span style="vertical-align: middle;">Simpan</span></button>
                    <a class="btn btn-danger mx-2" href="{{ url()->previous() }}"><i
                            class="lni lni-arrow-left-circle"style="vertical-align: middle;"></i><span
                            style="vertical-align: middle; margin-left: 2px;">Batal</span></a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Mengatur nilai id_ormawa secara otomatis berdasarkan pilihan nama_ormawa
        document.getElementById('nama_ormawa').addEventListener('change', function() {
            var selectedOption = this.options[this.selectedIndex];
            var idOrmawaInput = document.getElementById('id_ormawa');
            idOrmawaInput.value = selectedOption.value;
        });
    </script>
@endsection
