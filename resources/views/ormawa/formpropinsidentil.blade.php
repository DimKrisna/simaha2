@extends('sidebarormawa')
@section('content')
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Input Data Proposal Kegiatan</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/line-awesome/1.3.0/css/line-awesome.min.css">
        <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    </head>

    <body>
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
                <h1>Input Data Proposal Kegiatan</h1>
                <form action="{{ route('pengajuanprop_insidentil') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <label for="tema">Tema:</label>
                    <input type="text" id="tema" name="tema" required>

                    <label for="judul_kegiatan">Judul Kegiatan:</label>
                    <input type="text" id="judul_kegiatan" name="judul_kegiatan" required>

                    <label for="latar_belakang">Latar Belakang:</label>
                    <textarea id="latar_belakang" name="latar_belakang" rows="4" required></textarea>

                    <label for="deskripsi_kegiatan">Deskripsi Kegiatan:</label>
                    <textarea id="deskripsi_kegiatan" name="deskripsi_kegiatan" rows="4" required></textarea>

                    <label for="tujuan_kegiatan">Tujuan Kegiatan:</label>
                    <textarea id="tujuan_kegiatan" name="tujuan_kegiatan" rows="4" required></textarea>

                    <label for="manfaat_kegiatan">Manfaat Kegiatan:</label>
                    <textarea id="manfaat_kegiatan" name="manfaat_kegiatan" rows="4" required></textarea>

                    <label for="tempat_pelaksanaan">Tempat Pelaksanaan:</label>
                    <input type="text" id="tempat_pelaksanaan" name="tempat_pelaksanaan" required>

                    <label for="anggaran_kegiatan">Anggaran Kegiatan:</label>
                    <input type="number" id="anggaran_kegiatan" name="anggaran_kegiatan" required>

                    <label for="anggaran_diajukan">Anggaran Diajukan:</label>
                    <input type="number" id="anggaran_diajukan" name="anggaran_diajukan" required>

                    {{-- <label for="lampiran">Lampiran: *file Pdf</label>
                    <input type="file" name="lampiran" id="lampiran" accept="application/pdf" class="form-control-file"
                        required> --}}
                        <br>

                    <div class="date-flex-container">
                        <div id="waktu_kegiatan_container">
                            <label for="waktu_kegiatan">Waktu Kegiatan:</label>
                            <input type="date" id="waktu_kegiatan" name="waktu_kegiatan[]">
                        </div>

                        <!-- Tombol Tambah Tanggal -->
                        <div id="add_date_container">
                            <div class="add-date-button" onclick="addDateInput()">
                                <i class="lni lni-circle-plus" style="vertical-align: middle; margin-right:2px;"></i>
                                <span style="vertical-align: middle;">Tambah Tanggal</span>
                            </div>
                        </div>
                    </div>


                    <div class="button-container">
                        <button class="btn btn-primary"><i class="lni lni-save"
                                style="vertical-align: middle; margin-right: 2px;"></i><span
                                style="vertical-align: middle;">Simpan</span></button>
                        <a class="btn btn-danger" href="{{ url()->previous() }}" role="button"><i
                                class="lni lni-arrow-left-circle" style="vertical-align: middle; margin-right: 2px;"></i>
                            <span style="vertical-align: middle;">Batal</span></a>
                    </div>
                </form>
            </div>
            <br><br><br>

            <script>
                function addDateInput() {
                    var container = document.getElementById("waktu_kegiatan_container");
                    var input = document.createElement("input");
                    input.type = "date";
                    input.name = "waktu_kegiatan[]";
                    input.multiple = true;
                    input.style.width = "calc(100% - 32px)";
                    input.style.padding = "8px";
                    input.style.marginBottom = "10px";
                    input.style.border = "1px solid #ccc";
                    input.style.borderRadius = "5px";
                    input.style.boxSizing = "border-box";
                    container.appendChild(input);

                    // Tambahkan event listener untuk menampilkan date picker saat diklik
                    input.addEventListener("click", function() {
                        this.focus(); // Fokuskan pada input field
                    });
                }
            </script>
    </body>

    </html>
@endsection
