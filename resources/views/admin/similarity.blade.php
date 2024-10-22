@extends('sidebaradmin')

@section('content')
    <div style="text-align: center;" class="mt-5">
        
        <div class="container mt-5 border">
            <br>
            <h1>Proposal Similarity Check Results</h1>
            <br>
            
            <div style="text-align: left; margin-bottom: 10px;">
                <a class="btn btn-danger" href="{{ url()->previous() }}" role="button">
                    <i class="lni lni-arrow-left-circle" style="margin-right: 5px;"></i> Back
                </a>
            </div>
       <br>
         <br>
            <div style="text-align: left; margin-bottom: 10px;">
    <div class="alert alert-primary" role="alert">
        <strong>Threshold Kesamaan yang Dipakai: </strong>{{ $similarity_threshold }}
    </div>
</div>

            <br>
       <br>
            <table class="table table-striped border table-bordered text-center">
                <thead>
                    <tr>
                        <th>ID Proposal</th>
                        <th>Judul Kegiatan</th>
                        <th>ID Proposal Serupa</th>
                        <th>Judul Kegiatan Serupa</th>
                        <th>Periode Pengajuan</th>
                        <th>Point Kesamaan </th>
                        <th>Tema</th>
                        <th>Judul Kegiatan</th>
                        <th>Latar Belakang</th>
                        <th>Deskripsi Kegiatan</th>
                        <th>Tujuan Kegiatan</th>
                        <th>Manfaat Kegiatan</th>
                        <th>Saran</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($results as $result)
                        <tr>
                            <td><a href="{{ route('detailsimilarity', $result['id_proposal']) }}" class="btn btn-primary">
                                    <span style="vertical-align: middle; margin-left: 2px;">
                                        {{ $result['id_proposal'] }}
                                    </span>
                                </a>
                            </td>
                            <td>{{ $result['judul_kegiatan'] }}</td>
                            <td><a href="{{ route('detailsimilarity', $result['similar_id_proposal']) }}"
                                    class="btn btn-primary">
                                    <span style="vertical-align: middle; margin-left: 2px;">
                                        {{ $result['similar_id_proposal'] }}
                                    </span>
                                </a>
                            </td>
                            <td>{{ $result['similar_judul_kegiatan'] }}</td>
                            <td>{{ $result['periode'] }}</td>
                            <td>{{ number_format($result['similarity'], 2) }}</td>
                            <td>{{ number_format($result['indicator_similarities']['tema'], 2) }}</td>
                            <td>{{ number_format($result['indicator_similarities']['judul_kegiatan'], 2) }}</td>
                            <td>{{ number_format($result['indicator_similarities']['latar_belakang'], 2) }}</td>
                            <td>{{ number_format($result['indicator_similarities']['deskripsi_kegiatan'], 2) }}</td>
                            <td>{{ number_format($result['indicator_similarities']['tujuan_kegiatan'], 2) }}</td>
                            <td>{{ number_format($result['indicator_similarities']['manfaat_kegiatan'], 2) }}</td>
                            <td>{{ $result['suggestion'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <br>

            <!-- Pagination links -->
            {{-- <div class="d-flex justify-content-center">
            {{ $proposals->links('vendor.pagination') }}
        </div> --}}
        </div>
    </div>
    <br>
    <br>
    <br>
    <br>
@endsection
