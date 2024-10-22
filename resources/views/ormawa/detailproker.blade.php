@extends('sidebarormawa')

@section('content')
    @php
        // Inisialisasi variabel $isEditing dengan nilai false
        $isEditing = false;
    @endphp
    <div class="container">
        <div class="form-container">
            <h1>Detail Program Kerja</h1>
            @if ($proker)
                <form action="#" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-row">
                        <div class="form-group">
                            <label for="nama_kegiatan">Nama Kegiatan:</label>
                            <input type="text" id="nama_kegiatan" name="nama_kegiatan" value="{{ $proker->nama_kegiatan }}" readonly>
                        </div>

                        <div class="form-group">
                            <label for="uraian_kegiatan">Uraian Kegiatan:</label>
                            <textarea id="uraian_kegiatan" name="uraian_kegiatan" rows="4" readonly>{{ $proker->uraian_kegiatan }}</textarea>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="peran_ormawa">Peran Organisasi:</label>
                            @if ($isEditing)
                                <select id="peran_ormawa" name="peran_ormawa">
                                    <option value="">-- Peranan Organisasi --</option>
                                    <option value="Pelaksana">Pelaksana</option>
                                    <option value="Peserta">Peserta</option>
                                </select>
                            @else
                                <input type="text" id="peran_ormawa" name="peran_ormawa" value="{{ $proker->peran_ormawa }}" readonly>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="jenis_kegiatan">Jenis Kegiatan:</label>
                        @if ($isEditing)
                            <select id="jenis_kegiatan" name="jenis_kegiatan">
                                <option value="">-- Jenis Kegiatan --</option>
                                <option value="Kebidangan">Kebidangan</option>
                                <option value="Unggulan">Unggulan</option>
                                <option value="Wajib">Wajib</option>
                            </select>
                        @else
                            <input type="text" id="jenis_kegiatan" name="jenis_kegiatan" value="{{ $proker->jenis_kegiatan }}" readonly>
                        @endif
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="keunggulan">Keunggulan:</label>
                            <textarea id="keunggulan" name="keunggulan" rows="4" readonly>{{ $proker->keunggulan }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="capaian">Capaian:</label>
                            <textarea id="capaian" name="capaian" rows="4" readonly>{{ $proker->capaian }}</textarea>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="strategi_sosialisasi">Strategi Sosialisasi:</label>
                            <textarea id="strategi_sosialisasi" name="strategi_sosialisasi" rows="4" readonly>{{ $proker->strategi_sosialisasi }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="personalia_pelaksana">Personalia Pelaksana:</label>
                            <input type="text" id="personalia_pelaksana" name="personalia_pelaksana" value="{{ $proker->personalia_pelaksana }}" readonly>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="estimasi_anggaran">Estimasi Anggaran:</label>
                            <input type="number" id="estimasi_anggaran" name="estimasi_anggaran" value="{{ $proker->estimasi_anggaran }}" readonly>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <button id="submit_button" type="submit" class="btn btn-secondary">Submit</button>
                            <button type="button" class="btn btn-warning float-left" onclick="enableEditing()">Revisi</button>
                            <button type="button" class="btn btn-danger float-left" onclick="goBack()">Back</button>
                        </div>
                    </div>
                </form>
            @endif <!-- This line was missing -->
        </div>
    </div>
    <script>
        function enableEditing() {
            // Mendapatkan semua elemen input pada form
            var inputs = document.querySelectorAll('input, textarea');

            // Mengubah nilai variabel $isEditing menjadi true
            var isEditing = true;

            // Menghapus atribut readonly dari setiap input
            inputs.forEach(function(input) {
                input.removeAttribute('readonly');
            });

            // Mengubah elemen input teks dengan id "peran_ormawa" menjadi dropdown select
            var peranOrmawaInput = document.getElementById('peran_ormawa');
            var peranOrmawaSelect = document.createElement('select');
            peranOrmawaSelect.id = 'peran_ormawa';
            peranOrmawaSelect.name = 'peran_ormawa';

            // Opsi-opsi untuk dropdown select
            var peranOrmawaOptions = ['-- Peranan Organisasi --', 'Pelaksana', 'Peserta'];

            peranOrmawaOptions.forEach(function(optionText) {
                var option = document.createElement('option');
                option.value = optionText;
                option.text = optionText;
                peranOrmawaSelect.appendChild(option);
            });

            // Mengganti input teks dengan dropdown select
            peranOrmawaInput.parentNode.replaceChild(peranOrmawaSelect, peranOrmawaInput);

            // Mengubah elemen input teks dengan id "jenis_kegiatan" menjadi dropdown select
            var jenisKegiatanInput = document.getElementById('jenis_kegiatan');
            var jenisKegiatanSelect = document.createElement('select');
            jenisKegiatanSelect.id = 'jenis_kegiatan';
            jenisKegiatanSelect.name = 'jenis_kegiatan';

            // Opsi-opsi untuk dropdown select
            var jenisKegiatanOptions = ['-- Jenis Kegiatan --', 'Kebidangan', 'Unggulan', 'Wajib'];

            jenisKegiatanOptions.forEach(function(optionText) {
                var option = document.createElement('option');
                option.value = optionText;
                option.text = optionText;
                jenisKegiatanSelect.appendChild(option);
            });

            // Mengganti input teks dengan dropdown select
            jenisKegiatanInput.parentNode.replaceChild(jenisKegiatanSelect, jenisKegiatanInput);

            // Mengubah warna tombol submit menjadi biru
            document.getElementById('submit_button').classList.remove('btn-secondary');
            document.getElementById('submit_button').classList.add('btn-primary');
        }
    </script>
@endsection
