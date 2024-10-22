@extends('sidebaradmin')
@section('content')
    <div class="d-flex justify-content-center">
        <div class="col-md-6 ">
            <h2 class="mb-4 mt-3">Input Periode</h2>

            @if ($errors->any())
                @foreach ($errors->all() as $err)
                    <p class="alert alert-danger">{{ $err }}</p>
                @endforeach
            @endif

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <form action="{{ route('store.kepengurusan') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="nama_ormawa" class="form-label">Nama Ormawa <span class="text-danger">*</span></label>
                    <select class="form-control" name="nama_ormawa" id="nama_ormawa">
                        <option value="">Pilih Ormawa</option>
                        @foreach ($ormawas as $ormawa)
                            <option value="{{ $ormawa->id_ormawa }}">{{ $ormawa->nama_ormawa }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="periode">Id Ormawa</label>
                    <input class="form-control" type="text" name="id_ormawa" id="id_ormawa" />
                </div>
                <div class="mb-3">
                    <label for="periode">Periode</label>
                    <input class="form-control" type="text" name="periode" id="periode" />
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
        document.addEventListener('DOMContentLoaded', function() {
            var namaOrmawaDropdown = document.getElementById('nama_ormawa');
            var idOrmawaInput = document.getElementById('id_ormawa');

            // Event listener untuk perubahan pada dropdown nama_ormawa
            namaOrmawaDropdown.addEventListener('change', function() {
                var selectedOption = this.options[this.selectedIndex];
                // Mengisi nilai input id_ormawa dengan nilai dari atribut value option yang dipilih
                idOrmawaInput.value = selectedOption.value;
            });
        });
    </script>

@endsection
