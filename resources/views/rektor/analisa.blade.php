@extends('sidebarrektor')
@section('content')
<div style="text-align: center;" class= "mt-5">
        <h2>Website dalam Tahap Pembangunan</h2>
        <img src="{{ asset('images/pembangunan.jpg') }}" alt="Website dalam Tahap Pembangunan" style="max-width: 100%; height: auto;">
        <br>
        <a href="{{ url()->previous() }}"  class="btn btn-secondary">Back</a>
    </div>
@endsection