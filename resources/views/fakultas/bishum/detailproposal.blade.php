@extends('sidebarfakultasbishum')

@section('content')
    <div class="container">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="form-container mt-4">
            <h1> Data Proposal Kegiatan</h1>

            <label for="tema">Tema:</label>
            <input type="text" id="tema" name="tema" value="{{ $proposal->tema }}" readonly>

            <label for="judul_kegiatan">Judul Kegiatan:</label>
            <input type="text" id="judul_kegiatan" name="judul_kegiatan" value="{{ $proposal->judul_kegiatan }}"
                readonly>

            <label for="judul_kegiatan">Jenis Proposal:</label>
            <input type="text" id="jenis_proposal" name="jenis_proposal" value="{{ $proposal->jenis_proposal }}"
                readonly>

            <label for="latar_belakang">Latar Belakang:</label>
            <textarea id="latar_belakang" name="latar_belakang" rows="4" readonly>{{ $proposal->latar_belakang }} </textarea>

            <label for="deskripsi_kegiatan">Deskripsi Kegiatan:</label>
            <textarea id="deskripsi_kegiatan" name="deskripsi_kegiatan" rows="4" readonly>{{ $proposal->deskripsi_kegiatan }}</textarea>

            <label for="tujuan_kegiatan">Tujuan Kegiatan:</label>
            <textarea id="tujuan_kegiatan" name="tujuan_kegiatan" rows="4" readonly>{{ $proposal->tujuan_kegiatan }} </textarea>

            <label for="manfaat_kegiatan">Manfaat Kegiatan:</label>
            <textarea id="manfaat_kegiatan" name="manfaat_kegiatan" rows="4" readonly>{{ $proposal->manfaat_kegiatan }} </textarea>

            <label for="tempat_pelaksanaan">Tempat Pelaksanaan:</label>
            <input type="text" id="tempat_pelaksanaan" name="tempat_pelaksanaan"
                value="{{ $proposal->tempat_pelaksanaan }}" readonly>

            <label for="anggaran_kegiatan">Anggaran Kegiatan:</label>
            <input type="text" id="anggaran_kegiatan" name="anggaran_kegiatan"
                value="{{ 'Rp ' . number_format($proposal->anggaran_kegiatan, 0, ',', '.') }}" readonly>

            <label for="anggaran_diajukan">Anggaran Diajukan:</label>
            <input type="text" id="anggaran_diajukan" name="anggaran_diajukan"
                value="{{ 'Rp ' . number_format($proposal->anggaran_diajukan, 0, ',', '.') }}" readonly>

            @if ($proposal->waktu_kegiatan)
                <div class="form-row">
                    <div class="form-group">
                        <label for="waktu_kegiatan">Waktu Kegiatan:</label>
                        <ul>
                            @foreach ($proposal->waktu_kegiatan as $waktu)
                                <li>
                                    <input type="text" value="{{ date('d-m-Y', strtotime($waktu->waktu_kegiatan)) }}"
                                        readonly>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="form-row">
                <div class="form-group">
                    <label for="lampiran">Lampiran :</label>
                    <a href="{{ route('showlampiranproposal', $proposal->id_proposal) }}" class="btn btn-primary">Lihat
                        Lampiran</a>
                </div>
                <br>
            </div>

            <hr>

            <div class="d-flex align-items-center mb-2">
                <div class="w-20 fw-bold">Status Pengajuan Tingkat Fakultas</div>
                <div class="me-2">:</div>
            </div>
            <form action="{{ route('update.proposalFakultasBishum', ['id' => $proposal->id_proposal]) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group mb-4 p-3 border rounded shadow-sm bg-light">
                    <div class="row align-items-center mb-3">
                        <div class="col-md-3 fw-bold">Status Proposal:</div>
                        <div class="col-md-9">
                            <select class="form-select w-50" id="status_dekanat" name="status_dekanat" required>
                                <option value="" disabled {{ $proposal->status_dekanat == '' ? 'selected' : '' }}>---</option>
                                <option value="Acc" {{ $proposal->status_dekanat == 'Acc' ? 'selected' : '' }}>Acc</option>
                                <option value="Revisi" {{ $proposal->status_dekanat == 'Revisi' ? 'selected' : '' }}>Revisi</option>
                                <option value="Tolak" {{ $proposal->status_dekanat == 'Tolak' ? 'selected' : '' }}>Tolak</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 fw-bold">
                            <label for="catatan_fakultas" class="form-label">Catatan Fakultas:</label>
                        </div>
                        <div class="col-md-9">
                            <textarea name="catatan_fakultas" id="catatan_fakultas" class="form-control" placeholder="Masukkan catatan" {{ $proposal->status_dekanat == 'Revisi' || $proposal->status_dekanat == 'Tolak' ? '' : 'disabled' }}>
                                {{ $proposal->catatan_fakultas ?? '' }}
                            </textarea>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end gap-3 mt-4">
                    <!-- Back Button -->
                    <a class="btn btn-danger d-flex align-items-center" href="{{ route('proposalkegiatanprokerbishum') }}"
                        role="button">
                        <i class="lni lni-arrow-left-circle me-2"></i> Back
                    </a>
                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary d-flex align-items-center">
                        <i class="lni lni-save me-2"></i> Update
                    </button>
                </div>
            </form>

            <script>
                document.getElementById('status_dekanat').addEventListener('change', function() {
                    var catatanFakultas = document.getElementById('catatan_fakultas');
                    if (this.value === 'Revisi' || this.value === 'Tolak') {
                        catatanFakultas.disabled = false; // Enable textarea
                    } else {
                        catatanFakultas.disabled = true; // Disable textarea
                        catatanFakultas.value = ''; // Clear textarea if disabled
                    }
                });
            </script>
        @endsection
