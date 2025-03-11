@extends('sidebarprodi')

@section('content')
    <div class="container">
        @if ($proposal->isEmpty())
            <div class="alert alert-danger mt-4" role="alert">
                Belum Ada Proposal yang diajukan
            </div>
        @else
            <h1 class="text-center mt-5 mb-4">List Proposal Kegiatan Ormawa</h1>
            <div class="col-4"></div>
            <br>
            <div class="container border">
            <table class="table table-striped">
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
                            <!-- Format the date using Carbon -->
                            <td class="text-center">{{ \Carbon\Carbon::parse($data->tanggal_pengajuan)->translatedFormat('d-F-Y') }}</td>
                            <td class="text-center">
                                <a class="btn btn-success"
                                    href="{{ route('detailproposal', ['id_proposal' => $data->id_proposal]) }}"
                                    role="button">Lihat</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <!-- Add pagination links -->
            <div class="d-flex justify-content-center">
                {{ $proposal->links('vendor.pagination') }}
            </div>
        @endif
        <br>
    </div>
    <br>
    </div>
@endsection
