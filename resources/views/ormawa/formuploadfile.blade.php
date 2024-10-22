@extends('sidebarormawa')

@section('content')
    <div class="container mt-4">
        <div class="form-container">
            <h1>Upload File Proposal</h1>
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            <form action="{{ route('uploadproposalproker') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label for="judul_kegiatan">Judul Kegiatan:</label>
                <input type="text" id="judul_kegiatan" name="judul_kegiatan" class="form-control" required>

                <div class="form-group">
                    <label for="file">File Proposal:</label>
                    <input type="file" name="file" id="file" class="form-control-file" required>
                </div>

                <!-- Tombol Submit -->
                <div class="button-container">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
@endsection
