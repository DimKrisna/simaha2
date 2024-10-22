@extends('sidebaradmin')
@section('content')
<div class="container">
    <div class="row mt-3">
        <div class="col">
            <a class="btn btn-secondary" href="{{ url()->previous() }}" role="button"><i class="lni lni-arrow-left-circle"></i> Back</a>
            <h1 class="text-center mt-5 mb-4">Struktur Kepengurusan {{ $nama_singkatan }}</h1>
            <a href="{{ route('showformbph') }}" class="btn btn-primary" role="button">
                <i class="lni lni-circle-plus" style="vertical-align: middle; margin-right: 5px;"></i>
                <span style="vertical-align: middle;">Tambah Kepengurusan Inti</span>
            </a>
            <br><br>

            <!-- Form untuk memilih periode -->
            <form method="GET" action="{{ route('informasi', ['id' => $id]) }}">
                <div class="form-group">
                    <label for="periode">Pilih Periode:</label>
                    <select name="periode" id="periode" class="form-control" onchange="this.form.submit()">
                        @foreach ($kepengurusan as $data)
                            <option value="{{ $data->periode }}" {{ $data->periode == $selectedPeriode ? 'selected' : '' }}>
                                {{ $data->periode }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
            <br><br>

            <table class="table table-striped centered">
                <thead>
                    <tr>
                        <th>Jabatan</th>
                        <th>Nama Pengurus</th>
                        <th>NPM</th>
                        <th>Periode</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($jabatan as $data)
                        <tr>
                            <td>{{ $data->nama_jabatan }}</td>
                            <td>{{ $data->nama_mahasiswa }}</td>
                            <td>{{ $data->npm }}</td>
                            <td>{{ $data->periode }}</td>
                            <td>
                                <a href="#" class="btn btn-primary">
                                    <i class="lni lni-pencil" style="vertical-align: middle;"></i>
                                    <span style="vertical-align: middle;">Edit</span>
                                </a>
                                <form action="{{ route('deleteDetailKepengurusan', ['npm' => $data->npm]) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this item?')">
                                        <i class="lni lni-trash-can"></i>
                                        <span style="vertical-align: middle;">Delete</span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <h1 class="text-center mt-5 mb-4">Program Kerja {{ $nama_singkatan }}</h1>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Proker</th>
                        <th>Status Terlaksana</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($informasi as $data)
                        <tr>
                            <td>{{ $data->nama_kegiatan }}</td>
                            <td>
                                @if ($data->status == 'Belum Terlaksana')
                                    <button type="button" class="btn btn-danger">Belum Terlaksana</button>
                                @elseif($data->status == 'Terlaksana')
                                    <button type="button" class="btn btn-success">Terlaksana</button>
                                @else
                                    <span class="text-muted">Status tidak valid</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<br>
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
