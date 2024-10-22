@extends('sidebaradmin')

@section('content')
<div class="container">
    <h1 class="text-center mt-5 mb-4">Daftar Peringkat Ormawa Periode {{ $periode }}</h1>
    
     <!-- Normalized Data C1 -->
     <h2 class="mt-4">Hasil Perhitungan Kriteria 1 (selisih dana pada proposal dengan laporan)</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Nama Ormawa</th>
                <th scope="col">Nilai</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($normalizedData1 as $data)
                <tr>
                    <td>{{ $data['nama_ormawa'] }}</td>
                    <td>{{ $data['weight'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

     <!-- Normalized Data C2 -->
    <h2 class="mt-4">Hasil Perhitungan Kriteria 2 (selisih dana pada proker dengan proposal)</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Nama Ormawa</th>
                <th scope="col">Nilai</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($normalizedData2 as $data)
                <tr>
                    <td>{{ $data['nama_ormawa'] }}</td>
                    <td>{{ $data['weight'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

     <!-- Normalized Data C3 -->
    <h2 class="mt-4">Hasil Perhitungan Kriteria 3 (Program Terlaksana)</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Nama Ormawa</th>
                <th scope="col">Nilai</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($normalizedData3 as $data)
                <tr>
                    <td>{{ $data['nama_ormawa'] }}</td>
                    <td>{{ $data['weight'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Daftar Peringkat Akhir -->
    <h2 class="mt-4">Daftar Peringkat Akhir</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Nama Ormawa</th>
                <th scope="col">Nilai Total</th>
                <th scope="col">Rank</th>
                <th scope="col">Data Statistik</th>
            </tr>
        </thead>
        @php $rank = 1; @endphp
        @foreach ($final as $data)
            <tr>
                <td>{{ $data['nama_ormawa'] }}</td>
                <td>{{ $data['totalc'] }}</td>
                <td>{{ $rank }}</td>
                <td>
                    <a class="btn btn-success" href="{{ route('datastatistik', ['id' => $data['id_ormawa']]) }}" role="button">Lihat</a>
                </td>
            </tr>
            @php $rank++; @endphp
        @endforeach
    </table>
    
</div>
@endsection
