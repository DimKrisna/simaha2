@extends('sidebarormawa')

@section('content')
    <div class="container">
        <div class="form-container">
            <h1>Detail Laporan</h1>
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if ($laporan)
                <br>
                @if (str_starts_with(auth()->user()->id_ormawa, '1'))
                    <table class="table table-striped table-bordered text-center">
                        <thead>
                            <tr>
                                <th scope="col">Status Prodi</th>
                                <th scope="col">Status Kemahasiswaan</th>
                                <th scope="col">Status Wakil Rektor 3</th>
                                <th scope="col">Status Fakultas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $statuses->status_kaprodi }}</td>
                                <td>{{ $statuses->status_kemahasiswaan }}</td>
                                <td>{{ $statuses->status_wr3 }}</td>
                                <td>{{ $statuses->status_dekanat }}</td>
                            </tr>
                        </tbody>
                    </table>
                @elseif (str_starts_with(auth()->user()->id_ormawa, '2'))
                    <table class="table table-striped table-bordered text-center">
                        <thead>
                            <tr>
                                <th scope="col">Status Kemahasiswaan</th>
                                <th scope="col">Status Wakil Rektor 3 </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $statuses->status_kemahasiswaan }}</td>
                                <td>{{ $statuses->status_wr3 }}</td>
                            </tr>
                        </tbody>
                    </table>
                @endif

                <br>
                <form id="laporan_form" action="{{ route('laporan_update', $laporan->id_laporan) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-row">
                        <div class="form-group">
                            <label for="jenis_laporan">Jenis Laporan:</label>
                            <select id="jenis_laporan" name="jenis_laporan" class="form-control" disabled>
                                <option value="LPJ" {{ $laporan->jenis_laporan == 'LPJ' ? 'selected' : '' }}>LPJ</option>
                                <option value="Tahunan" {{ $laporan->jenis_laporan == 'Tahunan' ? 'selected' : '' }}>Tahunan
                                </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="judul_kegiatan">Judul Kegiatan:</label>
                            <input type="text" id="judul_kegiatan" name="judul_kegiatan"
                                value="{{ $laporan->judul_kegiatan }}" readonly>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="rencana_kegiatan">Rencana Kegiatan:</label>
                            <textarea id="rencana_kegiatan" name="rencana_kegiatan" rows="4" readonly>{{ $laporan->rencana_kegiatan }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="relasi_kegiatan">Realisasi Kegiatan:</label>
                            <textarea id="relasi_kegiatan" name="relasi_kegiatan" rows="4" readonly>{{ $laporan->relasi_kegiatan }}</textarea>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="evaluasi">Evaluasi:</label>
                            <textarea id="evaluasi" name="evaluasi" rows="4" readonly>{{ $laporan->evaluasi }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="penggunaan_dana">Penggunaan Dana:</label>
                            <textarea id="penggunaan_dana" name="penggunaan_dana" rows="4" readonly>{{ $laporan->penggunaan_dana }}</textarea>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="penutup">Penutup:</label>
                            <textarea id="penutup" name="penutup" rows="4" readonly>{{ $laporan->penutup }}</textarea>
                        </div>
                    </div>

                    <br>
                    <div class="form-group mb-4 p-3 border rounded shadow-sm bg-light">
                        <div class="d-flex align-items-center mb-3">
                            <div class="w-20 fw-bold" style="font-size: 1.5rem;">
                                Hasil Review
                            </div>
                        </div>

                        @if (Str::startsWith(auth()->user()->id_ormawa, '1'))
                            <!-- Catatan Prodi -->
                            <div class="row mb-4">
                                <div class="col-md-3 fw-bold">
                                    <label for="catatan_prodi" class="form-label">Catatan Prodi:</label>
                                </div>
                                <div class="col-md-9">
                                    <textarea name="catatan_prodi" id="catatan_prodi" class="form-control" placeholder="Masukkan catatan" rows="3"
                                        readonly>{{ $laporan->catatan_prodi ?? 'Tidak ada catatan' }}</textarea>
                                </div>
                            </div>

                            <hr>

                            <!-- Catatan Kemahasiswaan -->
                            <div class="row mb-4">
                                <div class="col-md-3 fw-bold">
                                    <label for="catatan_kemahasiswaan" class="form-label">Catatan Kemahasiswaan:</label>
                                </div>
                                <div class="col-md-9">
                                    <textarea name="catatan_kemahasiswaan" id="catatan_kemahasiswaan" class="form-control" placeholder="Masukkan catatan"
                                        rows="3" readonly>{{ $laporan->catatan_kemahasiswaan ?? 'Tidak ada catatan' }}</textarea>
                                </div>
                            </div>

                            <hr>

                            <!-- Catatan WR3 -->
                            <div class="row mb-4">
                                <div class="col-md-3 fw-bold">
                                    <label for="catatan_rektor" class="form-label">Catatan WR3:</label>
                                </div>
                                <div class="col-md-9">
                                    <textarea name="catatan_rektor" id="catatan_rektor" class="form-control" placeholder="Masukkan catatan" rows="3"
                                        readonly>{{ $laporan->catatan_rektor ?? 'Tidak ada catatan' }}</textarea>
                                </div>
                            </div>

                            <hr>

                            <!-- Catatan Fakultas -->
                            <div class="row mb-4">
                                <div class="col-md-3 fw-bold">
                                    <label for="catatan_fakultas" class="form-label">Catatan Fakultas:</label>
                                </div>
                                <div class="col-md-9">
                                    <textarea name="catatan_fakultas" id="catatan_fakultas" class="form-control" placeholder="Masukkan catatan"
                                        rows="3" readonly>{{ $laporan->catatan_fakultas ?? 'Tidak ada catatan' }}</textarea>
                                </div>
                            </div>
                        @elseif(Str::startsWith(auth()->user()->id_ormawa, '2'))
                            <!-- Catatan Kemahasiswaan -->
                            <div class="row mb-4">
                                <div class="col-md-3 fw-bold">
                                    <label for="catatan_kemahasiswaan" class="form-label">Catatan Kemahasiswaan:</label>
                                </div>
                                <div class="col-md-9">
                                    <textarea name="catatan_kemahasiswaan" id="catatan_kemahasiswaan" class="form-control"
                                        placeholder="Masukkan catatan" rows="3" readonly>{{ $laporan->catatan_kemahasiswaan ?? 'Tidak ada catatan' }}</textarea>
                                </div>
                            </div>

                            <hr>

                            <!-- Catatan WR3 -->
                            <div class="row mb-4">
                                <div class="col-md-3 fw-bold">
                                    <label for="catatan_rektor" class="form-label">Catatan WR3:</label>
                                </div>
                                <div class="col-md-9">
                                    <textarea name="catatan_rektor" id="catatan_rektor" class="form-control" placeholder="Masukkan catatan"
                                        rows="3" readonly>{{ $laporan->catatan_rektor ?? 'Tidak ada catatan' }}</textarea>
                                </div>
                            </div>
                        @endif
                    </div>

                    <br>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="lampiran">Lampiran :</label>
                            <a href="{{ route('showlampiran', $laporan->id_laporan) }}" class="form-control text-primary"
                                readonly>{{ $laporan->lampiran }}</a>
                        </div>
                    </div><br>

                    <div class="button-container">
                        <div class="form-group">
                            <button id="submit_button" type="submit" class="btn btn-secondary"><i class="lni lni-save"
                                    style="vertical-align: middle; margin-right: 2px;"></i><span
                                    style="vertical-align: middle;">Simpan</span></button>
                            <button type="button" class="btn btn-warning float-left" onclick="enableEditing()"><i
                                    class="lni lni-pencil-alt"
                                    style="vertical-align: middle; margin-right: 2px;"></i><span
                                    style="vertical-align: middle;">Revisi</span></button>
                            <button type="button" class="btn btn-danger float-left" onclick="goBack()"><i
                                    class="lni lni-arrow-left-circle"
                                    style="vertical-align: middle; margin-right: 2px;"></i><span
                                    style="vertical-align: middle;">Batal</span></button>
                        </div>
                    </div>
                </form>
            @else
                <div class="form-container">
                    <h1>Detail Laporan</h1>
                    <!-- Bagian lain dari form jika laporan tidak ada -->
                </div>
            @endif
        </div>
    </div>
    <script>
        function enableEditing() {
            // Mendapatkan semua elemen input pada form
            var inputs = document.querySelectorAll('input, textarea');

            // Menghapus atribut readonly dari setiap input
            inputs.forEach(function(input) {
                input.removeAttribute('readonly');
            });

            // Mengubah warna tombol submit menjadi biru
            document.getElementById('submit_button').classList.remove('btn-secondary');
            document.getElementById('submit_button').classList.add('btn-primary');
        }

        function goBack() {
            // Fungsi ini akan mengarahkan pengguna kembali ke halaman sebelumnya
            window.history.back();
        }
    </script>

@endsection
