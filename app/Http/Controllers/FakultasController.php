<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Ormawa;
use App\Models\ProposalKegiatan;
use Illuminate\Pagination\Paginator;
use Exception;
use Illuminate\Support\Facades\Crypt;

class FakultasController extends Controller
{
//-------------------------------saintek-----------------------//
    public function ormawafst()
    {
        // Mengambil data kepengurusan ormawa
        $datafst = DB::table('detail_kepengurusan')
            ->join('kepengurusan_ormawa', 'detail_kepengurusan.id_detail_kepengurusan', '=', 'kepengurusan_ormawa.id_kepengurusan')
            ->join('ormawa', 'kepengurusan_ormawa.id_ormawa', '=', 'ormawa.id_ormawa')
            ->join('mahasiswa', 'detail_kepengurusan.npm', '=', 'mahasiswa.npm')
            ->join('jabatan', 'detail_kepengurusan.jabatan', '=', 'jabatan.id_jabatan')
            ->select(
                'ormawa.id_ormawa', // Tambahkan properti id_ormawa
                'ormawa.nama_ormawa',
                'ormawa.nama_singkatan',
                'mahasiswa.npm',
                'mahasiswa.nama_mahasiswa',
                'detail_kepengurusan.jabatan',
                DB::raw('(SELECT MAX(periode) FROM kepengurusan_ormawa) AS periode')
            )
            ->where('detail_kepengurusan.jabatan', 9)
            ->where('kepengurusan_ormawa.periode', '=', DB::raw('(SELECT MAX(`periode`) FROM `kepengurusan_ormawa`)'))
            ->where('ormawa.id_ormawa', 'LIKE', '12%')
            ->paginate(10);

        // Mengambil data proker dari tabel proker dengan kondisi id_ormawa LIKE '12%', termasuk nama_ormawa yang berelasi
        $dataProker = DB::table('proker')
            ->join('ormawa', 'proker.id_ormawa', '=', 'ormawa.id_ormawa')
            ->select('proker.*', 'ormawa.nama_ormawa')
            ->where('proker.id_ormawa', 'LIKE', '12%')
            ->paginate(8);

        // Mengembalikan view dengan data proker dan kepengurusan ormawa
        return view('fakultas.saintek.datafst', compact('datafst', 'dataProker'));
    }

    public function proposalkegiatanprokerFST()
    {
        // Mengambil data proposal kegiatan yang sesuai dengan kriteria
        $proposal = DB::table('proposal_kegiatan')
            ->select('proposal_kegiatan.judul_kegiatan', 'ormawa.nama_ormawa', 'proposal_kegiatan.id_proposal')
            ->join('ormawa', 'proposal_kegiatan.id_ormawa', '=', 'ormawa.id_ormawa')
            ->join('detail_proposal', 'proposal_kegiatan.id_proposal', '=', 'detail_proposal.id_proposal')
            ->where('proposal_kegiatan.jenis_proposal', '=', 'Proker')
            ->where('detail_proposal.status_dekanat', '=', 'Revisi')
            ->where('detail_proposal.status_wr3', '=', 'ACC')
            ->where('proposal_kegiatan.id_ormawa', 'LIKE', '12%')
            
            ->get();

        // Mengirim data proposal ke view 'prodi/proposalproker'
        return view('fakultas.saintek.dataproposalproker', ['proposal' => $proposal]);
    }

    public function  proposalkegiataninsidentilFST()
    {
        $proposal = DB::table('proposal_kegiatan')
            ->select('proposal_kegiatan.judul_kegiatan', 'ormawa.nama_ormawa', 'proposal_kegiatan.id_proposal')
            ->join('ormawa', 'proposal_kegiatan.id_ormawa', '=', 'ormawa.id_ormawa')
            ->join('detail_proposal', 'proposal_kegiatan.id_proposal', '=', 'detail_proposal.id_proposal')
            ->where('proposal_kegiatan.jenis_proposal', '=', 'Insidentil')
            ->where('detail_proposal.status_dekanat', '=', 'Revisi')
            ->where('detail_proposal.status_wr3', '=', 'ACC')
            ->where('proposal_kegiatan.id_ormawa', 'LIKE', '12%')
            ->get();

        return view('fakultas.saintek.dataproposalinsidentil', ['proposal' => $proposal]);
    }

    public function laporankegiatanFST()
    {
        $laporan = DB::table('laporan')
            ->select('laporan.judul_kegiatan', 'ormawa.nama_ormawa', 'laporan.id_laporan')
            ->join('ormawa', 'laporan.id_ormawa', '=', 'ormawa.id_ormawa')
            ->join('detail_laporan', 'laporan.id_laporan', '=', 'detail_laporan.id_laporan')
            ->where('laporan.jenis_laporan', '=', 'LPJ')
            ->where('detail_laporan.status_dekanat', '=', 'Revisi')
            ->where('laporan.id_ormawa', 'LIKE', '12%')
            ->paginate(2);

        return view('fakultas.saintek.laporankegiatan', ['laporan' => $laporan]);
    }

    public function laporantahunanFST()
    {
        $laporan = DB::table('laporan')
            ->select('laporan.judul_kegiatan', 'ormawa.nama_ormawa', 'laporan.id_laporan')
            ->join('ormawa', 'laporan.id_ormawa', '=', 'ormawa.id_ormawa')
            ->join('detail_laporan', 'laporan.id_laporan', '=', 'detail_laporan.id_laporan')
            ->where('laporan.jenis_laporan', '=', 'Tahunan')
            ->where('detail_laporan.status_dekanat', '=', 'Revisi')
            ->where('laporan.id_ormawa', 'LIKE', '12%')
            ->paginate(2);

        return view('fakultas.saintek.laporantahunan', ['laporan' => $laporan]);
    }

    public function monitoringkegiatanfst()
    {
        // Tampilkan list kegiatan untuk monitoring
        $monitoring = DB::table('monitoring')
            ->join('proposal_kegiatan', 'monitoring.id_proposal', '=', 'proposal_kegiatan.id_proposal')
            ->join('ormawa', 'proposal_kegiatan.id_ormawa', '=', 'ormawa.id_ormawa')
            ->select('proposal_kegiatan.judul_kegiatan', 'ormawa.nama_ormawa', 'proposal_kegiatan.id_proposal')
            ->where('monitoring.status', 'NOT OKE')
            ->where('ormawa.id_ormawa', 'LIKE', '12%')
            ->get();

        return view('fakultas.saintek.monitoring', ['monitoring' => $monitoring]);
    }

    public function updateproposalfakultas(Request $request, $id)
    {
        // Validasi data
        $request->validate([
            'tema' => 'required',
            'judul_kegiatan' => 'required',
            'latar_belakang' => 'required',
            'deskripsi_kegiatan' => 'required',
            'tujuan_kegiatan' => 'required',
            'manfaat_kegiatan' => 'required',
            'tempat_pelaksanaan' => 'required',
            'anggaran_kegiatan' => 'required|numeric',
            'anggaran_diajukan' => 'required|numeric',
        ]);
        // Update data proposal
        DB::table('proposal_kegiatan')
            ->where('id_proposal', $id)
            ->update([
                'tema' => $request->tema,
                'judul_kegiatan' => $request->judul_kegiatan,
                'latar_belakang' => $request->latar_belakang,
                'deskripsi_kegiatan' => $request->deskripsi_kegiatan,
                'tujuan_kegiatan' => $request->tujuan_kegiatan,
                'manfaat_kegiatan' => $request->manfaat_kegiatan,
                'tempat_pelaksanaan' => $request->tempat_pelaksanaan,
                'anggaran_kegiatan' => $request->anggaran_kegiatan,
                'anggaran_diajukan' => $request->anggaran_diajukan,
            ]);
        // Update status_dekanat menjadi 'Revisi' di tabel detail_proposal
        DB::table('detail_proposal')
            ->where('id_proposal', $id)
            ->update(['status_dekanat' => 'Revisi']);
        return redirect()->back()->with('success', 'Data proposal berhasil direvisi');
    }

    public function detailProposalFakultas($id_proposal)
    {
        $proposal = DB::table('proposal_kegiatan')
            ->where('id_proposal', $id_proposal)
            ->first();

        if ($proposal) {
            // Mengambil data waktu kegiatan
            $waktu_kegiatan = DB::table('detail_waktu_kegiatan_proposal')
                ->select('waktu_kegiatan')
                ->where('id_proposal', $id_proposal)
                ->get();

            // Menambahkan data waktu kegiatan ke dalam objek proposal
            $proposal->waktu_kegiatan = $waktu_kegiatan;
            return view('fakultas.saintek.detailproposal', compact('proposal'));
        } else {
            // Tangani jika proposal tidak ditemukan
            return view('fakultas.saintek.detailproposal')->with('error', 'Proposal tidak ditemukan.');
        }
    }

    public function showDetailLaporanFakultas($id_laporan)
    {
        $laporan = DB::table('laporan')
            ->where('id_laporan', $id_laporan)
            ->first();

        if (!$laporan) {
            return "Laporan tidak ditemukan";
        }
        return view('fakultas.saintek.detaillaporan', compact('laporan'));
    }

    public function detailmonitorkegiatanFakultas($id)
    {
        // Mengambil data detail berdasarkan id proposal
        $detail = DB::table('monitoring')
            ->join('proposal_kegiatan', 'proposal_kegiatan.id_proposal', '=', 'monitoring.id_proposal')
            ->join('ormawa', 'proposal_kegiatan.id_ormawa', '=', 'ormawa.id_ormawa')
            ->where('monitoring.id_proposal', $id)
            ->where('monitoring.status', 'NOT OKE')
            ->select('proposal_kegiatan.judul_kegiatan', 'monitoring.keterangan', 'monitoring.foto', 'monitoring.waktu', 'monitoring.id_proposal')
            ->get();

        return view('fakultas.saintek.detailmonitoring', ['detail' => $detail]);
    }

    public function accProposalFakultas($id)
    {
        // Perbarui status_kaprodi menjadi 'ACC'
        DB::table('detail_proposal')
            ->where('id_proposal', $id)
            ->update(['status_dekanat' => 'ACC']);

        // Redirect kembali ke halaman sebelumnya
        return redirect()->back()->with('success', 'Proposal berhasil disetujui');
    }

    public function updatelaporanFakultas(Request $request, $id)
    {
        // Validasi data
        $request->validate([
            'judul_kegiatan' => 'required',
            'rencana_kegiatan' => 'required',
            'relasi_kegiatan' => 'required',
            'evaluasi' => 'required',
            'penggunaan_dana' => 'required',
            'penutup' => 'required',
        ]);
        // Update data laporan
        DB::table('laporan')
            ->where('id_laporan', $id)
            ->update([
                'judul_kegiatan' => $request->judul_kegiatan,
                'rencana_kegiatan' => $request->rencana_kegiatan,
                'relasi_kegiatan' => $request->relasi_kegiatan,
                'evaluasi' => $request->evaluasi,
                'penggunaan_dana' => $request->penggunaan_dana,
                'penutup' => $request->penutup,
            ]);

        // Redirect dengan pesan sukses
        return redirect()->back()->with('success', 'Laporan berhasil diperbarui');
    }
    public function acclaporanfakultas($id)
    {
        $laporan = DB::table('detail_laporan')
            ->where('id_laporan', $id)
            ->first();

        if (!$laporan) {
            return redirect()->back()->with('error', 'Laporan tidak ditemukan');
        }

        // Lakukan pembaruan kolom status_kemahasiswaan menjadi 'ACC'
        DB::table('detail_laporan')
            ->where('id_laporan', $id)
            ->update(['status_dekanat' => 'ACC']);

        // Redirect kembali ke halaman sebelumnya
        return redirect()->back()->with('success', 'Laporan berhasil di-ACC');
    }

    public function datakepengurusanormawaFakultas($encryptedId)
    {
        $id = Crypt::decryptString($encryptedId);
        // Ambil data berdasarkan ID
        $ormawa = DB::table('ormawa')->where('id_ormawa', $id)->first();

        // Pastikan ormawa ditemukan
        if (!$ormawa) {
            return redirect()->back()->with('error', 'Ormawa tidak ditemukan');
        }

        // Mendapatkan nilai singkatan nama dari objek ormawa
        $nama_singkatan = $ormawa->nama_singkatan;

        $databph = DB::table('kepengurusan_ormawa')
            ->join('detail_kepengurusan', 'kepengurusan_ormawa.id_kepengurusan', '=', 'detail_kepengurusan.id_detail_kepengurusan')
            ->join('jabatan', 'detail_kepengurusan.jabatan', '=', 'jabatan.id_jabatan')
            ->join('mahasiswa', 'detail_kepengurusan.npm', '=', 'mahasiswa.npm')
            ->join('ormawa', 'kepengurusan_ormawa.id_ormawa', '=', 'ormawa.id_ormawa')
            ->select(
                'mahasiswa.npm as npm',
                'mahasiswa.nama_mahasiswa as nama_mahasiswa',
                'jabatan.nama_jabatan as nama_jabatan',
                'kepengurusan_ormawa.periode as periode'
            )
            ->where('ormawa.id_ormawa', $ormawa->id_ormawa)
            ->whereRaw('kepengurusan_ormawa.periode = (SELECT MAX(periode) FROM kepengurusan_ormawa WHERE id_ormawa = ?)', [$ormawa->id_ormawa])
            ->get();

        $datadivisi = DB::table('detail_kepengurusan_divisi')
            ->join('kepengurusan_ormawa', 'detail_kepengurusan_divisi.id_detail_kepengurusan', '=', 'kepengurusan_ormawa.id_kepengurusan')
            ->join('ormawa', 'kepengurusan_ormawa.id_ormawa', '=', 'ormawa.id_ormawa')
            ->join('jabatan', 'detail_kepengurusan_divisi.jabatan', '=', 'jabatan.id_jabatan')
            ->join('divisi', 'detail_kepengurusan_divisi.id_divisi', '=', 'divisi.id_divisi')
            ->join('mahasiswa', 'detail_kepengurusan_divisi.npm', '=', 'mahasiswa.npm')
            ->select(
                'kepengurusan_ormawa.periode as periode',
                'mahasiswa.npm as npm',
                'mahasiswa.nama_mahasiswa as nama_mahasiswa',
                'divisi.nama_divisi as nama_divisi',
                'jabatan.nama_jabatan as nama_jabatan'
            )
            ->where('ormawa.id_ormawa', $ormawa->id_ormawa)
            ->whereRaw('kepengurusan_ormawa.periode = (SELECT MAX(periode) FROM kepengurusan_ormawa WHERE id_ormawa = ?)', [$ormawa->id_ormawa])
            ->paginate(5);

        return view('fakultas.saintek.detailormawa', compact('encryptedId', 'ormawa', 'databph', 'datadivisi', 'nama_singkatan'));
    }

    public function datadetailprokerFakultas($encryptedId)
    {
        // Mengenkripsi ID proker
        $id_proker = Crypt::decryptString($encryptedId);

        // Mengambil data proker berdasarkan id_proker
        $proker = DB::table('proker')
            ->where('id_proker', $id_proker)
            ->first();
        if (!$proker) {
            return redirect()->back()->with('error', 'Proker tidak ditemukan.');
        }
        $id_ormawa = $proker->id_ormawa;
        $ormawa = DB::table('ormawa')
            ->where('id_ormawa', $id_ormawa)
            ->first();
        if (!$ormawa) {
            return redirect()->back()->with('error', 'Ormawa tidak ditemukan.');
        }
        // Mendapatkan nilai singkatan nama dari objek ormawa
        $nama_singkatan = $ormawa->nama_singkatan;
        // Tampilkan view dengan data proker, id enkripsi, dan nama singkatan
        return view('fakultas.saintek.detailproker', compact('proker', 'encryptedId', 'nama_singkatan'));
    }



    //-------------------------------bishum-----------------------//
    public function ormawabishum()
    {
        // Mengambil data kepengurusan ormawa
        $databishum = DB::table('detail_kepengurusan')
            ->join('kepengurusan_ormawa', 'detail_kepengurusan.id_detail_kepengurusan', '=', 'kepengurusan_ormawa.id_kepengurusan')
            ->join('ormawa', 'kepengurusan_ormawa.id_ormawa', '=', 'ormawa.id_ormawa')
            ->join('mahasiswa', 'detail_kepengurusan.npm', '=', 'mahasiswa.npm')
            ->join('jabatan', 'detail_kepengurusan.jabatan', '=', 'jabatan.id_jabatan')
            ->select(
                'ormawa.id_ormawa', // Tambahkan properti id_ormawa
                'ormawa.nama_ormawa',
                'ormawa.nama_singkatan',
                'mahasiswa.npm',
                'mahasiswa.nama_mahasiswa',
                'detail_kepengurusan.jabatan',
                DB::raw('(SELECT MAX(periode) FROM kepengurusan_ormawa) AS periode')
            )
            ->where('detail_kepengurusan.jabatan', 9)
            ->where('kepengurusan_ormawa.periode', '=', DB::raw('(SELECT MAX(`periode`) FROM `kepengurusan_ormawa`)'))
            ->where('ormawa.id_ormawa', 'LIKE', '11%')
            ->paginate(10);

        // Mengambil data proker dari tabel proker dengan kondisi id_ormawa LIKE '12%', termasuk nama_ormawa yang berelasi
        $dataProker = DB::table('proker')
            ->join('ormawa', 'proker.id_ormawa', '=', 'ormawa.id_ormawa')
            ->select('proker.*', 'ormawa.nama_ormawa')
            ->where('proker.id_ormawa', 'LIKE', '11%')
            ->paginate(8);

        return view('fakultas.bishum.databishum', compact('databishum', 'dataProker'));
    }

    public function proposalkegiatanprokerbishum()
    {
        // Mengambil data proposal kegiatan yang sesuai dengan kriteria
        $proposal = DB::table('proposal_kegiatan')
            ->select('proposal_kegiatan.judul_kegiatan', 'ormawa.nama_ormawa', 'proposal_kegiatan.id_proposal')
            ->join('ormawa', 'proposal_kegiatan.id_ormawa', '=', 'ormawa.id_ormawa')
            ->join('detail_proposal', 'proposal_kegiatan.id_proposal', '=', 'detail_proposal.id_proposal')
            ->where('proposal_kegiatan.jenis_proposal', '=', 'Proker')
            ->where('detail_proposal.status_dekanat', '=', 'Revisi')
            ->where('detail_proposal.status_wr3', '=', 'ACC')
            ->where('proposal_kegiatan.id_ormawa', 'LIKE', '11%')
            ->get();

        // Mengirim data proposal ke view 'prodi/proposalproker'
        return view('fakultas.bishum.dataproposalproker', ['proposal' => $proposal]);
    }

    public function  proposalkegiataninsidentilbishum()
    {
        $proposal = DB::table('proposal_kegiatan')
            ->select('proposal_kegiatan.judul_kegiatan', 'ormawa.nama_ormawa', 'proposal_kegiatan.id_proposal')
            ->join('ormawa', 'proposal_kegiatan.id_ormawa', '=', 'ormawa.id_ormawa')
            ->join('detail_proposal', 'proposal_kegiatan.id_proposal', '=', 'detail_proposal.id_proposal')
            ->where('proposal_kegiatan.jenis_proposal', '=', 'Insidentil')
            ->where('detail_proposal.status_dekanat', '=', 'Revisi')
            ->where('detail_proposal.status_wr3', '=', 'ACC')
            ->where('proposal_kegiatan.id_ormawa', 'LIKE', '11%')
            ->get();

        return view('fakultas.bishum.dataproposalinsidentil', ['proposal' => $proposal]);
    }

    public function laporankegiatanbishum()
    {
        $laporan = DB::table('laporan')
            ->select('laporan.judul_kegiatan', 'ormawa.nama_ormawa', 'laporan.id_laporan')
            ->join('ormawa', 'laporan.id_ormawa', '=', 'ormawa.id_ormawa')
            ->join('detail_laporan', 'laporan.id_laporan', '=', 'detail_laporan.id_laporan')
            ->where('laporan.jenis_laporan', '=', 'LPJ')
            ->where('detail_laporan.status_dekanat', '=', 'Revisi')
            ->where('laporan.id_ormawa', 'LIKE', '11%')
            ->paginate(2);

        return view('fakultas.bishum.laporankegiatan', ['laporan' => $laporan]);
    }

    public function laporantahunanbishum()
    {
        $laporan = DB::table('laporan')
            ->select('laporan.judul_kegiatan', 'ormawa.nama_ormawa', 'laporan.id_laporan')
            ->join('ormawa', 'laporan.id_ormawa', '=', 'ormawa.id_ormawa')
            ->join('detail_laporan', 'laporan.id_laporan', '=', 'detail_laporan.id_laporan')
            ->where('laporan.jenis_laporan', '=', 'Tahunan')
            ->where('detail_laporan.status_dekanat', '=', 'Revisi')
            ->where('laporan.id_ormawa', 'LIKE', '11%')
            ->paginate(2);

        return view('fakultas.bishum.laporantahunan', ['laporan' => $laporan]);
    }

    public function monitoringkegiatanbishum()
    {
        // Tampilkan list kegiatan untuk monitoring
        $monitoring = DB::table('monitoring')
            ->join('proposal_kegiatan', 'monitoring.id_proposal', '=', 'proposal_kegiatan.id_proposal')
            ->join('ormawa', 'proposal_kegiatan.id_ormawa', '=', 'ormawa.id_ormawa')
            ->select('proposal_kegiatan.judul_kegiatan', 'ormawa.nama_ormawa', 'proposal_kegiatan.id_proposal')
            ->where('monitoring.status', 'NOT OKE')
            ->where('ormawa.id_ormawa', 'LIKE', '11%')
            ->get();

        return view('fakultas.bishum.monitoring', ['monitoring' => $monitoring]);
    }







    public function updateproposalfakultasbishum(Request $request, $id)
    {
        // Validasi data
        $request->validate([
            'tema' => 'required',
            'judul_kegiatan' => 'required',
            'latar_belakang' => 'required',
            'deskripsi_kegiatan' => 'required',
            'tujuan_kegiatan' => 'required',
            'manfaat_kegiatan' => 'required',
            'tempat_pelaksanaan' => 'required',
            'anggaran_kegiatan' => 'required|numeric',
            'anggaran_diajukan' => 'required|numeric',
        ]);
        // Update data proposal
        DB::table('proposal_kegiatan')
            ->where('id_proposal', $id)
            ->update([
                'tema' => $request->tema,
                'judul_kegiatan' => $request->judul_kegiatan,
                'latar_belakang' => $request->latar_belakang,
                'deskripsi_kegiatan' => $request->deskripsi_kegiatan,
                'tujuan_kegiatan' => $request->tujuan_kegiatan,
                'manfaat_kegiatan' => $request->manfaat_kegiatan,
                'tempat_pelaksanaan' => $request->tempat_pelaksanaan,
                'anggaran_kegiatan' => $request->anggaran_kegiatan,
                'anggaran_diajukan' => $request->anggaran_diajukan,
            ]);
        // Update status_dekanat menjadi 'Revisi' di tabel detail_proposal
        DB::table('detail_proposal')
            ->where('id_proposal', $id)
            ->update(['status_dekanat' => 'Revisi']);
        return redirect()->back()->with('success', 'Data proposal berhasil direvisi');
    }

    public function detailProposalfakultasbishum($id_proposal)
    {
        $proposal = DB::table('proposal_kegiatan')
            ->where('id_proposal', $id_proposal)
            ->first();

        if ($proposal) {
            // Mengambil data waktu kegiatan
            $waktu_kegiatan = DB::table('detail_waktu_kegiatan_proposal')
                ->select('waktu_kegiatan')
                ->where('id_proposal', $id_proposal)
                ->get();

            // Menambahkan data waktu kegiatan ke dalam objek proposal
            $proposal->waktu_kegiatan = $waktu_kegiatan;
            return view('fakultas.bishum.detailproposal', compact('proposal'));
        } else {
            // Tangani jika proposal tidak ditemukan
            return view('fakultas.bishum.detailproposal')->with('error', 'Proposal tidak ditemukan.');
        }
    }

    public function showDetailLaporanfakultasbishum($id_laporan)
    {
        $laporan = DB::table('laporan')
            ->where('id_laporan', $id_laporan)
            ->first();

        if (!$laporan) {
            return "Laporan tidak ditemukan";
        }
        return view('fakultas.bishum.detaillaporan', compact('laporan'));
    }

    public function detailmonitorkegiatanfakultasbishum($id)
    {
        // Mengambil data detail berdasarkan id proposal
        $detail = DB::table('monitoring')
            ->join('proposal_kegiatan', 'proposal_kegiatan.id_proposal', '=', 'monitoring.id_proposal')
            ->join('ormawa', 'proposal_kegiatan.id_ormawa', '=', 'ormawa.id_ormawa')
            ->where('monitoring.id_proposal', $id)
            ->where('monitoring.status', 'NOT OKE')
            ->select('proposal_kegiatan.judul_kegiatan', 'monitoring.keterangan', 'monitoring.foto', 'monitoring.waktu', 'monitoring.id_proposal')
            ->get();

        return view('fakultas.bishum.detailmonitoring', ['detail' => $detail]);
    }

    public function accProposalfakultasbishum($id)
    {
        // Perbarui status_kaprodi menjadi 'ACC'
        DB::table('detail_proposal')
            ->where('id_proposal', $id)
            ->update(['status_dekanat' => 'ACC']);

        // Redirect kembali ke halaman sebelumnya
        return redirect()->back()->with('success', 'Proposal berhasil disetujui');
    }

    public function updatelaporanfakultasbishum(Request $request, $id)
    {
        // Validasi data
        $request->validate([
            'judul_kegiatan' => 'required',
            'rencana_kegiatan' => 'required',
            'relasi_kegiatan' => 'required',
            'evaluasi' => 'required',
            'penggunaan_dana' => 'required',
            'penutup' => 'required',
        ]);
        // Update data laporan
        DB::table('laporan')
            ->where('id_laporan', $id)
            ->update([
                'judul_kegiatan' => $request->judul_kegiatan,
                'rencana_kegiatan' => $request->rencana_kegiatan,
                'relasi_kegiatan' => $request->relasi_kegiatan,
                'evaluasi' => $request->evaluasi,
                'penggunaan_dana' => $request->penggunaan_dana,
                'penutup' => $request->penutup,
            ]);

        // Redirect dengan pesan sukses
        return redirect()->back()->with('success', 'Laporan berhasil diperbarui');
    }
    public function acclaporanfakultasbishum($id)
    {
        $laporan = DB::table('detail_laporan')
            ->where('id_laporan', $id)
            ->first();

        if (!$laporan) {
            return redirect()->back()->with('error', 'Laporan tidak ditemukan');
        }

        // Lakukan pembaruan kolom status_kemahasiswaan menjadi 'ACC'
        DB::table('detail_laporan')
            ->where('id_laporan', $id)
            ->update(['status_dekanat' => 'ACC']);

        // Redirect kembali ke halaman sebelumnya
        return redirect()->back()->with('success', 'Laporan berhasil di-ACC');
    }

    public function datakepengurusanormawafakultasbishum($encryptedId)
    {
        $id = Crypt::decryptString($encryptedId);
        // Ambil data berdasarkan ID
        $ormawa = DB::table('ormawa')->where('id_ormawa', $id)->first();

        // Pastikan ormawa ditemukan
        if (!$ormawa) {
            return redirect()->back()->with('error', 'Ormawa tidak ditemukan');
        }

        // Mendapatkan nilai singkatan nama dari objek ormawa
        $nama_singkatan = $ormawa->nama_singkatan;

        $databph = DB::table('kepengurusan_ormawa')
            ->join('detail_kepengurusan', 'kepengurusan_ormawa.id_kepengurusan', '=', 'detail_kepengurusan.id_detail_kepengurusan')
            ->join('jabatan', 'detail_kepengurusan.jabatan', '=', 'jabatan.id_jabatan')
            ->join('mahasiswa', 'detail_kepengurusan.npm', '=', 'mahasiswa.npm')
            ->join('ormawa', 'kepengurusan_ormawa.id_ormawa', '=', 'ormawa.id_ormawa')
            ->select(
                'mahasiswa.npm as npm',
                'mahasiswa.nama_mahasiswa as nama_mahasiswa',
                'jabatan.nama_jabatan as nama_jabatan',
                'kepengurusan_ormawa.periode as periode'
            )
            ->where('ormawa.id_ormawa', $ormawa->id_ormawa)
            ->whereRaw('kepengurusan_ormawa.periode = (SELECT MAX(periode) FROM kepengurusan_ormawa WHERE id_ormawa = ?)', [$ormawa->id_ormawa])
            ->get();

        $datadivisi = DB::table('detail_kepengurusan_divisi')
            ->join('kepengurusan_ormawa', 'detail_kepengurusan_divisi.id_detail_kepengurusan', '=', 'kepengurusan_ormawa.id_kepengurusan')
            ->join('ormawa', 'kepengurusan_ormawa.id_ormawa', '=', 'ormawa.id_ormawa')
            ->join('jabatan', 'detail_kepengurusan_divisi.jabatan', '=', 'jabatan.id_jabatan')
            ->join('divisi', 'detail_kepengurusan_divisi.id_divisi', '=', 'divisi.id_divisi')
            ->join('mahasiswa', 'detail_kepengurusan_divisi.npm', '=', 'mahasiswa.npm')
            ->select(
                'kepengurusan_ormawa.periode as periode',
                'mahasiswa.npm as npm',
                'mahasiswa.nama_mahasiswa as nama_mahasiswa',
                'divisi.nama_divisi as nama_divisi',
                'jabatan.nama_jabatan as nama_jabatan'
            )
            ->where('ormawa.id_ormawa', $ormawa->id_ormawa)
            ->whereRaw('kepengurusan_ormawa.periode = (SELECT MAX(periode) FROM kepengurusan_ormawa WHERE id_ormawa = ?)', [$ormawa->id_ormawa])
            ->paginate(5);

        return view('fakultas.bishum.detailormawa', compact('encryptedId', 'ormawa', 'databph', 'datadivisi', 'nama_singkatan'));
    }

    public function datadetailprokerfakultasbishum($encryptedId)
    {
        // Mengenkripsi ID proker
        $id_proker = Crypt::decryptString($encryptedId);

        // Mengambil data proker berdasarkan id_proker
        $proker = DB::table('proker')
            ->where('id_proker', $id_proker)
            ->first();
        if (!$proker) {
            return redirect()->back()->with('error', 'Proker tidak ditemukan.');
        }
        $id_ormawa = $proker->id_ormawa;
        $ormawa = DB::table('ormawa')
            ->where('id_ormawa', $id_ormawa)
            ->first();
        if (!$ormawa) {
            return redirect()->back()->with('error', 'Ormawa tidak ditemukan.');
        }
        // Mendapatkan nilai singkatan nama dari objek ormawa
        $nama_singkatan = $ormawa->nama_singkatan;
        // Tampilkan view dengan data proker, id enkripsi, dan nama singkatan
        return view('fakultas.bishum.detailproker', compact('proker', 'encryptedId', 'nama_singkatan'));
    }


























    //-------------------------------diploma-----------------------//
    public function ormawadiploma()
    {
        // Mengambil data kepengurusan ormawa
        $datadiploma = DB::table('detail_kepengurusan')
            ->join('kepengurusan_ormawa', 'detail_kepengurusan.id_detail_kepengurusan', '=', 'kepengurusan_ormawa.id_kepengurusan')
            ->join('ormawa', 'kepengurusan_ormawa.id_ormawa', '=', 'ormawa.id_ormawa')
            ->join('mahasiswa', 'detail_kepengurusan.npm', '=', 'mahasiswa.npm')
            ->join('jabatan', 'detail_kepengurusan.jabatan', '=', 'jabatan.id_jabatan')
            ->select(
                'ormawa.nama_ormawa',
                'ormawa.nama_singkatan',
                'mahasiswa.npm',
                'mahasiswa.nama_mahasiswa',
                'detail_kepengurusan.jabatan',
                DB::raw('(SELECT MAX(periode) FROM kepengurusan_ormawa) AS periode')
            )
            ->where('detail_kepengurusan.jabatan', 9)
            ->where('kepengurusan_ormawa.periode', '=', DB::raw('(SELECT MAX(`periode`) FROM `kepengurusan_ormawa`)'))
            ->where('ormawa.id_ormawa', 'LIKE', '13%')
            ->paginate(10);

        // Mengambil data proker dari tabel proker dengan kondisi id_ormawa LIKE '12%', termasuk nama_ormawa yang berelasi
        $dataProker = DB::table('proker')
            ->join('ormawa', 'proker.id_ormawa', '=', 'ormawa.id_ormawa')
            ->select('proker.*', 'ormawa.nama_ormawa')
            ->where('proker.id_ormawa', 'LIKE', '13%')
            ->paginate(8);

        return view('fakultas/diploma/databishum', compact('datadiploma', 'dataProker'));
    }
}
