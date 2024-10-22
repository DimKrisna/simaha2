@extends('sidebaradmin')

@section('content')
    <div class="d-flex justify-content-center">
        <div class="col-md-6">
            <h2 class="mb-4 mt-3">Isi Data User</h2>

            @if ($errors->any())
                @foreach ($errors->all() as $err)
                    <p class="alert alert-danger">{{ $err }}</p>
                @endforeach
            @endif

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <form id="registerForm" action="{{ route('register.action') }}" method="POST">
                @csrf
                <div class="mb-3">
                 <label for="nama_singkatan" class="form-label">Nama Singkatan</label>
                  <input type="text" name="nama_singkatan" id="nama_singkatan" class="form-control" placeholder="singkatan">
                </div>
                <div class="mb-3">
                    <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" name="username" value="{{ old('username') }}"
                        id="username" />
                </div>
                <div class="mb-3">
                    <label for="id_ormawa" class="form-label">Ormawa (Bila ada)</label>
                    <select class="form-select" name="id_ormawa" id="id_ormawa" aria-label="Default select example">
                        <option value="" selected>Pilih Ormawa</option>
                        @foreach ($ormawas as $ormawa)
                            <option value="{{ $ormawa->id_ormawa }}">{{ $ormawa->nama_ormawa }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="id_role" class="form-label">User Role <span class="text-danger">*</span></label>
                    <select class="form-select" name="id_role" id="id_role" aria-label="Default select example">
                        <option selected>Select User's role</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id_role }}">{{ $role->role }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                    <input class="form-control" type="password" name="password" id="password" />
                </div>

                <div class="mb-3">
                    <label for="password_confirm" class="form-label">Password Confirmation <span
                            class="text-danger">*</span></label>
                    <input class="form-control" type="password" name="password_confirm" id="password_confirm" />
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-primary" >
                        <i class="lni lni-circle-plus" style="vertical-align: middle; margin-right: 5px;"></i>
                      <span style="vertical-align: middle; margin-left: 2px;">Tambah</span></button>
                    <a class="btn btn-danger mx-2" href="{{ url()->previous() }}"><i class="lni lni-arrow-left-circle"
                                style="vertical-align: middle; margin-right: 5px;"></i>
                                <span style="vertical-align: middle; margin-left: 2px;">Back</span></a>
                </div>
            </form><br>
        </div>
    </div>
    <br>
@endsection
