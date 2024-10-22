@extends('sidebarfakultassaintek')
@section('content')
    <div class="container border">
        <div class="row mt-3">
            <div class="col-8">
                <a class="btn btn-danger" href="{{ url()->previous() }}" role="button"><i class="lni lni-arrow-left-circle"
                        style="vertical-align: middle;"></i> <span style="vertical-align: middle; margin-left: 2px;">Back</span></a>
            </div>
        </div>
        <div class="container">
            <h1 class="text-center mt-5 mb-4">Daftar Kegiatan</h1>
            <div class="row">
                <div class="col-10 mx-auto center">
                    <table class="table table-striped">
                        <thead>
                            <tr class="text-center">
                                <th scope="col">Kegiatan</th>
                                <th scope="col">Informasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($monitoring as $kegiatan)
                                <tr>
                                    <td class="text-center">{{ $kegiatan->judul_kegiatan }}</td>
                                    <td class="text-center"><a class="btn btn-success"
                                            href="{{ route('detail.monitor.kegiatan.Fakultas', ['id' => $kegiatan->id_proposal]) }}"
                                            role="button"><i class="lni lni-eye" style="vertical-align: middle;"></i><span
                                                style="vertical-align: middle; margin-left: 2px;">Detail</span></a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
