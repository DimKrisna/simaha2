@extends('sidebarrektor')

@section('content')
    <div class="container">
        <div class="form-container">
            <h1>Detail Proposal Kegiatan</h1>
            @if ($proposal)
                <br>
                <table class="table table-striped table-bordered text-center">
                    <thead>
                        <tr>
                            <th scope="col">Status Prodi</th>
                            <th scope="col">Status Kemahasiswaan</th>
                            <th scope="col">Status Wakil Rektor 3</th>
                            <th scope="col">Status Fakultas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $proposal->statuses ? $proposal->statuses->status_kaprodi : 'N/A' }}</td>
                            <td>{{ $proposal->statuses ? $proposal->statuses->status_kemahasiswaan : 'N/A' }}</td>
                            <td>{{ $proposal->statuses ? $proposal->statuses->status_wr3 : 'N/A' }}</td>
                            <td>{{ $proposal->statuses ? $proposal->statuses->status_dekanat : 'N/A' }}</td>
                        </tr>
                    </tbody>
                </table>
                <br>
                <form id="proposal_form" action="{{ route('updateproposalrektor', $proposal->id_proposal) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-row">
                        <div class="form-group">
                            <label for="tema">Tema:</label>
                            <input type="text" id="tema" name="tema" value="{{ $proposal->tema }}" >
                        </div>

                        <div class="form-group">
                            <label for="judul_kegiatan">Judul Kegiatan:</label>
                            <input type="text" id="judul_kegiatan" name="judul_kegiatan"
                                value="{{ $proposal->judul_kegiatan }}" >
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="latar_belakang">Latar Belakang:</label>
                            <textarea id="latar_belakang" name="latar_belakang" rows="4" >{{ $proposal->latar_belakang }} (masukan revisi jika ada)</textarea>
                        </div>

                        <div class="form-group">
                            <label for="deskripsi_kegiatan">Deskripsi Kegiatan:</label>
                            <textarea id="deskripsi_kegiatan" name="deskripsi_kegiatan" rows="4" >{{ $proposal->deskripsi_kegiatan }}(masukan revisi jika ada)</textarea>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="tujuan_kegiatan">Tujuan Kegiatan:</label>
                            <textarea id="tujuan_kegiatan" name="tujuan_kegiatan" rows="4" >{{ $proposal->tujuan_kegiatan }}(masukan revisi jika ada)</textarea>
                        </div>

                        <div class="form-group">
                            <label for="manfaat_kegiatan">Manfaat Kegiatan:</label>
                            <textarea id="manfaat_kegiatan" name="manfaat_kegiatan" rows="4" >{{ $proposal->manfaat_kegiatan }}(masukan revisi jika ada)</textarea>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="tempat_pelaksanaan">Tempat Pelaksanaan:</label>
                            <input type="text" id="tempat_pelaksanaan" name="tempat_pelaksanaan"
                                value="{{ $proposal->tempat_pelaksanaan }}" readonly>
                        </div>

                        <div class="form-group">
                            <label for="anggaran_kegiatan">Anggaran Kegiatan:</label>
                            <input type="number" id="anggaran_kegiatan" name="anggaran_kegiatan"
                                value="{{ $proposal->anggaran_kegiatan }}" readonly>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="anggaran_diajukan">Anggaran Diajukan:</label>
                            <input type="number" id="anggaran_diajukan" name="anggaran_diajukan"
                                value="{{ $proposal->anggaran_diajukan }}" readonly>
                        </div>
                    </div>

                    @if ($proposal->waktu_kegiatan)
                        <div class="form-row">
                            <div class="form-group">
                                <label for="waktu_kegiatan">Waktu Kegiatan:</label>
                                <ul>
                                    @foreach ($proposal->waktu_kegiatan as $waktu)
                                        <li>
                                            <input type="text"
                                                value="{{ date('d-m-Y', strtotime($waktu->waktu_kegiatan)) }}" readonly>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                    <br>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="lampiran">Lampiran :</label>
                            <a href="{{route ('showlampiranproposal', $proposal->id_proposal)}}" class="btn btn-primary">Lihat Lampiran</a>
                        </div>
                        <br>
                    </div>
                    </div><br>
                    <div class="button-container">
                        <!-- Tombol Revisi -->
                        <form action="{{ route('updateproposalrektor', ['id' => $proposal->id_proposal]) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-secondary" name="revisi" value="1">
                                <i class="lni lni-arrow-left-circle"></i> Revisi
                            </button>
                        </form>

                        <!-- Tombol Back -->
                        <a class="btn btn-danger" href="{{ route('proposalinsidentilrektor') }}" role="button">
                            <i class="lni lni-arrow-left-circle"></i> Back
                        </a>
                    </div>
                </form>

            </form>
            <!-- Tombol ACC -->
            <form action="{{ route('accProposal_rektor', $proposal->id_proposal) }}" method="POST" style="display: inline;">
              @csrf
              @method('PUT')
              <button type="submit" class="btn btn-success">
                  <i class="lni lni-checkmark-circle"></i> ACC
              </button>
          </form>
            @else
                <p>Proposal tidak ditemukan.</p>
            @endif
        </div>
    </div>
    <br>
@endsection
