@extends('sidebarormawa')

@section('content')
    <div class="container border">
        <h1 class="text-center mt-5 mb-4">Data Kepengurusan Inti</h1>
        <div class="col-4">
        </div>
        <br>
        <table class="table table-striped border  table-bordered text-center">
            <thead>
                <tr>
                    <th scope="col">Nama Mahasiswa</th>
                    <th scope="col">NPM</th>
                    <th scope="col">Jabatan</th>
                    <th scope="col">Periode</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($databph as $data)
                    <tr>
                        <td>{{ $data->nama_mahasiswa }}</td>
                        <td>{{ $data->npm }}</td>
                        <td>{{ $data->nama_jabatan }}</td>
                        <td>{{ $data->periode }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <br>
    </div>
    <br>
    <br>
    <div class="container border">
        <h1 class="text-center mt-5 mb-4">Data Kepengurusan Divisi</h1>
        <div class="col-4">
        </div>
        <a href="{{ route('showFormDivisi') }}" class="btn btn-primary" role="button">
            <i class="lni lni-circle-plus" style="vertical-align: middle; margin-right: 5px;"></i>
            <span style="vertical-align: middle;">Tambah Kepengurusan Divisi</span>
        </a>
        <br>
        <br>
        <table class="table table-striped border  table-bordered text-center">
            <thead>
                <tr>
                    <th scope="col">Nama Mahasiswa</th>
                    <th scope="col">NPM</th>
                    <th scope="col">Jabatan</th>
                    <th scope="col">Divisi</th>
                    <th scope="col">Periode </th>
                    <th scope="col">Action </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($datadivisi as $data)
                    <tr>
                        <td>{{ $data->nama_mahasiswa }}</td>
                        <td>{{ $data->npm }}</td>
                        <td>{{ $data->nama_jabatan }}</td>
                        <td>{{ $data->nama_divisi }}</td>
                        <td>{{ $data->periode }}</td>
                        <td>
                            <form action="{{ route('deleteDetailKepengurusanDivisi', ['npm' => $data->npm]) }}"
                                method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger"
                                    onclick="return confirm('Are you sure you want to delete this item?')">
                                    <i class="lni lni-trash-can"></i>
                                    <span style="vertical-align: middle;">Delete</span>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="d-flex justify-content-center">
            {{ $datadivisi->links('vendor.pagination') }}
        </div>
        <br>
    </div>
    <br>
    <br>
    <div class="container border">
        <h1 class="text-center mt-5 mb-4">Data Program Kerja</h1>
        <div class="col-4">
        </div><br>
        <a href="{{ route('showFormProker') }}" class="btn btn-primary" role="button">
            <i class="lni lni-circle-plus" style="vertical-align: middle; margin-right: 5px;"></i>
            <span style="vertical-align: middle;">Tambah Program Kerja</span>
        </a>
        <a href="#" class="btn btn-primary" role="button">
            <i class="lni lni-upload" style="vertical-align: middle; margin-right: 5px;"></i>
            <span style="vertical-align: middle;">Upload File Program Kerja</span>
        </a>
        <br><br>
        <table class="table table-striped border  table-bordered text-center">
            <thead>
                <tr>
                    <th scope="col">Nama Kegiatan</th>
                    <th scope="col">Peran Organisasi</th>
                    <th scope="col">Jenis Kegiatan</th>
                    <th scope="col">Estimasi Anggaran </th>
                    <th scope="col">Status Pelaksanaan </th>
                    <th scope="col">Action </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($prokers as $data)
                    <tr>
                        <td>{{ $data->nama_kegiatan }}</td>
                        <td>{{ $data->peran_ormawa }}</td>
                        <td>{{ $data->jenis_kegiatan }}</td>
                        <td>{{ $data->estimasi_anggaran }}</td>
                        <td>{{ $data->status }}</td>
                        <td><a href="{{ route('detailproker', ['id_proker' => $data->id_proker]) }}"
                                class="btn btn-primary">
                                <i class="lni lni-eye" style="vertical-align: middle;"></i>
                                <span style="vertical-align: middle;">Detail</span></a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="d-flex justify-content-center">
            {{ $prokers->links('vendor.pagination') }}
        </div>
        <br>
    </div>
    <br>
    <script>
        // Fungsi untuk menampilkan pesan pop-up
        function showPopup(message, type) {
            // Buat elemen div untuk pop-up
            var popup = document.createElement('div');
            popup.className = 'popup ' + type; // Tambahkan kelas sesuai dengan tipe pesan

            // Buat elemen paragraf untuk pesan
            var text = document.createElement('p');
            text.textContent = message;

            // Tambahkan elemen paragraf ke dalam pop-up
            popup.appendChild(text);

            // Tambahkan pop-up ke dalam body
            document.body.appendChild(popup);

            // Hilangkan pop-up setelah 3 detik
            setTimeout(function() {
                popup.remove();
            }, 3000);
        }

        // Ambil pesan sukses dari session
        var successMessage = "{{ Session::get('success') }}";
        // Jika ada pesan sukses, tampilkan pop-up
        if (successMessage) {
            showPopup(successMessage, 'success');
        }

        // Ambil pesan error dari session
        var errorMessage = "{{ Session::get('error') }}";
        // Jika ada pesan error, tampilkan pop-up
        if (errorMessage) {
            showPopup(errorMessage, 'error');
        }
    </script>
@endsection
