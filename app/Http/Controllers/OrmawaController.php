<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Ormawa;
use App\Models\ProposalKegiatan;
use App\Models\KepengurusanOrmawa;
use App\Models\ProposalFiles;
use App\Models\LaporanFiles;
use Illuminate\Pagination\Paginator;
use Exception;
use Yaza\LaravelGoogleDriveStorage\Gdrive;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;



class OrmawaController extends Controller
{
    public function ormawa()
    {
        // Mengambil id_ormawa dari user yang sedang login
        $id_ormawa = Auth::user()->id_ormawa;
        // Mengambil data tema, jenis_proposal, dan tempat_pelaksanaan dari tabel proposal_kegiatan
        $dataProposal = ProposalKegiatan::where('id_ormawa', $id_ormawa)
            ->paginate(5); // Ganti angka 10 dengan jumlah item per halaman yang diinginkan
        return view('ormawa.dataormawa', ['dataProposal' => $dataProposal]);
    }

 public function tampilprop($encryptedId)
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
            ->select('status_kaprodi', 'status_kemahasiswaan', 'status_wr3', 'status_dekanat', 'status_akhir')
            ->where('id_proposal', $id_proposal)
            ->first();

        // Menambahkan data waktu kegiatan dan status ke dalam objek proposal
        $proposal->waktu_kegiatan = $waktu_kegiatan;
        $proposal->statuses = $statuses;

        // Kembalikan ke view yang sesuai berdasarkan apakah ada proker.nama_kegiatan atau tidak
        if (isset($proposal->nama_kegiatan)) {
            return view('ormawa.detail_prop_revisi', compact('proposal'));
        } else {
            return view('ormawa.detail_prop_revisi', compact('proposal'));
        }
    }

   public function tampilpropinsidentil($encryptedId)
    {
        try {
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

                return view('ormawa.detailpropinsidentil', compact('proposal'));
            } else {
                return view('ormawa.detailpropinsidentil')->with('error', 'Proposal tidak ditemukan.');
            }
        } catch (\Exception $e) {
            // Log error untuk debugging
            Log::error('Error decrypting ID proposal: ' . $e->getMessage());
            return view('ormawa.detailpropinsidentil')->with('error', 'Payload tidak valid atau terdekripsi.');
        }
    }

    //pengajuan proposal proker
  public function pengajuanpropproker(Request $request)
{
    try {
        // Validasi data input dari form
        $request->validate([
            'id_proker' => 'required', // Pastikan id_proker terisi
            'tema' => 'required|string|max:150',
            'judul_kegiatan' => 'required|string|max:150',
            'latar_belakang' => 'required|string',
            'deskripsi_kegiatan' => 'required|string',
            'tujuan_kegiatan' => 'required|string',
            'manfaat_kegiatan' => 'required|string',
            'tempat_pelaksanaan' => 'required|string|max:150',
            'anggaran_kegiatan' => 'required|numeric',
            'anggaran_diajukan' => 'required|numeric',
            'waktu_kegiatan' => 'required|array', // Pastikan waktu_kegiatan dikirim sebagai array
            'waktu_kegiatan.*' => 'required|date', // Pastikan setiap elemen waktu_kegiatan adalah tanggal
            'lampiran' => 'required|mimes:pdf|max:10000',
        ]);

        // Ambil file yang di-upload
        $file = $request->file('lampiran');
        $filename = $file->getClientOriginalName();
        $folderPath = "Lampiran";

        // Upload file ke Google Drive
        $filePath = "$folderPath/$filename";
        Gdrive::put($filePath, $file);
        // Simpan path lampiran
        $lampiranPath = $filePath;

        // Ambil id_ormawa dari pengguna yang saat ini masuk
        $idOrmawa = Auth::user()->id_ormawa;

        // Cari periode terbesar dari id_ormawa yang menjadi pengguna
        $periodeTerbesar = DB::table('kepengurusan_ormawa')
            ->where('id_ormawa', $idOrmawa)
            ->max('periode');

        // Cari id_kepengurusan yang sesuai dengan periode terbesar
        $idKepengurusan = DB::table('kepengurusan_ormawa')
            ->where('id_ormawa', $idOrmawa)
            ->where('periode', $periodeTerbesar)
            ->value('id_kepengurusan');

        // Simpan data proposal kegiatan
        $proposalId = DB::table('proposal_kegiatan')->insertGetId([
            'id_ormawa' => $idOrmawa,
            'id_kepengurusan' => $idKepengurusan,
            'jenis_proposal' => 'Proker', // Mengisi jenis_proposal secara otomatis
            'id_proker' => $request->id_proker,
            'tema' => $request->tema,
            'judul_kegiatan' => $request->judul_kegiatan,
            'latar_belakang' => $request->latar_belakang,
            'deskripsi_kegiatan' => $request->deskripsi_kegiatan,
            'tujuan_kegiatan' => $request->tujuan_kegiatan,
            'manfaat_kegiatan' => $request->manfaat_kegiatan,
            'tempat_pelaksanaan' => $request->tempat_pelaksanaan,
            'anggaran_kegiatan' => $request->anggaran_kegiatan,
            'anggaran_diajukan' => $request->anggaran_diajukan,
            'lampiran' => $lampiranPath,
        ]);

        // Simpan data waktu kegiatan ke dalam tabel detail_waktu_kegiatan_proposal
        if ($request->has('waktu_kegiatan')) {
            $waktuKegiatan = $request->input('waktu_kegiatan');
            foreach ($waktuKegiatan as $waktu) {
                DB::table('detail_waktu_kegiatan_proposal')->insert([
                    'id_proposal' => $proposalId,
                    'waktu_kegiatan' => $waktu,
                ]);
            }
        }

        // Tentukan status berdasarkan id_ormawa
        if (Str::startsWith($idOrmawa, '2')) {
            $statusKaprodi = 'ACC';
            $statusDekan = 'ACC';
        } else {
            $statusKaprodi = 'Revisi';
            $statusDekan = 'Revisi';
        }

        // Tambahkan penyimpanan ke tabel detail_proposal
        DB::table('detail_proposal')->insert([
            'id_proposal' => $proposalId,
            'status_kaprodi' => $statusKaprodi,
            'status_kemahasiswaan' => 'Revisi',
            'status_wr3' => 'Revisi',
            'status_dekanat' => $statusDekan,
            'status_akhir' => 'Revisi'
        ]);

        // Jika penyimpanan berhasil
        Session::flash('success', 'Data berhasil disimpan.');
    } catch (\Exception $e) {
        // Jika terjadi kesalahan
        Session::flash('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
    }

    // Redirect ke halaman dengan route bernama 'pengajuanpropinsidentil'
    return redirect()->route('formpropproker');
}


    public function forminsidentil()
    {
        return view('ormawa/formpropinsidentil');
    }

   public function pengajuanpropinsidentil(Request $request)
{
    try {
        // Validasi data input dari form
        $request->validate([
            'tema' => 'required|string|max:150',
            'judul_kegiatan' => 'required|string|max:150',
            'latar_belakang' => 'required|string',
            'deskripsi_kegiatan' => 'required|string',
            'tujuan_kegiatan' => 'required|string',
            'manfaat_kegiatan' => 'required|string',
            'tempat_pelaksanaan' => 'required|string|max:150',
            'anggaran_kegiatan' => 'required|numeric',
            'anggaran_diajukan' => 'required|numeric',
            'waktu_kegiatan' => 'required|array', // Pastikan waktu_kegiatan dikirim sebagai array
            'waktu_kegiatan.*' => 'required|date', // Pastikan setiap elemen waktu_kegiatan adalah tanggal
            'lampiran' => 'required|mimes:pdf|max:10000',
        ]);

        // Ambil file yang di-upload
        $file = $request->file('lampiran');
        $filename = $file->getClientOriginalName();
        $folderPath = "Lampiran";

        // Upload file ke Google Drive
        $filePath = "$folderPath/$filename";
        Gdrive::put($filePath, $file);
        // Simpan path lampiran
        $lampiranPath = $filePath;

        // Ambil id_ormawa dari pengguna yang saat ini masuk
        $idOrmawa = Auth::user()->id_ormawa;

        // Cari periode terbesar dari id_ormawa yang menjadi pengguna
        $periodeTerbesar = DB::table('kepengurusan_ormawa')
            ->where('id_ormawa', $idOrmawa)
            ->max('periode');

        // Cari id_kepengurusan yang sesuai dengan periode terbesar
        $idKepengurusan = DB::table('kepengurusan_ormawa')
            ->where('id_ormawa', $idOrmawa)
            ->where('periode', $periodeTerbesar)
            ->value('id_kepengurusan');

        // Simpan data proposal kegiatan
        $proposalId = DB::table('proposal_kegiatan')->insertGetId([
            'id_ormawa' => $idOrmawa,
            'id_kepengurusan' => $idKepengurusan,
            'jenis_proposal' => 'Insidentil', // Mengisi jenis_proposal secara otomatis
            'tema' => $request->tema,
            'judul_kegiatan' => $request->judul_kegiatan,
            'latar_belakang' => $request->latar_belakang,
            'deskripsi_kegiatan' => $request->deskripsi_kegiatan,
            'tujuan_kegiatan' => $request->tujuan_kegiatan,
            'manfaat_kegiatan' => $request->manfaat_kegiatan,
            'tempat_pelaksanaan' => $request->tempat_pelaksanaan,
            'anggaran_kegiatan' => $request->anggaran_kegiatan,
            'anggaran_diajukan' => $request->anggaran_diajukan,
            'lampiran' => $lampiranPath,
        ]);

        // Simpan data waktu kegiatan ke dalam tabel detail_waktu_kegiatan_proposal
        if ($request->has('waktu_kegiatan')) {
            $waktuKegiatan = $request->input('waktu_kegiatan');
            foreach ($waktuKegiatan as $waktu) {
                DB::table('detail_waktu_kegiatan_proposal')->insert([
                    'id_proposal' => $proposalId,
                    'waktu_kegiatan' => $waktu,
                ]);
            }
        }

        // Tentukan status berdasarkan id_ormawa
        if (Str::startsWith($idOrmawa, '2')) {
            $statusKaprodi = 'ACC';
            $statusDekan = 'ACC';
        } else {
            $statusKaprodi = 'Revisi';
            $statusDekan = 'Revisi';
        }

        // Tambahkan penyimpanan ke tabel detail_proposal
        DB::table('detail_proposal')->insert([
            'id_proposal' => $proposalId,
            'status_kaprodi' => $statusKaprodi,
            'status_kemahasiswaan' => 'Revisi',
            'status_wr3' => 'Revisi',
            'status_dekanat' => $statusDekan,
            'status_akhir' => 'Revisi'
        ]);

        // Jika penyimpanan berhasil
        Session::flash('success', 'Data berhasil disimpan.');
    } catch (\Exception $e) {
        // Jika terjadi kesalahan
        Log::error('Error saving data: ' . $e->getMessage());
        Session::flash('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
    }

    // Redirect ke halaman dengan route bernama 'pengajuanpropinsidentil'
    return redirect()->route('forminsidentil');
}


    public function tampilkanPropInsiden()
    {
        // Mengambil id_ormawa dari user yang sedang login
        $id_ormawa = Auth::user()->id_ormawa;

       // Mengambil data proposal kegiatan yang memiliki jenis_proposal = 'Insidentil' dan terkait dengan periode terbaru
        $datainsidentil = ProposalKegiatan::join('detail_proposal', 'proposal_kegiatan.id_proposal', '=', 'detail_proposal.id_proposal')
            ->join('kepengurusan_ormawa', 'proposal_kegiatan.id_kepengurusan', '=', 'kepengurusan_ormawa.id_kepengurusan')
            ->select('proposal_kegiatan.*', 'detail_proposal.status_akhir')
            ->where('proposal_kegiatan.jenis_proposal', 'Insidentil')
            ->where('proposal_kegiatan.id_ormawa', $id_ormawa)
            ->where('kepengurusan_ormawa.periode', function ($query) use ($id_ormawa) {
                $query->selectRaw('MAX(periode)')
                    ->from('kepengurusan_ormawa')
                    ->where('id_ormawa', $id_ormawa);
            })
            ->get();


        // Format tanggal_pengajuan untuk setiap data
        foreach ($datainsidentil as $proposal) {
            $proposal->tanggal_pengajuan = Carbon::parse($proposal->tanggal_pengajuan)->format('d-m-Y');
        }

        // Mengembalikan view dengan data yang telah diformat
        return view('ormawa.pengajuanpropinsidentil', ['datainsidentil' => $datainsidentil])->with('success', 'Data insidentil berhasil ditampilkan.');
    }


    public function tampilkanPropProker()
{
    // Mengambil id_ormawa dari user yang sedang login
    $id_ormawa = Auth::user()->id_ormawa;

    // Mengambil data proposal kegiatan yang berstatus 'Revisi' dan join dengan detail_proposal dan proker
    $datapropproker = ProposalKegiatan::join('detail_proposal', 'proposal_kegiatan.id_proposal', '=', 'detail_proposal.id_proposal')
        ->join('kepengurusan_ormawa', 'proposal_kegiatan.id_kepengurusan', '=', 'kepengurusan_ormawa.id_kepengurusan')
        ->select('proposal_kegiatan.*', 'detail_proposal.status_akhir')
        ->where('proposal_kegiatan.jenis_proposal', 'Proker')
        ->where('proposal_kegiatan.id_ormawa', $id_ormawa)
        ->where('kepengurusan_ormawa.periode', function ($query) use ($id_ormawa) {
            $query->selectRaw('MAX(periode)')
                ->from('kepengurusan_ormawa')
                ->where('id_ormawa', $id_ormawa);
        })
        ->paginate(6); // Paginate here, before get()

    // Format tanggal_pengajuan untuk setiap data
    foreach ($datapropproker as $proposal) {
        $proposal->tanggal_pengajuan = Carbon::parse($proposal->tanggal_pengajuan)->format('d-m-Y');
    }

    // Mengambil seluruh data dari tabel 'proker'
    $allProker = DB::table('proker')
        ->where('id_ormawa', $id_ormawa)
        ->get();

    // Menggabungkan kedua data tersebut ke dalam view
    return view('ormawa.pengajuanpropproker', [
        'datapropproker' => $datapropproker,
        'allProker' => $allProker
    ]);
}


       public function formpropproker()
{
    // Mengambil data proker yang statusnya bukan 'Terlaksana' dari tabel proker
    $prokers = DB::table('proker')
                 ->where('status', '!=', 'Terlaksana')
                 ->get();

    return view('ormawa/formpropproker', compact('prokers'));
}


    public function updateproposal(Request $request, $id_proposal)
    {
        // Lakukan pembaruan proposal menggunakan DB facade
        DB::table('proposal_kegiatan')
            ->where('id_proposal', $id_proposal)
            ->update([
                'tema' => $request->input('tema'),
                'judul_kegiatan' => $request->input('judul_kegiatan'),
                'latar_belakang' => $request->input('latar_belakang'),
                'deskripsi_kegiatan' => $request->input('deskripsi_kegiatan'),
                'tujuan_kegiatan' => $request->input('tujuan_kegiatan'),
                'manfaat_kegiatan' => $request->input('manfaat_kegiatan'),
                'tempat_pelaksanaan' => $request->input('tempat_pelaksanaan'),
                'anggaran_kegiatan' => $request->input('anggaran_kegiatan'),
                'anggaran_diajukan' => $request->input('anggaran_diajukan'),
                // Tambahkan pembaruan untuk setiap kolom sesuai kebutuhan
            ]);

        return redirect()->route('ormawa', $id_proposal)->with('success', 'Proposal berhasil direvisi.');
    }

    //tampil data laporan kegiatan
    public function tampilkanDataLaporan()
    {
        // Mengambil ID ormawa berdasarkan username
        $id_ormawa = Auth::user()->id_ormawa;

        // Mengambil data laporan dari tabel laporan dengan jenis_laporan LPJ dan menggabungkan dengan tabel detail_laporan untuk mendapatkan status akhir
        $laporan = DB::table('laporan')
            ->join('detail_laporan', 'laporan.id_laporan', '=', 'detail_laporan.id_laporan')
            ->where('laporan.jenis_laporan', 'LPJ')
            ->where('laporan.id_ormawa', $id_ormawa)
            ->select('laporan.*', 'detail_laporan.status_akhir')
            ->get();

        // Jika data laporan berhasil ditemukan, tampilkan view dengan data laporan
        return view('ormawa.pelaporankegiatan', ['laporan' => $laporan]);
    }


    public function showFormLaporan()
    {
        $id_ormawa = Auth::user()->id_ormawa;
        //ambil data id proposal, proposal dan kepengurusan
        $data = DB::table('proposal_kegiatan')
        ->select('id_proposal', 'id_proker', 'id_kepengurusan','judul_kegiatan')
        ->where('id_ormawa', $id_ormawa)
        ->get();
      
      
      
        return view('ormawa/formlaporan', ['data' => $data]);
    }


    //
    public function inputLaporan(Request $request)
    {
        try {
            // Ambil ID Ormawa dari user yang sedang login
            $id_ormawa = Auth::user()->id_ormawa;

 
            // Validasi data input
            $validatedData = $request->validate([
                'jenis_laporan' => 'required|in:LPJ,Tahunan',
                'id_proposal' => 'required|integer',
                'judul_kegiatan' => 'required|string|max:100',
                'rencana_kegiatan' => 'required|string',
                'relasi_kegiatan' => 'required|string',
                'evaluasi' => 'required|string',
                'penggunaan_dana' => 'required|string',
                'dana_terpakai' => 'required|integer',
                'penutup' => 'required|string',
                'lampiran' => 'required|mimes:pdf|max:10000',
            ]);

            // Ambil file yang di-upload
            $file = $request->file('lampiran');
            $filename = $file->getClientOriginalName();
            $folderPath = "Lampiran";

            // Upload file ke Google Drive
            $filePath = "$folderPath/$filename";
            Gdrive::put($filePath, $file);
            // Simpan path lampiran
            $lampiranPath = $filePath;

            $idProposal = $validatedData['id_proposal'];

            $data = DB::table('proposal_kegiatan')
                ->select('id_proposal', 'id_proker', 'id_kepengurusan')
                ->where('id_proposal', $idProposal)
                ->first();
          

            // Masukkan data ke dalam tabel laporan
            $laporanId = DB::table('laporan')->insertGetId([
                'id_ormawa' => Auth::user()->id_ormawa,
                'id_proposal' => $data->id_proposal,
                'id_proker' => $data->id_proker,
                'id_kepengurusan' => $data->id_kepengurusan,
                'jenis_laporan' => $validatedData['jenis_laporan'],
                'judul_kegiatan' => $validatedData['judul_kegiatan'],
                'rencana_kegiatan' => $validatedData['rencana_kegiatan'],
                'relasi_kegiatan' => $validatedData['relasi_kegiatan'],
                'evaluasi' => $validatedData['evaluasi'],
                'penggunaan_dana' => $validatedData['penggunaan_dana'],
                'dana_terpakai' => $validatedData['dana_terpakai'],
                'penutup' => $validatedData['penutup'],
                'lampiran' => $lampiranPath,
            ]);

            //memasukkan data ke tabel detail laporan / pengganti triger
            DB::table('detail_laporan')->insert([
                'id_laporan' => $laporanId,
                'status_kaprodi' => 'Revisi', // Atur nilai default
                'status_kemahasiswaan' => 'Revisi', // Atur nilai default
                'status_wr3' => 'Revisi', // Atur nilai default
                'status_dekanat' => 'Revisi', // Atur nilai default
                'status_akhir' => 'Revisi' // Atur nilai default
            ]);

            // Redirect dengan pesan sukses
            return redirect()->back()->with('success', 'Data laporan berhasil disimpan.');

        } catch (Exception $e) {
            // Redirect dengan pesan error
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    public function DataLaporanTahunan()
    {
        // Mengambil ID ormawa berdasarkan username
        $id_ormawa = Auth::user()->id_ormawa;

        // Mengambil data laporan dari tabel laporan dengan jenis_laporan Tahunan dan menggabungkan dengan tabel detail_laporan untuk mendapatkan status akhir
        $laporan = DB::table('laporan')
            ->join('detail_laporan', 'laporan.id_laporan', '=', 'detail_laporan.id_laporan')
            ->where('laporan.jenis_laporan', 'Tahunan')
            ->where('laporan.id_ormawa', $id_ormawa)
            ->select('laporan.*', 'detail_laporan.status_akhir')
            ->get();

        // Jika data laporan berhasil ditemukan, tampilkan view dengan data laporan
        return view('ormawa.pelaporantahunan', ['laporan' => $laporan]);
    }

    public function showFormLaporanTahunan()
    {
        return view('ormawa/formlaptahunan');
    }


    public function TampilDetailLaporan($id_laporan)
    {
        // Mengambil data laporan berdasarkan id_laporan
        $laporan = DB::table('laporan')->where('id_laporan', $id_laporan)->first();

        // Pastikan bahwa laporan yang diakses sesuai dengan id_ormawa dari user yang sedang login
        if ($laporan && $laporan->id_ormawa !== auth()->user()->id_ormawa) {
            // Jika tidak sesuai, lakukan redirect atau tindakan lain sesuai kebutuhan Anda
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke laporan ini.');
        }
        
        // Mengambil data status dari tabel detail_proposal
        $statuses = DB::table('detail_laporan')
            ->select('status_kaprodi', 'status_kemahasiswaan', 'status_wr3', 'status_dekanat', 'status_akhir')
            ->where('id_laporan', $id_laporan)
            ->first();
        

        // Kirim data laporan ke view
        return view('ormawa.detaillaporan', ['laporan' => $laporan, 'statuses' => $statuses]);
    }

    public function UpdateLaporan(Request $request, $id_laporan)
    {
        // Validasi input
        $request->validate([
            'jenis_laporan' => 'required|in:LPJ,Tahunan',
            'judul_kegiatan' => 'required|string|max:100',
            'rencana_kegiatan' => 'required|string',
            'relasi_kegiatan' => 'required|string',
            'evaluasi' => 'required|string',
            'penggunaan_dana' => 'required|string',
            'penutup' => 'required|string',
        ]);

        // Cek apakah user memiliki akses ke laporan dengan id_laporan yang diberikan
        $laporan = DB::table('laporan')->where('id_laporan', $id_laporan)->first();
        if ($laporan->id_ormawa !== Auth::user()->id_ormawa) {
            // Jika tidak sesuai, lakukan redirect atau tindakan lain sesuai kebutuhan Anda
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk mengupdate laporan ini.');
        }

        // Update data laporan
        DB::table('laporan')
            ->where('id_laporan', $id_laporan)
            ->update([
                'jenis_laporan' => $request->jenis_laporan,
                'judul_kegiatan' => $request->judul_kegiatan,
                'rencana_kegiatan' => $request->rencana_kegiatan,
                'relasi_kegiatan' => $request->relasi_kegiatan,
                'evaluasi' => $request->evaluasi,
                'penggunaan_dana' => $request->penggunaan_dana,
                'penutup' => $request->penutup,
            ]);

        // Redirect ke halaman detail laporan dengan pesan sukses
        return redirect()->route('show_laporan', $id_laporan)->with('success', 'Laporan berhasil diperbarui.');
    }

    public function tampilmonitoring()
    {

        $id_ormawa = Auth::user()->id_ormawa;

        $kegiatans = DB::table('proposal_kegiatan')
            ->select('judul_kegiatan', 'id_proposal')
            ->where('id_ormawa', $id_ormawa)
            ->get();

        return view('ormawa/inputmonitoring', ['kegiatans' => $kegiatans]);
    }


    public function inputmonitoring(Request $request)
    {
        // Validasi input
        $request->validate([
            'jenis_laporan' => 'required',
            'tanggal' => 'required|date',
            'rencana_kegiatan' => 'required|string',
            'foto_kegiatan' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Menyimpan file foto
        try {
            $file = $request->file('foto_kegiatan');

            // Path tujuan di folder public_html/foto
            $destinationPath = base_path('../public_html/foto'); // Sesuaikan path ini

            // Cek apakah direktori tujuan ada, jika tidak, buat direktori tersebut
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move($destinationPath, $fileName);
            $filePath = 'foto/' . $fileName;

            // Memasukkan data ke dalam tabel 'monitoring'
            DB::table('monitoring')->insert([
                'id_proposal' => $request->jenis_laporan,
                'waktu' => $request->tanggal,
                'keterangan' => $request->rencana_kegiatan,
                'foto' => $filePath,
                'status' => 'NOT OKE',
            ]);

            return redirect()->back()->with('success', 'Data kegiatan berhasil disimpan.');
        } catch (\Exception $e) {
            // Log pesan kesalahan untuk debugging
            //Log::error('Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }





    public function datapengurusormawa()
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
            ->paginate(5);

        $prokers = DB::table('proker')
            ->where('id_ormawa', $id_ormawa) // Filter data berdasarkan id_ormawa
            ->paginate(10);

        return view('ormawa/datadetailormawa', ['databph' => $databph, 'datadivisi' => $datadivisi, 'prokers' => $prokers]);
    }

    public function showFormProker()
    {
        // Ambil data proker dari database
        $prokers = DB::table('proker')->get(); // Sesuaikan dengan tabel Anda

        // Kirimkan data proker ke view
        return view('ormawa/forminputproker')->with('prokers', $prokers);
    }

    public function inputDataProker(Request $request)
    {
        try {
            // Validasi data input dari form
            $request->validate([
                'nama_kegiatan' => 'required|string|max:100',
                'uraian_kegiatan' => 'required|string',
                'peran_ormawa' => 'required|in:Pelaksana,Peserta',
                'jenis_kegiatan' => 'required|in:Kebidangan,Unggulan,Wajib',
                'keunggulan' => 'required|string',
                'capaian' => 'required|string',
                'strategi_sosialisasi' => 'required|string',
                'personalia_pelaksana' => 'required|string|max:50',
                'estimasi_anggaran' => 'required|numeric',
            ]);

            // Mendapatkan id_ormawa dari pengguna yang sedang masuk
            $id_ormawa = auth()->user()->id_ormawa;

            // Mendapatkan id_pengurus dari tabel kepengurusan_ormawa dengan periode terbesar
            $id_pengurus = DB::table('kepengurusan_ormawa')
                ->where('id_ormawa', $id_ormawa)
                ->orderByDesc('periode')
                ->value('id_kepengurusan');

            // Pastikan id_pengurus tidak null sebelum menyimpan data
            if ($id_pengurus) {
                // Memasukkan data ke dalam tabel proker
                DB::table('proker')->insert([
                    'id_ormawa' => $id_ormawa,
                    'IdPengurus' => $id_pengurus,
                    'nama_kegiatan' => $request->nama_kegiatan,
                    'uraian_kegiatan' => $request->uraian_kegiatan,
                    'peran_ormawa' => $request->peran_ormawa,
                    'jenis_kegiatan' => $request->jenis_kegiatan,
                    'keunggulan' => $request->keunggulan,
                    'capaian' => $request->capaian,
                    'strategi_sosialisasi' => $request->strategi_sosialisasi,
                    'personalia_pelaksana' => $request->personalia_pelaksana,
                    'estimasi_anggaran' => $request->estimasi_anggaran,
                ]);

                // Set pesan berhasil dalam session
                Session::flash('success', 'Berhasil menambahkan data kegiatan.');

                // Kembali ke halaman sebelumnya dengan pesan sukses
                return redirect()->back();
            } else {
                // Jika id_pengurus null, artinya data kepengurusan belum tersedia
                throw new \Exception('Data kepengurusan belum tersedia.');
            }
        } catch (\Exception $e) {
            // Set pesan error dalam session
            Session::flash('error', 'Gagal menambahkan data kegiatan: ' . $e->getMessage());

            // Kembali ke halaman sebelumnya dengan pesan error
            return redirect()->back();
        }
    }

    public function detailproker($id_proker)
    {
        // Mengambil data proker berdasarkan id_proker
        $proker = DB::table('proker')
            ->where('id_proker', $id_proker)
            ->first();

        // Jika data proker ditemukan, tampilkan view dengan data proker
        if ($proker) {
            return view('ormawa.detailproker', compact('proker'));
        } else {
            // Jika data proker tidak ditemukan, kembalikan ke halaman sebelumnya dengan pesan error
            return redirect()->back()->with('error', 'Proker tidak ditemukan.');
        }
    }

    public function updateDataProker(Request $request, $id_proker)
    {
        // Lakukan validasi input
        $request->validate([
            'nama_kegiatan' => 'required|string|max:100',
            'uraian_kegiatan' => 'required|string',
            'peran_ormawa' => 'required|in:Pelaksana,Peserta',
            'jenis_kegiatan' => 'required|in:Kebidangan,Unggulan,Wajib',
            'keunggulan' => 'required|string',
            'capaian' => 'required|string',
            'strategi_sosialisasi' => 'required|string',
            'personalia_pelaksana' => 'required|string|max:50',
            'estimasi_anggaran' => 'required|numeric',
        ]);

        try {
            // Mulai transaksi database
            DB::beginTransaction();

            // Lakukan update data proker menggunakan Facades DB
            $proker = DB::table('proker')
                ->where('id_proker', $id_proker)
                ->update([
                    'nama_kegiatan' => $request->nama_kegiatan,
                    'uraian_kegiatan' => $request->uraian_kegiatan,
                    'peran_ormawa' => $request->peran_ormawa,
                    'jenis_kegiatan' => $request->jenis_kegiatan,
                    'keunggulan' => $request->keunggulan,
                    'capaian' => $request->capaian,
                    'strategi_sosialisasi' => $request->strategi_sosialisasi,
                    'personalia_pelaksana' => $request->personalia_pelaksana,
                    'estimasi_anggaran' => $request->estimasi_anggaran,
                ]);

            if ($proker == 0) {
                // Jika tidak ada data yang terupdate, lempar exception
                throw new \Exception('Proker tidak ditemukan.');
            }

            // Commit transaksi jika berhasil
            DB::commit();

            // Set pesan berhasil dalam session
            Session::flash('success', 'Berhasil melakukan update proker.');

            // Redirect ke halaman datapengurusormawa
            return redirect()->route('datapengurusormawa');
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi error
            DB::rollback();

            // Set pesan error dalam session
            Session::flash('error', 'Gagal melakukan update proker.');

            // Redirect ke halaman datapengurusormawa
            return redirect()->route('datapengurusormawa');
        }
    }

  public function showformbph()
{
    // Ambil data ormawa dari database
    $ormawas = DB::table('ormawa')->get();

    // Ambil data jabatan dari tabel 'jabatan'
    $jabatan = DB::table('jabatan')->pluck('nama_jabatan', 'id_jabatan');

    // Tampilkan view dengan data ormawa dan jabatan
    return view('ormawa.inputdatabph', compact('ormawas', 'jabatan'));
}




   public function deleteDetailKepengurusan($npm)
    {
        try {
            // Lakukan penghapusan data berdasarkan kolom npm menggunakan query DELETE SQL
            DB::delete("DELETE FROM detail_kepengurusan WHERE npm = ?", [$npm]);
            DB::delete("DELETE FROM mahasiswa WHERE npm = ?", [$npm]);

            // Set pesan berhasil dalam session
            Session::flash('success', 'Data detail kepengurusan berhasil dihapus.');

            // Mendapatkan URL halaman sebelumnya dari session, jika ada
            $previousUrl = session()->has('previous_url') ? session('previous_url') : '/';

            // Kembali ke halaman sebelumnya
            return redirect()->route('read');
        } catch (\Exception $e) {
            // Jika terjadi kesalahan, set pesan error dalam session
            Session::flash('error', 'Gagal menghapus data detail kepengurusan.');

            // Kembali ke halaman yang sesuai dengan pesan gagal
            return redirect()->back();
        }
    }

    public function showformdivisi()
    {
        // Get jabatan data from the database
        $jabatan = DB::table('jabatan')->pluck('nama_jabatan', 'id_jabatan');

        // Get divisi data from the database
        $divisi = DB::table('divisi')
            ->join('ormawa', 'divisi.id_ormawa', '=', 'ormawa.id_ormawa')
            ->where('ormawa.id_ormawa', auth()->user()->id_ormawa)
            ->pluck('nama_divisi', 'id_divisi');

        // Return the view with jabatan and divisi data
        return view('ormawa.inputpengurusdivisi', compact('jabatan', 'divisi'));
    }


    public function inputDetailKepengurusanDivisi(Request $request)
    {
        // Get the id_ormawa of the authenticated user
    $id_ormawa = Auth::user()->id_ormawa;

    // Query to get the maximum periode associated with the id_ormawa
    $id_kepengurusan = DB::table('kepengurusan_ormawa')
        ->where('id_ormawa', $id_ormawa)
        ->orderByDesc('periode')
        ->value('id_kepengurusan');

    // Make sure id_kepengurusan is a positive integer
    if (!$id_kepengurusan) {
        // If no id_kepengurusan is found, return an error message
        return back()->with('error', 'Data kepengurusan tidak ditemukan');
    }

    // Validation rules for the incoming request data
    $request->validate([
        'jabatan' => 'required|integer',
        'nama_mahasiswa' => 'required|string|max:100',
        'npm' => 'required|string|unique:mahasiswa,npm',
        'id_divisi' => 'required|integer',
    ]);

    // Start a database transaction
    DB::transaction(function () use ($request, $id_kepengurusan) {
        // Insert data into the 'mahasiswa' table
        DB::table('mahasiswa')->insert([
            'nama_mahasiswa' => $request->nama_mahasiswa,
            'npm' => $request->npm,
        ]);

        // Insert data into the 'detail_kepengurusan_divisi' table using the obtained id_kepengurusan
        DB::table('detail_kepengurusan_divisi')->insert([
            'id_detail_kepengurusan' => $id_kepengurusan,
            'jabatan' => $request->jabatan,
            'npm' => $request->npm,
            'id_divisi' => $request->id_divisi,
        ]);
    });

            // If an error occurs, return an error message with a popup
            return back()->with('error', 'Gagal menyimpan data detail kepengurusan');
        }


    public function deleteDetailKepengurusanDivisi($npm)
    {
        try {
            // Lakukan penghapusan data dari tabel detail_kepengurusan_divisi berdasarkan kolom npm
            DB::table('detail_kepengurusan_divisi')->where('npm', $npm)->delete();

            // Lakukan penghapusan data dari tabel mahasiswa berdasarkan kolom npm
            DB::table('mahasiswa')->where('npm', $npm)->delete();

            // Set pesan berhasil dalam session
            Session::flash('success', 'Data detail kepengurusan divisi dan data mahasiswa berhasil dihapus.');

            // Kembali ke halaman yang sesuai, misalnya halaman yang menampilkan daftar detail kepengurusan divisi
            return redirect()->route('datapengurusormawa');
        } catch (\Exception $e) {
            // Jika terjadi kesalahan, set pesan error dalam session
            Session::flash('error', 'Gagal menghapus data detail kepengurusan divisi dan data mahasiswa.');

            // Kembali ke halaman yang sesuai dengan pesan gagal
            return redirect()->route('datapengurusormawa');
        }
    }


    //upload file proposal ke drive
    public function uploadproposalproker(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'judul_kegiatan' => 'required|string|max:255',
            'file' => 'required|mimes:pdf|max:10000', //batas pdf yang di masukkan 10MB
        ]);

        $ormawa = DB::table('users')
            ->join('ormawa', 'users.id_ormawa', '=', 'ormawa.id_ormawa')
            ->where('users.user_id', $user->user_id)
            ->select('ormawa.nama_ormawa')
            ->first();

        $latestPeriode = KepengurusanOrmawa::orderBy('periode', 'desc')->first()->periode;

        // Get the uploaded file
        $file = $request->file('file');
        $filename = $file->getClientOriginalName();

        $folderPath = "$latestPeriode/{$ormawa->nama_ormawa}/Proposal";

        // Simpan informasi PDF ke database
        $pdf = new ProposalFiles();
        $pdf->nama_file = $file->getClientOriginalName();
        $pdf->path = $folderPath; // Menyimpan path folder
        $pdf->save();

        // Store the file on Google Drive
        Gdrive::put("$folderPath/$filename", $request->file('file'));

        // Return a response, you can customize this as needed
        return back()->with('success', 'File has been uploaded successfully.');
    }

    //upload file laporan ke drive
    public function uploadlaporankegiatan(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'judul_kegiatan' => 'required|string|max:255',
            'file' => 'required|mimes:pdf|max:10000', //batas pdf yang di masukkan 10MB
        ]);

        $ormawa = DB::table('users')
            ->join('ormawa', 'users.id_ormawa', '=', 'ormawa.id_ormawa')
            ->where('users.user_id', $user->user_id)
            ->select('ormawa.nama_ormawa')
            ->first();

        $latestPeriode = KepengurusanOrmawa::orderBy('periode', 'desc')->first()->periode;

        // Get the uploaded file
        $file = $request->file('file');
        $filename = $file->getClientOriginalName();

        $folderPath = "$latestPeriode/{$ormawa->nama_ormawa}/Laporan";

        // Simpan informasi PDF ke database
        $pdf = new LaporanFiles();
        $pdf->nama_file = $file->getClientOriginalName();
        $pdf->path = $folderPath; // Menyimpan path folder
        $pdf->save();

        // Store the file on Google Drive
        Gdrive::put("$folderPath/$filename", $request->file('file'));

        // Return a response, you can customize this as needed
        return back()->with('success', 'File has been uploaded successfully.');
    }



    //fungsi untuk menampilkan lampiran
    public function showlampiran($id_laporan)
    {
        //mengambil data dari tabel laporan
        $file = DB::table('laporan')->where('id_laporan', $id_laporan)->first();
        //menemukan file di drive
        $filePath =  $file->lampiran;
        //mengambil data
        $data = Gdrive::get($filePath);

        return response($data->file)
            ->header('Content-Type', 'application/pdf');
    }

    //fungsi untuk menampilkan lampiran
    public function showlampiranproposal($id_proposal)
    {
        //mengambil data dari tabel laporan
        $file = DB::table('proposal_kegiatan')->where('id_proposal', $id_proposal)->first();
        //menemukan file di drive
        $filePath =  $file->lampiran;
        //mengambil data
        $data = Gdrive::get($filePath);

        return response($data->file)
            ->header('Content-Type', 'application/pdf');
    }

    public function strukturormawa(){

        return view('ormawa/struktur');
    }
    
    

    public function pengesahan($encryptedId)
    {
        try {
            // Dekripsi ID proposal
            $id_proposal = Crypt::decryptString($encryptedId);

            // Mengambil data proposal berdasarkan ID proposal yang didekripsi
            $proposal = DB::table('proposal_kegiatan')
                ->select('id_proposal', 'tema', 'judul_kegiatan')
                ->where('id_proposal', $id_proposal)
                ->first();

            if ($proposal) {
                // Mengambil data status dari tabel detail_proposal
                $statuses = DB::table('detail_proposal')
                    ->select('status_kaprodi', 'status_kemahasiswaan', 'status_wr3', 'status_dekanat', 'status_akhir')
                    ->where('id_proposal', $id_proposal)
                    ->first();

                // Menambahkan data status ke dalam objek proposal
                $proposal->statuses = $statuses;

                return view('ormawa.pengesahan', compact('proposal'));
            } else {
                return view('ormawa.pengesahan')->with('error', 'Proposal tidak ditemukan.');
            }
        } catch (\Exception $e) {
            // Log error untuk debugging
            Log::error('Error decrypting ID proposal: ' . $e->getMessage());
            return view('ormawa.pengesahan')->with('error', 'Payload tidak valid atau terdekripsi.');
        }
    }
    
    
    
    public function pengesahanlaporan($encryptedId)
{
    try {
        // Dekripsi ID laporan
        $id_laporan = Crypt::decryptString($encryptedId);

        // Mengambil data laporan berdasarkan ID laporan yang didekripsi
        $laporan = DB::table('laporan')
            ->select('id_laporan as id_laporan', 'judul_kegiatan')
            ->where('id_laporan', $id_laporan)
            ->first();

        if ($laporan) {
            // Mengambil data status dari tabel detail_laporan
            $statuses = DB::table('detail_laporan')
                ->select('status_kaprodi', 'status_kemahasiswaan', 'status_wr3', 'status_dekanat', 'status_akhir')
                ->where('id_laporan', $id_laporan)
                ->first();

            // Menambahkan data status ke dalam objek laporan
            $laporan->statuses = $statuses;

            return view('ormawa.pengesahan', compact('laporan'));
        } else {
            return view('ormawa.pengesahan')->with('error', 'Laporan tidak ditemukan.');
        }
    } catch (\Exception $e) {
        // Log error untuk debugging
        Log::error('Error decrypting ID laporan: ' . $e->getMessage());
        return view('ormawa.pengesahanlaporan')->with('error', 'Payload tidak valid atau terdekripsi.');
    }
}

    

    public function downloadFile()
    {
        try {
            $filePath = 'Pengesahan/PengesahanProposal1.docx';
            $data = Gdrive::get($filePath);
            return response($data->file, 200)
                ->header('Content-Type', $data->ext)
                ->header('Content-disposition', 'attachment; filename="' . $data->filename . '"');
        } catch (\Exception $e) {
            // Log error untuk debugging
            Log::error('Error downloading file: ' . $e->getMessage());
            return redirect()->back()->with('error', 'File tidak ditemukan atau terjadi kesalahan saat mengunduh.');
        }
    }
}
