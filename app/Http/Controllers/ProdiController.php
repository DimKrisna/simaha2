<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\ProposalKegiatan;
use App\Models\Ormawa;

class ProdiController extends Controller
{
    public function DataProdi()
    {
        $id_ormawa = Auth::user()->id_ormawa;

        $nama_ormawa = Ormawa::where('id_ormawa', $id_ormawa)->value('nama_ormawa');

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
            ->where('ormawa.id_ormawa', $id_ormawa)
            ->whereRaw('kepengurusan_ormawa.periode = (SELECT MAX(periode) FROM kepengurusan_ormawa WHERE id_ormawa = ?)', [$id_ormawa])
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
            ->where('ormawa.id_ormawa', $id_ormawa)
            ->where('jabatan.id_jabatan', 16) // Tambahkan kondisi untuk ID jabatan
            ->whereRaw('kepengurusan_ormawa.periode = (SELECT MAX(periode) FROM kepengurusan_ormawa WHERE id_ormawa = ?)', [$id_ormawa])
            ->paginate(5);

        return view('prodi/dataprodi', compact('databph', 'datadivisi', 'nama_ormawa'));
    }

    public function proposalkegiatanproker()
    {
        $id_ormawa = Auth::user()->id_ormawa;

        $proposal = DB::table('proposal_kegiatan')
            ->select('proposal_kegiatan.judul_kegiatan', 'ormawa.nama_ormawa', 'proposal_kegiatan.id_proposal', 'proposal_kegiatan.tanggal_pengajuan')
            ->join('ormawa', 'proposal_kegiatan.id_ormawa', '=', 'ormawa.id_ormawa')
            ->join('detail_proposal', 'proposal_kegiatan.id_proposal', '=', 'detail_proposal.id_proposal')
            ->where('proposal_kegiatan.jenis_proposal', '=', 'Proker')
            ->where('detail_proposal.status_kaprodi', '=', 'Revisi')
            ->where('proposal_kegiatan.id_ormawa', '=', $id_ormawa)
            ->paginate(5); // Add pagination here

        return view('prodi/proposalproker', ['proposal' => $proposal]);
    }



    public function  proposalinsidentil()
    {
        // Ambil id_ormawa dari pengguna yang sedang terautentikasi
        $id_ormawa = Auth::user()->id_ormawa;

        $proposal = DB::table('proposal_kegiatan')
            ->select('proposal_kegiatan.judul_kegiatan', 'ormawa.nama_ormawa', 'proposal_kegiatan.id_proposal')
            ->join('ormawa', 'proposal_kegiatan.id_ormawa', '=', 'ormawa.id_ormawa')
            ->join('detail_proposal', 'proposal_kegiatan.id_proposal', '=', 'detail_proposal.id_proposal')
            ->where('proposal_kegiatan.jenis_proposal', '=', 'Insidentil')
            ->where('detail_proposal.status_kaprodi', '=', 'Revisi')
            ->where('proposal_kegiatan.id_ormawa', '=', $id_ormawa)
            ->get();

        return view('prodi/proposalinsidentil', ['proposal' => $proposal]);
    }



    public function detailproposal($id_proposal)
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

            return view('prodi/detailproposal', compact('proposal'));
        } else {
            return view('prodi/detailproposal')->with('error', 'Proposal tidak ditemukan.');
        }
    }

    public function updateproposal(Request $request, $id)
    {
        // Validate data
        $request->validate([
            'status_kaprodi' => 'required',  // This remains required
            'catatan_prodi' => 'nullable|string', // Allow null by default
        ]);

        // Update catatan_prodi in proposal_kegiatan
        DB::table('proposal_kegiatan')
            ->where('id_proposal', $id)
            ->update([
                'catatan_prodi' => $request->catatan_prodi,
            ]);

        // Update status_kaprodi in detail_proposal
        DB::table('detail_proposal')
            ->where('id_proposal', $id)
            ->update([
                'status_kaprodi' => $request->status_kaprodi,
            ]);

        // Check if status_kaprodi is 'Tolak'
        if ($request->status_kaprodi === 'Tolak') {
            // Get id_proker from proposal_kegiatan
            $id_proker = DB::table('proposal_kegiatan')
                ->where('id_proposal', $id)
                ->value('id_proker');

            // Update the status in the proker table
            if ($id_proker) {
                DB::table('proker')
                    ->where('id_proker', $id_proker)
                    ->update(['status' => 'Tolak']);
            }
        }

        return redirect()->back()->with('success', 'Status dan catatan berhasil diperbarui');
    }


    public function accProposal($id)
    {
        // Perbarui status_kaprodi menjadi 'ACC'
        DB::table('detail_proposal')
            ->where('id_proposal', $id)
            ->update(['status_kaprodi' => 'ACC']);

        // Redirect kembali ke halaman sebelumnya
        return redirect()->back()->with('success', 'Proposal berhasil disetujui');
    }

    public function laporankegiatanprodi()
    {
        // Ambil id_ormawa dari pengguna yang sedang terautentikasi
        $id_ormawa = Auth::user()->id_ormawa;

        $laporan = DB::table('laporan')
            ->select('laporan.judul_kegiatan', 'ormawa.nama_ormawa', 'laporan.id_laporan')
            ->join('ormawa', 'laporan.id_ormawa', '=', 'ormawa.id_ormawa')
            ->join('detail_laporan', 'laporan.id_laporan', '=', 'detail_laporan.id_laporan')
            ->where('laporan.jenis_laporan', '=', 'LPJ')
            ->where('detail_laporan.status_kaprodi', '=', 'Revisi')
            ->where('laporan.id_ormawa', '=', $id_ormawa)
            ->get();

        return view('prodi/laporankegiatan', ['laporan' => $laporan]);
    }

    public function laporantahunan()
    {
        // Ambil id_ormawa dari pengguna yang sedang terautentikasi
        $id_ormawa = Auth::user()->id_ormawa;

        $laporan = DB::table('laporan')
            ->select('laporan.judul_kegiatan', 'ormawa.nama_ormawa', 'laporan.id_laporan')
            ->join('ormawa', 'laporan.id_ormawa', '=', 'ormawa.id_ormawa')
            ->join('detail_laporan', 'laporan.id_laporan', '=', 'detail_laporan.id_laporan')
            ->where('laporan.jenis_laporan', '=', 'Tahunan')
            ->where('detail_laporan.status_kemahasiswaan', '=', 'Revisi')
            ->where('laporan.id_ormawa', '=', $id_ormawa)
            ->paginate(5);


        return view('prodi/laporantahunan', ['laporan' => $laporan]);
    }

    public function monitoringkegiatan()
    {
        // Ambil id_ormawa dari pengguna yang sedang terautentikasi
        $id_ormawa = Auth::user()->id_ormawa;

        // Tampilkan list kegiatan untuk monitoring
        $monitoring = DB::table('monitoring')
            ->join('proposal_kegiatan', 'monitoring.id_proposal', '=', 'proposal_kegiatan.id_proposal')
            ->join('ormawa', 'proposal_kegiatan.id_ormawa', '=', 'ormawa.id_ormawa')
            ->select('proposal_kegiatan.judul_kegiatan', 'ormawa.nama_ormawa', 'proposal_kegiatan.id_proposal', 'monitoring.id_monitoring')
            ->where('monitoring.status', 'NOT OKE')
            ->where('ormawa.id_ormawa', $id_ormawa)
            ->get();

        return view('prodi/monitoring', ['monitoring' => $monitoring]);
    }

    public function detailmonitorkegiatan($id)
    {
        // Ambil id_ormawa dari pengguna yang sedang terautentikasi
        $id_ormawa = Auth::user()->id_ormawa;

        // Mengambil data detail berdasarkan id proposal

        $detail = DB::table('monitoring')
            ->select('proposal_kegiatan.judul_kegiatan', 'monitoring.keterangan', 'monitoring.waktu', 'monitoring.foto')
            ->join('proposal_kegiatan', 'monitoring.id_proposal', '=', 'proposal_kegiatan.id_proposal')
            ->join('ormawa', 'proposal_kegiatan.id_ormawa', '=', 'ormawa.id_ormawa')
            ->where('monitoring.status', '=', 'NOT OKE')
            ->where('ormawa.id_ormawa', $id_ormawa)
            ->where('monitoring.id_monitoring', $id)
            ->first();

        return view('prodi/detailmonitoring', ['detail' => $detail]);
    }

    public function showDetailLaporan($id_laporan)
    {
        $laporan = DB::table('laporan')
            ->where('id_laporan', $id_laporan)
            ->first();

        if (!$laporan) {
            return "Laporan tidak ditemukan";
        }
        return view('prodi/detaillaporan', compact('laporan'));
    }

    //update laporan kegiatan dan tahunan
    public function updatelaporanprodi(Request $request, $id)
    {
        // Validasi data input awal
        $request->validate([
            'status_kaprodi' => 'required',
            'catatan_prodi' => 'nullable|string',
            'judul_kegiatan' => 'required',
            'rencana_kegiatan' => 'required',
            'relasi_kegiatan' => 'required',
            'evaluasi' => 'required',
            'penggunaan_dana' => 'required',
            'penutup' => 'required',
        ]);

        // Update detail_laporan.status_kaprodi
        DB::table('detail_laporan')
            ->where('id_laporan', $id)
            ->update(['status_kaprodi' => $request->status_kaprodi]);

        // Update catatan_prodi di tabel laporan
        DB::table('laporan')
            ->where('id_laporan', $id)
            ->update([
                'catatan_prodi' => $request->catatan_prodi,
                'judul_kegiatan' => $request->judul_kegiatan,
                'rencana_kegiatan' => $request->rencana_kegiatan,
                'relasi_kegiatan' => $request->relasi_kegiatan,
                'evaluasi' => $request->evaluasi,
                'penggunaan_dana' => $request->penggunaan_dana,
                'penutup' => $request->penutup,
            ]);

        //return redirect()->back()->with('success', 'Laporan berhasil diperbarui');
        return dd('update data prodi');
    }


    public function acclaporan($id)
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
            ->update(['status_kaprodi' => 'ACC']);

        // Redirect kembali ke halaman sebelumnya
        return dd('berhasil');
    }

    public function datakepengurusanormawa()
    {
        $id_ormawa = Auth::user()->id_ormawa;

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
            ->where('ormawa.id_ormawa', $id_ormawa)
            ->whereRaw('kepengurusan_ormawa.periode = (SELECT MAX(periode) FROM kepengurusan_ormawa WHERE id_ormawa = ?)', [$id_ormawa])
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
            ->where('ormawa.id_ormawa', $id_ormawa)
            ->whereRaw('kepengurusan_ormawa.periode = (SELECT MAX(periode) FROM kepengurusan_ormawa WHERE id_ormawa = ?)', [$id_ormawa])
            ->paginate(5, ['*'], 'divisiPage');  // Custom pagination page name for datadivisi

        $prokers = DB::table('proker')
            ->where('id_ormawa', $id_ormawa)
            ->paginate(10, ['*'], 'prokerPage');  // Custom pagination page name for prokers

        return view('prodi/dataormawa', [
            'databph' => $databph,
            'datadivisi' => $datadivisi,
            'prokers' => $prokers
        ]);
    }


    public function datadetailproker($id_proker)
    {
        // Mengambil data proker berdasarkan id_proker
        $proker = DB::table('proker')
            ->where('id_proker', $id_proker)
            ->first();

        // Jika data proker ditemukan, tampilkan view dengan data proker
        if ($proker) {
            return view('prodi/detailproker', compact('proker'));
        }

        // Jika data proker tidak ditemukan, kembalikan ke halaman sebelumnya dengan pesan error
        return redirect()->back()->with('error', 'Proker tidak ditemukan.');
    }

    public function strukturprodi()
    {

        return view('prodi/struktur');
    }
}
