@extends('sidebaradmin')

@section('content')
<div class="row justify-content-center mt-4">
    <div class="col-md-8">
        <h1>Sistem Pembantu Keputusan Peringkat Ormawa</h1>
        <p>Note : Menentukan peringkat menggunakan Metode AHP untuk informasi selengkapnya lihat di <a href="https://en.wikipedia.org/wiki/Analytic_hierarchy_process#:~:text=In%20the%20theory%20of%20decision%20making%2C%20the%20analytic,analyzing%20complex%20decisions%2C%20based%20on%20mathematics%20and%20psychology.">wikipedia</a></p>
        <form action="{{ route('normalisasi.post') }}" method="POST">
            @csrf
            <div class="form-group ">
                <label for="period">Pilih Periode Jabatan</label>
                <div class="input-group">
                    <select class="form-control custom-select" name="period" id="period">
                        @php
                            $uniquePeriods = $pilihanperiode->unique('periode');
                        @endphp
                        @foreach($uniquePeriods as $period)
                            <option value="{{ $period->periode }}">
                                {{ $period->periode }}
                            </option>
                        @endforeach
                    </select>
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-success">Go</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
