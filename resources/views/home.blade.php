@extends('app')
@section('content')

<div style="text-align: center;" class= "mt-5">
        <h2>Website dalam Tahap Pembangunan</h2>
        <img src="{{ asset('images/pembangunan.jpg') }}" alt="Website dalam Tahap Pembangunan" style="max-width: 100%; height: auto;">
        <br>
        <p>*menu register sudah di pindah ke menu sidebar admin untuk menambah user sistem</p>
        @auth
<p>Welcome <b>{{ Auth::user()->nama_singkatan }}</b></p>
<a class="btn btn-primary" href="{{ route('password') }}">Change Password</a>
<a class="btn btn-danger" href="{{ route('logout') }}">Logout</a>
@endauth
@guest
<a class="btn btn-primary" href="{{ route('login') }}">Developer Login</a>
@endguest
</div>


@endsection
