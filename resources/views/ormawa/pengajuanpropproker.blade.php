@extends('sidebarormawa')
@section('content')

    <div class="container">
        @if ($allProker->isEmpty())
            <div class="alert alert-danger mt-4" role="alert">
                Belum Terdapat Program Kerja yang Diajukan. Silahkan Ajukan Program Kerja Terlebih Dahulu
            </div>
        @else
            <div class="row mt-5">
                <div class="col-8">
                </div>
            </div>
            <h1 class="text-center mt-5 mb-4">Data Pengajuan Proposal Kegiatan Berdasarkan Program Kerja</h1>
            <div class="col-4">
            </div><br><br>
            <a href="{{ route('formpropproker') }}" class="btn btn-primary" role="button"><i
                    class="lni lni-circle-plus"></i><span style="vertical-align: middle; margin-left: 2px;">Pengajuan
                    Proposal</span></a>
            <br>
            <br>
            <table class="table table-striped table-bordered text-center">
                <thead>
                    <tr>
                        <th scope="col">Tema Pengajuan</th>
                        <th scope="col">Status Proposal</th>
                        <th scope="col">Tanggal Pengajuan</th>
                        <th scope="col">Informasi </th>
                        <th scope="col">Lembar pengesahan</th>
                        <th scope="col">Naskah Proposal Final</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($datapropproker as $proposal)
                        <tr>
                            <td>{{ $proposal->tema }}</td>
                            <td>{{ $proposal->status_akhir }}</td>
                            <td>{{ \Carbon\Carbon::parse($proposal->tanggal_pengajuan)->translatedFormat('d F Y') }}</td>
                            <td><a href="{{ route('tampilprop', Crypt::encryptString($proposal->id_proposal)) }}"
                                    class="btn btn-primary"><i class="lni lni-eye" style="vertical-align: middle;"></i>
                                    <span style="vertical-align: middle; margin-left: 2px;">Detail</span></a></td>
                           <td>
    @if ($proposal->status_akhir != 'Revisi')
        <a href="{{ route('proposal.pengesahan', Crypt::encryptString($proposal->id_proposal)) }}" 
           class="btn btn-primary">
           <i class="lni lni-download" style="vertical-align: middle;"></i>
           <span style="vertical-align: middle; margin-left: 2px;">Pengesahan</span></a>
    @else
        <button class="btn btn-primary" disabled>
           <i class="lni lni-download" style="vertical-align: middle;"></i>
           <span style="vertical-align: middle; margin-left: 2px;">Pengesahan</span>
        </button>
    @endif
</td>

                            <td>
                                @if ($proposal->status_akhir != 'Revisi')
                                    <a href="{{ route('uploadproposalpdf') }}" class="btn btn-primary"><i
                                            class="lni lni-upload" style="vertical-align: middle;"></i>
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
            <div class="d-flex justify-content-center">
            {{ $datapropproker->links('vendor.pagination') }}
            </div>
    </div>
    </tbody>
    </table>


    @endif
@endsection
