@extends('sidebarprodi')
@section('content')
<div class="container">
<div class="row mt-3">
        <div class="col-8">
        <a class="btn btn-secondary" href="{{ url()->previous() }}" role="button"><i class="lni lni-arrow-left-circle"></i>  Back</a>
        </div>
</div>
<div style="text-align: center;">
    <h2>Struktur Organisasi Bidang Kemahasiswaan UTY</h2>
    <img src="{{ asset('images/struktur.jpeg') }}" alt="Struktur Organisasi Struktural" style="max-width: 50%; height: auto;">
    <br>
</div>
</div>
@endsection
