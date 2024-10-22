<!-- Modal untuk menambahkan data kepengurusan_ormawa -->
<div class="modal fade" id="modalTambahKepengurusan" tabindex="-1" aria-labelledby="modalTambahKepengurusanLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTambahKepengurusanLabel">Tambah Kepengurusan Ormawa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formTambahKepengurusan">
                    <div class="mb-3">
                        <label for="periode" class="form-label">Periode</label>
                        <input type="text" class="form-control" id="periode" name="periode" readonly>
                    </div>
                    <!-- Tambahan input yang tersembunyi untuk id_ormawa -->
                    <input type="hidden" id="id_ormawa" name="id_ormawa">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>
