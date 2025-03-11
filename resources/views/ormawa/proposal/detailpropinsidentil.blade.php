@extends('sidebarormawa')

@section('content')
<div class="container">
    <div class="form-container">
        <h1>Detail Laporan Kegiatan</h1>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @isset($proposal->statuses)
            @if (str_starts_with(auth()->user()->id_ormawa, '1'))
                <table class="table table-striped table-bordered text-center">
                    <thead>
                        <tr>
                            <th scope="col">Status Prodi</th>
                            <th scope="col">Status Fakultas</th>
                            <th scope="col">Status Kemahasiswaan</th>
                            <th scope="col">Status Rektorat</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $proposal->statuses->status_kaprodi }}</td>
                            <td>{{ $proposal->statuses->status_dekanat }}</td>
                            <td>{{ $proposal->statuses->status_kemahasiswaan }}</td>
                            <td>{{ $proposal->statuses->status_wr3 }}</td>
                        </tr>
                    </tbody>
                </table>
            @elseif (str_starts_with(auth()->user()->id_ormawa, '2'))
                <table class="table table-striped table-bordered text-center">
                    <thead>
                        <tr>
                            <th scope="col">Status Kemahasiswaan</th>
                            <th scope="col">Status Wakil Rektor 3</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $proposal->statuses->status_kemahasiswaan }}</td>
                            <td>{{ $proposal->statuses->status_wr3 }}</td>
                        </tr>
                    </tbody>
                </table>
            @endif
        @else
            <div class="alert alert-danger">
                Status tidak ditemukan.
            </div>
        @endisset
                <br>
                <form id="proposal_form" action="{{ route('proposal_update', $proposal->id_proposal) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-row">
                        <div class="form-group">
                            <label for="tema">Tema:</label>
                            <input type="text" id="tema" name="tema" value="{{ $proposal->tema }}" readonly>
                        </div>

                        <div class="form-group">
                            <label for="judul_kegiatan">Judul Kegiatan:</label>
                            <input type="text" id="judul_kegiatan" name="judul_kegiatan"
                                value="{{ $proposal->judul_kegiatan }}" readonly>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="latar_belakang">Latar Belakang:</label>
                            <textarea id="latar_belakang" name="latar_belakang" rows="4" readonly>{{ $proposal->latar_belakang }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="deskripsi_kegiatan">Deskripsi Kegiatan:</label>
                            <textarea id="deskripsi_kegiatan" name="deskripsi_kegiatan" rows="4" readonly>{{ $proposal->deskripsi_kegiatan }}</textarea>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="tujuan_kegiatan">Tujuan Kegiatan:</label>
                            <textarea id="tujuan_kegiatan" name="tujuan_kegiatan" rows="4" readonly>{{ $proposal->tujuan_kegiatan }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="manfaat_kegiatan">Manfaat Kegiatan:</label>
                            <textarea id="manfaat_kegiatan" name="manfaat_kegiatan" rows="4" readonly>{{ $proposal->manfaat_kegiatan }}</textarea>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="tempat_pelaksanaan">Tempat Pelaksanaan:</label>
                            <input type="text" id="tempat_pelaksanaan" name="tempat_pelaksanaan"
                                value="{{ $proposal->tempat_pelaksanaan }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="anggaran_kegiatan">Anggaran Kegiatan:</label>
                            <input type="text" id="anggaran_kegiatan" name="anggaran_kegiatan"
                                value="Rp {{ number_format($proposal->anggaran_kegiatan, 0, ',', '.') }}" readonly>
                        </div>

                        <div class="form-group">
                            <label for="anggaran_diajukan">Anggaran Diajukan:</label>
                            <input type="text" id="anggaran_diaj ukan" name="anggaran_diajukan"
                                value="Rp {{ number_format($proposal->anggaran_diajukan, 0, ',', '.') }}" readonly>
                        </div>

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
                    <br>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="lampiran">Lampiran :</label>
                            <a href="{{route('showlampiranproposal', $proposal->id_proposal)}}" class="btn btn-primary">Lihat Lampiran</a>
                        </div>
                    </div><br>
                    <div class="button-container">
                        <div class="form-group">
                            <button id="submit_button" type="submit" class="btn btn-secondary"><i class="lni lni-save"
                                    style="vertical-align: middle; margin-right: 2px;"></i><span
                                    style="vertical-align: middle;">Simpan</span></button>
                            <button type="button" class="btn btn-warning float-left" onclick="enableEditing()"><i
                                    class="lni lni-pencil-alt" style="vertical-align: middle; margin-right: 2px;"></i><span
                                    style="vertical-align: middle;">Revisi</span></button>
                            <button type="button" class="btn btn-danger float-left" onclick="goBack()"><i
                                    class="lni lni-arrow-left-circle"
                                    style="vertical-align: middle; margin-right: 2px;"></i><span
                                    style="vertical-align: middle;">Batal</span></button>
                        </div>
                    </div>
                </form>

        </div>
    </div>
    <br>
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
