@extends('app')
@section('content')
    <link href="{{ asset('css/stylesLogin.css') }}" rel="stylesheet">
    <div class="row">
        <div class="col-md-6">
            @if (session('success'))
                <p class="alert alert-success">{{ session('success') }}</p>
            @endif
            @if ($errors->any())
                @foreach ($errors->all() as $err)
                    <p class="alert alert-danger">{{ $err }}</p>
                @endforeach
            @endif
            <div class="container">
                <div class="form-container">
                    <div class="logo-container">
                        <img src="{{ asset('images/UTY.png') }}" alt="Logo"><br><br>
                        

                        <form action="{{ route('password.action') }}"  method="POST">
                            @csrf
                                <div class="mb-3">
                                    <label>Password Lama <span class="text-danger">*</span></label>
                                    <input class="form-control" type="password" name="old_password" />
                                </div>
                                <div class="mb-3">
                                    <label>Password Baru <span class="text-danger">*</span></label>
                                    <input class="form-control" type="password" name="new_password" />
                                </div>
                                <div class="mb-3">
                                    <label>Konfirmasi Password Baru<span class="text-danger">*</span></label>
                                    <input class="form-control" type="password" name="new_password_confirmation" />
                                </div>
                            <div class="mb-3">
                                <button class="btn btn-primary">Ubah</button><br>
                                <br>
                                <a class="btn btn-danger" href="{{ route('home') }}">Back</a>
                            </div>
                        </form>
                    </div>
                </div>
            @endsection
