@extends('sidebaradmin')

@section('content')
<div class="container">
        <div class="row mt-3">
        <div class="col-8">
            <a class="btn btn-secondary" href="{{ url()->previous() }}" role="button"><i class="lni lni-arrow-left-circle"></i> Back</a>
        </div>
    </div>
<h1 class="my-4 text-center">Jumlah Program Kerja {{$data1}}</h1>
<div class="row text-center mb-4">
        <div class="col-md-3">
            <div class="p-3 bg-light border rounded-lg">
                <h4>Wajib</h4>
                <h2>{{$data2}}</h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="p-3 bg-light border rounded-lg">
                <h4>Kebidangan</h4>
                <h2>{{$data3}}</h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="p-3 bg-light border rounded-lg">
                <h4>Unggulan</h4>
                <h2>{{$data10}}</h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="p-3 bg-light border rounded-lg">
                <h4>Total</h4>
                <h2>{{$data11}}</h2>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">Program kerja</h5>
            <p class="card-text">Jumlah : {{$data4}}</p>
            <p class="card-text">Dana diajukan : Rp. {{$data7}}</p>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">Proposal Kegiatan</h5>
            <p class="card-text">Jumlah : {{$data5}}</p>
            <p class="card-text">Dana dipakai : Rp. {{$data8}}</p>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">Laporan Kegiatan</h5>
            <p class="card-text">Jumlah : {{$data6}}</p>
            <p class="card-text">Dana dipakai : Rp. {{$data9}}</p>
        </div>
    </div>
</div>

<!--css tambahan untuk border-->
<style>
    .rounded-lg {
        border-radius: 15px;
    }
</style>
@endsection
