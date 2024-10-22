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

                        <form action="{{ route('login.action') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label>Username <span class="text-danger">*</span></label>
                                <input class="form-control" type="username" name="username" value="{{ old('username') }}" />
                            </div>
                            <div class="mb-3">
                                <label>Password <span class="text-danger">*</span></label>
                                <input class="form-control" type="password" name="password" />
                            </div>
                            <div class="mb-3">
                                <button class="btn btn-primary">Login</button><br>
                                <br>
                                <a class="btn btn-danger" href="{{ route('home') }}">Back</a>
                            </div>
                        </form>
                    </div>
                </div>
            @endsection
