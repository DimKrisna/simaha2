@extends('sidebaradmin')
@section('content')
<div class="container">
    <div class="row mt-3">
        <div class="col-4">
        @if(session('success'))
        <p class="alert alert-success">{{ session('success') }}</p>
        @endif
        @if(session('delete_success'))
        <p class="alert alert-danger">{{ session('delete_success') }}</p>
        @endif
        <a class="btn btn-primary" href="{{ route('tambah') }}" role="button">+ Tambah User</a>
        </div>
    </div>
</div>

<div class="container">
    <h1 class="text-center mt-5 mb-4">List User Sistem</h1>
    <div class="col-4">
    </div><br>
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Username</th>
                <th scope="col">Role</th>
                <th scope="col">Ormawa</th>
                <th scope="col">Hapus</th>
            </tr>
        </thead>
        <tbody>
            @if($usersData->isEmpty())
                <tr>
                    <td colspan="3" class="text-center">Tidak ada data user.</td>
                </tr>
            @else
                @foreach($usersData as $user)
                    <tr>
                        <td>{{ $user->username }}</td>
                        <td>{{ $user->role }}</td>
                        <td>{{ $user->nama_ormawa }}</td>
                        <td>
                            <form method="POST" action="{{ route('hapus', ['user_id' => $user->user_id]) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger"><i class="lni lni-trash-can"></i></button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>
@endsection
