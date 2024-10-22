@extends('sidebaradmin')
@section('content')
    <div class="container">
        <div class="row mt-5">
            <div class="col-8">
            <a href="#" class="btn btn-primary" onClick="create()" id="btnTambahHIMA"> +Tambah Data</a>
            </div>
        </div>
        <div class="row mt-5">
        <div class="col-8">
            <a href="{{ route('input_periode') }}" class="btn btn-primary" >Tambah Periode Kepengurusan</a>
            </div></div>
        <h1 class="text-center mt-5 mb-4">Data HIMA</h1>
        <div class="col-4">
        @if(session('delete_success'))
           <div class="alert alert-success">
              {{ session('delete_success') }}
           </div>
         @endif
        </div><br>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">Id Ormawa</th>
                    <th scope="col">Nama Panjang</th>
                    <th scope="col">Nama Singkatan</th>
                    <th scope="col">Data Lengkap</th>
                    <th scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @foreach($data as $ormawa)
    @if ((int)($ormawa->id_ormawa / 1000) === 1)
        <tr>
            <td>{{ $ormawa->id_ormawa }}</td>
            <td>{{ $ormawa->nama_ormawa }}</td>
            <td>{{ $ormawa->nama_singkatan }}</td>
            <td><a href="{{ route('informasi', $ormawa->id_ormawa) }}" class="btn btn-success">Informasi</a></td>
            <td>
                <button type="button" class="btn btn-warning" onClick="show()"><i class="lni lni-pencil"></i></button>
                <form action="{{ route('ormawa.destroy', $ormawa->id_ormawa) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger"><i class="lni lni-trash-can"></i></button>
                </form>
            </td>
        </tr>
    @endif
@endforeach

            </tbody>
        </table>
    </div>
    <div class="container">
        <h1 class="text-center mt-5 mb-4">Data UKM</h1>
        <div class="col-4">
        </div><br>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">Id Ormawa</th>
                    <th scope="col">Nama Panjang</th>
                    <th scope="col">Nama Singkatan</th>
                    <th scope="col">Data Lengkap</th>
                    <th scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @foreach($data as $ormawa)
            @if ((int)($ormawa->id_ormawa / 1000) === 2)
                <tr>
                    <td>{{ $ormawa->id_ormawa }}</td>
                    <td>{{ $ormawa->nama_ormawa }}</td>
                    <td>{{ $ormawa->nama_singkatan }}</td>  
                    <td><a href="{{ route('informasi', $ormawa->id_ormawa) }}"class="btn btn-success">Informasi</a></td>
                    <td><button type="button" class="btn btn-warning" onClick="show({{ $ormawa->id_ormawa }})"><i class="lni lni-pencil"></i></button>
                    <form action="{{ route('ormawa.destroy', $ormawa->id_ormawa) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger"><i class="lni lni-trash-can"></i></button>
                </form>
                    </td>
                </tr>
            @endif
                @endforeach
            </tbody>
        </table>
    </div>
    <!--Modal untuk menambahkan data-->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-center" id="exampleModalLabel">Judul Modal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="page" class="p-2"></div>
                </div>
            </div>
        </div>
    </div>


<!--script untuk pop up form-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
        $(document).ready(function() {
            read()
        });

        // Menampilkan data dari database
        function read() {
            $.get("{{ url('data-hima') }}", {}, function(data, status) {
                $("#table table-striped").html(data);
            });
        }
        //untuk menampilkan modal isi data ormawa
        function create() {
            $.get("{{ url('tambah-ormawa') }}", {}, function(data, status) {
                $("#exampleModalLabel").html('Masukkan Data Ormawa')
                $("#page").html(data);
                $("#exampleModal").modal('show');
            });
        }

        //untuk memasukkan data ke tabel ormawa
        function store() {
                 var id_ormawa = $("#id_ormawa").val();
                 var nama_ormawa = $("#nama_ormawa").val();
                 var nama_singkatan = $("#nama_singkatan").val();
                 $.ajax({
                   type: "get",
                   url: "{{ url('tambah-data') }}",
                   data: {
                       id_ormawa: id_ormawa,
                       nama_ormawa: nama_ormawa,
                       nama_singkatan: nama_singkatan
                         },
                   success: function(data) {
                    $(".btn-close").click();
                     read();
                     }
               });
            }

        // Untuk modal halaman edit show
        function show(id) {
    $.get("{{ url('show') }}/" + id, function(data, status) {
        $("#exampleModalLabel").html('Edit Product');
        $("#page").html(data);
        $("#exampleModal").modal('show');
    });
}


</script>

@endsection
