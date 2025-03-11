@extends('sidebaradmin')

@section('content')
<div class="container">
    <h1 class="text-center mt-5 mb-4">List Proposal Kegiatan Ormawa</h1>

    <!-- Form Filter -->
    <form method="GET" action="{{ route('proposalkegiatan') }}" class="d-flex justify-content-left mb-3">
        <div class="input-group" style="max-width: 300px; gap: 8px;">
            <input type="text" name="search" class="form-control" placeholder="Cari..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary">Cari</button>
            <a href="{{ route('proposalkegiatan') }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>

<br>

    @if ($proposal->isEmpty())
        <div class="alert alert-danger mt-4" role="alert">
            Belum Ada Proposal yang diajukan
        </div>
    @else
        <table class="table table-striped table-bordered">
            <thead class="text-center">
                <tr>
                    <th scope="col" class="text-start">Judul Proposal</th>
                    <th scope="col">Nama Ormawa</th>
                    <th scope="col">Tanggal Pengajuan</th>
                    <th scope="col">Detail Proposal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($proposal as $data)
                    <tr>
                        <td class="text-start">{{ $data->judul_kegiatan }}</td>
                        <td class="text-center">{{ $data->nama_ormawa }}</td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($data->tanggal_pengajuan)->translatedFormat('d-F-Y') }}</td>
                        <td class="text-center">
                            <a class="btn btn-success" href="{{ route('detailproposaladmin', $data->id_proposal) }}" role="button">Lihat</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $proposal->links('vendor.pagination') }}
        </div>
        <br>
    @endif
</div>
@endsection
