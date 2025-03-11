@extends('sidebarormawa')

@section('content')
    <div class="container">
        <div class="row mt-5">
            <div class="col-8">
            </div>
        </div>
        <h1 class="text-center mt-5 mb-4">Data Laporan</h1>
        <br>
        <div class="col-4">
        </div><br>
        <a href="{{ route('form_lpj') }}" class="btn btn-primary" role="button"><i class="lni lni-circle-plus"
                style="vertical-align: middle; margin-right: 5px;"></i>
            <span style="vertical-align: middle;">Pengajuan Laporan LPJ Kegiatan</span></a>
        <br> <br>
        <table class="table table-striped table-bordered text-center">
            <thead>
                <tr>
                    <th scope="col">Judul Kegiatan</th>
                    <th scope="col">Jenis Laporan</th>
                    <th scope="col">Status Laporan</th>
                    <th scope="col">Informasi </th>
                    <th scope="col">Lembar pengesahan</th>
                    <th scope="col">Naskah Laporan Final</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($laporan as $laporan)
                    <tr>
                        <td>{{ $laporan->judul_kegiatan }}</td>
                        <td>{{ $laporan->jenis_laporan }}</td>
                        <td>{{ $laporan->status_akhir }}</td>
                        <td><a href="{{ route('DetailLaporan', ['id_laporan' => $laporan->id_laporan]) }}"
                                class="btn btn-primary"><i class="lni lni-eye" style="vertical-align: middle;"></i>
                                <span style="vertical-align: middle; margin-left: 2px;">Detail</span></a></td>
                        <td>@if ($laporan->status_akhir != 'Revisi')
                            <a href="{{ route('laporan.pengesahan', Crypt::encryptString($data->id_laporan)) }}" class="btn btn-primary"><i class="lni lni-download" style="vertical-align: middle;"></i>
                            <span style="vertical-align: middle; margin-left: 2px;">Pengesahan</span></a>
                                @else
                            <button class="btn btn-primary" disabled><i class="lni lni-download" style="vertical-align: middle;"></i>
                            <span style="vertical-align: middle; margin-left: 2px;">Pengesahan</span>
                                @endif
                        </td>
                        <td>@if ($laporan->status_akhir != 'Revisi')
                            <a href="{{ route('uploadlaporankegiatan') }}" class="btn btn-primary"><i class="lni lni-upload" style="vertical-align: middle;"></i>
                            <span style="vertical-align: middle; margin-left: 2px;">Upload</span></a>
                                @else
                            <button class="btn btn-primary" disabled><i class="lni lni-upload" style="vertical-align: middle;"></i>
                            <span style="vertical-align: middle; margin-left: 2px;">Upload</span></button>
                                @endif
                        </td>    
                            
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    </tbody>
    </table>
    </div>
@endsection
