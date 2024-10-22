@extends('sidebaradmin')
@section('content')
<div class="container">
    <div class="row mt-3">
        <div class="col">
            <h1 class="text-center mt-5 mb-4">Hasil Pencarian Proposal</h1>

            <!-- Form Pencarian -->
            <div class="input-group">
                <form method="GET" action="{{ route('find.similar.proposals') }}">
                    <label for="id_proposal">Cari berdasarkan ID Proposal:</label>
                    <input type="text" name="id_proposal" id="id_proposal" value="{{ request('id_proposal') }}">

                    <label for="similarity_threshold">Nilai Kesamaan (%):</label>
                    <input type="number" name="similarity_threshold" id="similarity_threshold"
                        value="{{ request('similarity_threshold', 25) }}">

                    <button type="submit" class="btn btn-primary">
                        <i class="lni lni-magnifier" style="vertical-align: middle; margin-right: 5px;"></i>
                        <span style="vertical-align: middle; margin-left: 2px;">Cari</span>
                    </button>
                    <a href="{{ route('find.similar.proposals') }}" class="btn btn-danger">
                        <i class="lni lni-spinner-arrow" style="vertical-align: middle; margin-right: 5px;"></i>
                        <span style="vertical-align: middle; margin-left: 2px;">Reset</span>
                    </a>
                </form>
            </div>
            <br>
             <div class="alert {{ $overallAverageSimilarity > $similarity_threshold ? 'alert-danger' : 'alert-info' }}" role="alert">
                <h3 class="text-center mt-5 mb-4">Rata-rata Keseluruhan Point Kesamaan: {{ number_format($overallAverageSimilarity, 2) }}%</h3>
            </div>
            <br>

            <table class="table table-striped centered">
                <thead>
                    <tr>
                        <th>ID Proposal</th>
                        <th>Judul Kegiatan</th>
                        <th>Rata-rata Point Kesamaan</th>
                        <th>Saran</th>
                        <th>Informasi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($results as $result)
                        <tr>
                            <td>{{ $result['id_proposal'] }}</td>
                            <td>{{ $result['judul_kegiatan'] }}</td>
                            <td>{{ number_format($result['average_similarity'], 2) }}%</td>
                            <td>{{ $result['suggestion'] }}</td>
                            <td><a href="{{ route('proposals.similar', ['id_proposal' => $result['id_proposal']]) }}"
                                class="btn btn-primary"><i class="lni lni-eye" style="vertical-align: middle;"></i>
                                <span style="vertical-align: middle; margin-left: 2px;">Detail</span></a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
           
        </div>
    </div>
</div>
@endsection
