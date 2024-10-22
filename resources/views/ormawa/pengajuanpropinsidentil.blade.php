@extends('sidebarormawa')

@section('content')
    <div class="container">
        <h1 class="text-center mt-5 mb-4">Data Pengajuan Proposal Kegiatan Insidentil</h1>
        <div class="col-4">
        </div><br><br>
        <a href="{{ route('forminsidentil') }}" class="btn btn-primary" role="button"><i class="lni lni-circle-plus"></i><span
                    style="vertical-align: middle; margin-left: 2px;">Pengajuan
                    Proposal</span></a>
        <br><br>
        @if($datainsidentil->isEmpty())
            <p class="text-center">Tidak ada data untuk ditampilkan.</p>
        @else
            <table class="table table-striped table-bordered text-center">
                <thead>
                    <tr>
                        <th scope="col">Tema Pengajuan</th>
                        <th scope="col">Jenis Proposal</th>
                        <th scope="col">Status Proposal</th>
                        <th scope="col">Tanggal Pengajuan</th>
                        <th scope="col">Informasi</th>
                        <th scope="col">Lembar pengesahan</th>
                        <th scope="col">Naskah Proposal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($datainsidentil as $data)
                        <tr>
                            <td>{{ $data->tema }}</td>
                            <td>{{ $data->jenis_proposal }}</td>
                            <td>{{ $data->status_akhir }}</td>
                            <td>{{ $data->tanggal_pengajuan }}</td>
                            <td><a href="{{ route('tampilpropinsidentil', Crypt::encryptString($data->id_proposal)) }}"
                                class="btn btn-primary"><i class="lni lni-eye" style="vertical-align: middle;"></i>
                                <span style="vertical-align: middle; margin-left: 2px;">Detail</span></a></td>
                            <td>
                                @if ($data->status_akhir != 'Revisi')
                                    <a href="{{ route('proposal.pengesahan', Crypt::encryptString($data->id_proposal)) }}" class="btn btn-primary"><i class="lni lni-download"
                                            style="vertical-align: middle;"></i>
                                        <span style="vertical-align: middle; margin-left: 2px;">Pengesahan</span></a>
                                @else
                                    <button class="btn btn-primary" disabled><i class="lni lni-download"
                                            style="vertical-align: middle;"></i>
                                        <span style="vertical-align: middle; margin-left: 2px;">Pengesahan</span></button>
                                @endif
                            </td>
                            <td>
                                @if ($data->status_akhir != 'Revisi')
                                    <a href="{{ route('uploadproposalpdf') }}" class="btn btn-primary"><i class="lni lni-upload"
                                            style="vertical-align: middle;"></i>
                                        <span style="vertical-align: middle; margin-left: 2px;">Upload</span></a>
                                @else
                                    <button class="btn btn-primary" disabled><i class="lni lni-upload"
                                            style="vertical-align: middle;"></i>
                                        <span style="vertical-align: middle; margin-left: 2px;">Upload</span></button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection

