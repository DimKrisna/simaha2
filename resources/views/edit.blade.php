<div class="p2">
    <div class="form-group">
        <label for="id_ormawa" class="form-label">ID Ormawa</label>
        <input type="number" name="id_ormawa" id="id_ormawa" class="form-control" value="{{ $data->id_ormawa }}" placeholder="Id ">
    </div>
    <div class="form-group">
        <label for="nama_ormawa" class="form-label">Nama Ormawa</label>
        <input type="text" name="nama_ormawa" id="nama_ormawa" class="form-control" value="{{ $data->nama_ormawa }}" placeholder="Nama Ormawa">
    </div>
    <div class="form-group">
        <label for="nama_singkatan" class="form-label">Nama Singkatan</label>
        <input type="text" name="nama_singkatan" id="nama_singkatan" class="form-control" value="{{ $data->nama_singkatan }}" placeholder="Singkatan">
    </div>
    <div class="form-group mt-2">
        <button class="btn btn-warning" onclick="editData()">Edit</button>
    </div>
</div>