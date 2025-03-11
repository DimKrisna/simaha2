<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Proker;
use App\Models\Ormawa;
use Illuminate\Support\Facades\Crypt;

class RektorController extends Controller
{
    //
    public function baca()
    {
        $data = Ormawa::all();
        return view('rektor/datarektor')->with([
            'data' => $data
        ]);
    }
    public function informasiRektor($id)
    {
        $ormawa = Ormawa::where('id_ormawa', $id)->first(); // Menggunakan model Ormawa, ganti dengan nama model yang sesuai
        $nama_singkatan = $ormawa->nama_singkatan;

        $informasi = DB::table('proker')
            ->join('kepengurusan_ormawa', 'proker.IdPengurus', '=', 'kepengurusan_ormawa.id_kepengurusan')
            ->select('proker.nama_kegiatan', 'proker.status')
            ->where('proker.id_ormawa', $id)
            ->where('kepengurusan_ormawa.periode', DB::raw('(SELECT MAX(periode) FROM kepengurusan_ormawa)'))
            ->get();

        $jabatan = DB::table('kepengurusan_ormawa')
            ->join('detail_kepengurusan', 'kepengurusan_ormawa.id_kepengurusan', '=', 'detail_kepengurusan.id_detail_kepengurusan')
            ->join('jabatan', 'detail_kepengurusan.jabatan', '=', 'jabatan.id_jabatan')
            ->join('mahasiswa', 'detail_kepengurusan.npm', '=', 'mahasiswa.npm')
            ->join('ormawa', 'kepengurusan_ormawa.id_ormawa', '=', 'ormawa.id_ormawa')
            ->select(
                'ormawa.nama_ormawa',
                'mahasiswa.nama_mahasiswa',
                'jabatan.nama_jabatan',
                'kepengurusan_ormawa.periode'
            )
            ->where('ormawa.id_ormawa', $id)
            ->where('kepengurusan_ormawa.periode', DB::raw('(SELECT MAX(periode) FROM kepengurusan_ormawa)'))
            ->orderBy('jabatan.id_jabatan')
            ->get();


        return view('rektor/informasi', ['informasi' => $informasi, 'nama_singkatan' => $nama_singkatan, 'jabatan' => $jabatan]);
    }





    //fungsi update data atau revisi mengambil ke controller yang yang ada di ControllerAdmin

    public function laporantahunanwr3(Request $request)
    {

        $laporan = DB::table('laporan')
            ->select('laporan.judul_kegiatan', 'ormawa.nama_ormawa', 'laporan.id_laporan')
            ->join('ormawa', 'laporan.id_ormawa', '=', 'ormawa.id_ormawa')
            ->join('detail_laporan', 'laporan.id_laporan', '=', 'detail_laporan.id_laporan')
            ->where('laporan.jenis_laporan', '=', 'Tahunan')
            ->where('detail_laporan.status_wr3', '=', 'Revisi')
            ->get();

        return view('rektor/laporantahunan', ['laporan' => $laporan]);
    }

    public function detailtahunanwr3($id)
    {

        $laporan = DB::table('laporan')
            ->select('id_laporan', 'judul_kegiatan', 'rencana_kegiatan', 'relasi_kegiatan', 'evaluasi', 'penggunaan_dana', 'penutup')
            ->where('id_laporan', $id)
            ->first();

        return view('rektor/detaillaporantahunan', ['laporan' => $laporan]);
    }

    public function acclaporankegiatanwr3($id)
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
            ->update(['status_wr3' => 'ACC']);

        return redirect()->route('laporankegiatanwr3')->with('success', 'Laporan berhasil di-ACC');
    }

    public function acclaporantahunanwr3($id)
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
            ->update(['status_wr3' => 'ACC']);

        return redirect()->route('laporantahunanwr3')->with('success', 'Laporan berhasil di-ACC');
    }

    public function struktur1()
    {
        return view('rektor/struktur');
    }

    public function statis1()
    {
        return view('rektor/analisa');
    }

    public function proposalkegiatanrektor(Request $request)
    {
        $query = DB::table('proposal_kegiatan')
            ->select(
                'proposal_kegiatan.judul_kegiatan',
                'ormawa.nama_ormawa',
                'proposal_kegiatan.id_proposal',
                'proposal_kegiatan.tanggal_pengajuan'
            )
            ->join('ormawa', 'proposal_kegiatan.id_ormawa', '=', 'ormawa.id_ormawa')
            ->join('detail_proposal', 'proposal_kegiatan.id_proposal', '=', 'detail_proposal.id_proposal')
            ->where('proposal_kegiatan.jenis_proposal', 'Proker')
            ->where('detail_proposal.status_kemahasiswaan', 'ACC');

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('ormawa.nama_ormawa', 'LIKE', "%$search%")
                        ->orWhere('proposal_kegiatan.judul_kegiatan', 'LIKE', "%$search%");
                });
            }
        $proposal = $query->paginate(10);

        return view('rektor/proposalprokerrektor', compact('proposal'));
    }


    public function proposalinsidentilrektor(Request $request)
    {
        $query = DB::table('proposal_kegiatan')
            ->select(
                'proposal_kegiatan.judul_kegiatan',
                'ormawa.nama_ormawa',
                'proposal_kegiatan.id_proposal',
                'proposal_kegiatan.tanggal_pengajuan'
            )
            ->join('ormawa', 'proposal_kegiatan.id_ormawa', '=', 'ormawa.id_ormawa')
            ->join('detail_proposal', 'proposal_kegiatan.id_proposal', '=', 'detail_proposal.id_proposal')
            ->where('proposal_kegiatan.jenis_proposal', 'Insidentil')
            ->where('detail_proposal.status_kemahasiswaan', 'ACC');

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('ormawa.nama_ormawa', 'LIKE', "%$search%")
                        ->orWhere('proposal_kegiatan.judul_kegiatan', 'LIKE', "%$search%");
                });
            }
        $proposal = $query->paginate(10);

        return view('rektor/proposalinsidentilrektor', compact('proposal'));
    }



    public function tampilpropprokerrektor($encryptedId)
    {
        // Dekripsi ID proposal
        $id_proposal = Crypt::decryptString($encryptedId);

        // Mengambil data proposal berdasarkan ID proposal yang didekripsi
        $proposal = DB::table('proposal_kegiatan')
            ->leftJoin('proker', 'proposal_kegiatan.id_proker', '=', 'proker.id_proker')
            ->select('proposal_kegiatan.*', 'proker.nama_kegiatan')
            ->where('proposal_kegiatan.id_proposal', $id_proposal)
            ->first();

        // Jika proposal tidak ditemukan, ambil seluruh data proposal dengan id_proposal tertentu
        if (!$proposal) {
            $proposal = DB::table('proposal_kegiatan')
                ->where('id_proposal', $id_proposal)
                ->first();

            if (!$proposal) {
                // Jika tidak ada proposal dengan id_proposal tersebut, kembalikan pesan error atau ke halaman lain
                abort(404); // Atau bisa dialihkan ke halaman lain yang sesuai
            }
        }
        // Mengambil data waktu kegiatan
        $waktu_kegiatan = DB::table('detail_waktu_kegiatan_proposal')
            ->select('waktu_kegiatan')
            ->where('id_proposal', $id_proposal)
            ->get();

        // Mengambil data status dari tabel detail_proposal
        $statuses = DB::table('detail_proposal')
            ->where('id_proposal', $id_proposal)
            ->first();

        $proposal->waktu_kegiatan = $waktu_kegiatan;
        $proposal->statuses = $statuses;

        // Kembalikan ke view yang sesuai berdasarkan apakah ada proker.nama_kegiatan atau tidak
        if (isset($proposal->nama_kegiatan)) {
            return view('rektor.detailproprektor', compact('proposal'));
        } else {
            return view('rektor.detailproprektor', compact('proposal'));
        }
    }

    public function tampilpropinsidentilrektor($encryptedId)
    {
        // Dekripsi ID proposal
        $id_proposal = Crypt::decryptString($encryptedId);

        // Mengambil data proposal berdasarkan ID proposal yang didekripsi
        $proposal = DB::table('proposal_kegiatan')
            ->select('proposal_kegiatan.*')
            ->where('proposal_kegiatan.id_proposal', $id_proposal)
            ->first();

        if ($proposal) {
            // Mengambil data waktu kegiatan
            $waktu_kegiatan = DB::table('detail_waktu_kegiatan_proposal')
                ->select('waktu_kegiatan')
                ->where('id_proposal', $id_proposal)
                ->get();
            // Mengambil data status dari tabel detail_proposal
            $statuses = DB::table('detail_proposal')
                ->select('status_kaprodi', 'status_kemahasiswaan', 'status_wr3', 'status_dekanat', 'status_akhir')
                ->where('id_proposal', $id_proposal)
                ->first();

            // Menambahkan data waktu kegiatan dan status ke dalam objek proposal
            $proposal->waktu_kegiatan = $waktu_kegiatan;
            $proposal->statuses = $statuses;


            return view('rektor.detailpropinsidentilrektor', compact('proposal'));
        } else {
            return view('rektor.detailpropinsidentilrektor')->with('error', 'Proposal tidak ditemukan.');
        }
    }



    public function updateproposal(Request $request, $id)
    {
        // Validate data
        $request->validate([
            'status_wr3' => 'required',  // This remains required
            'catatan_rektor' => 'nullable|string', // Allow null by default
        ]);

        // Update catatan_prodi in proposal_kegiatan
        DB::table('proposal_kegiatan')
            ->where('id_proposal', $id)
            ->update([
                'catatan_rektor' => $request->catatan_rektor,
            ]);

        // Update status_kaprodi in detail_proposal
        DB::table('detail_proposal')
            ->where('id_proposal', $id)
            ->update([
                'status_wr3' => $request->status_kaprodi,
            ]);

        // Check if status_kaprodi is 'Tolak'
        if ($request->status_wr3 === 'Tolak') {
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

    // public function updateproposalrektor(Request $request, $id)
    // {
    //     // Validasi data
    //     $request->validate([
    //         'tema' => 'required',
    //         'judul_kegiatan' => 'required',
    //         'latar_belakang' => 'required',
    //         'deskripsi_kegiatan' => 'required',
    //         'tujuan_kegiatan' => 'required',
    //         'manfaat_kegiatan' => 'required',
    //         'tempat_pelaksanaan' => 'required',
    //         'anggaran_kegiatan' => 'required|numeric',
    //         'anggaran_diajukan' => 'required|numeric',
    //     ]);

    //     // Update data proposal
    //     DB::table('proposal_kegiatan')
    //         ->where('id_proposal', $id)
    //         ->update([
    //             'tema' => $request->tema,
    //             'judul_kegiatan' => $request->judul_kegiatan,
    //             'latar_belakang' => $request->latar_belakang,
    //             'deskripsi_kegiatan' => $request->deskripsi_kegiatan,
    //             'tujuan_kegiatan' => $request->tujuan_kegiatan,
    //             'manfaat_kegiatan' => $request->manfaat_kegiatan,
    //             'tempat_pelaksanaan' => $request->tempat_pelaksanaan,
    //             'anggaran_kegiatan' => $request->anggaran_kegiatan,
    //             'anggaran_diajukan' => $request->anggaran_diajukan,
    //         ]);

    //     return redirect()->back()->with('success', 'Data proposal berhasil direvisi');
    // }
    public function accProposal_rektor($id)
    {
        // Perbarui status_kaprodi menjadi 'ACC'
        DB::table('detail_proposal')
            ->where('id_proposal', $id)
            ->update(['status_wr3' => 'ACC']);

        // Redirect kembali ke halaman sebelumnya
        return redirect()->back()->with('success', 'Proposal berhasil disetujui');
    }

    //fungsi laporan dimulai dari sini -> list laporan kegiatan
    public function ListLaporanRektor()
    {

        $laporan = DB::table('laporan')
            ->join('detail_laporan', 'laporan.id_laporan', '=', 'detail_laporan.id_laporan')
            ->join('ormawa', 'laporan.id_ormawa', '=', 'ormawa.id_ormawa')
            ->where('detail_laporan.status_wr3', 'Revisi')
            ->where('laporan.jenis_laporan', 'LPJ')
            ->select('laporan.*', 'detail_laporan.*', 'ormawa.*')
            ->get();

        return view('rektor/laporan', ['laporan' => $laporan]);
    }

    public function detaillaporanwr3($id)
    {

        $laporan = DB::table('laporan')
            ->where('id_laporan', $id)
            ->first();

        return view('rektor/detaillaporan', ['laporan' => $laporan]);
    }

    //list laporan kegiatan tahunan
    public function ListLaporanTahunanRektor()
    {

        $laporan = DB::table('laporan')
            ->join('detail_laporan', 'laporan.id_laporan', '=', 'detail_laporan.id_laporan')
            ->join('ormawa', 'laporan.id_ormawa', '=', 'ormawa.id_ormawa')
            ->where('detail_laporan.status_wr3', 'Revisi')
            ->where('laporan.jenis_laporan', 'Tahunan')
            ->select('laporan.*', 'detail_laporan.*', 'ormawa.*')
            ->get();

        return view('rektor/laporantahunan', ['laporan' => $laporan]);
    }

    //update atau revisi laporan
    public function UpdateLaporanRektor(Request $request, $id)
    {

        // Update status_dekanat di tabel detail_laporan
        DB::table('detail_laporan')
            ->where('id_laporan', $id)
            ->update(['status_wr3' => $request->status_wr3]);

        // Update catatan_dekanat dan lainnya di tabel laporan
        DB::table('laporan')
            ->where('id_laporan', $id)
            ->update([
                'catatan_rektor' => $request->catatan_rektor,
                'judul_kegiatan' => $request->judul_kegiatan,
                'rencana_kegiatan' => $request->rencana_kegiatan,
                'relasi_kegiatan' => $request->relasi_kegiatan,
                'evaluasi' => $request->evaluasi,
                'penggunaan_dana' => $request->penggunaan_dana,
                'penutup' => $request->penutup,
            ]);

        return redirect()->back()->with('success', 'Laporan berhasil diperbarui');
    }
}
