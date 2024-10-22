<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Ormawa;
use App\Models\User;
use App\Models\Roles;
use App\Models\Proker;
use App\Models\Monitoring;
use App\Libraries\AhoCorasickMatcher;
use Illuminate\Support\Facades\Log;
use Wikimedia\AhoCorasick\AhoCorasick;
use AhoCorasick\MultiStringMatcher;



class AdminController extends Controller
{
    //fungsi mengembalikan view admin
    public function read()
    {
        $data = ormawa::all();
        return view('admin/dataadmin')->with([
            'data' => $data
        ]);
    }

    public function create()
    {
        return view('create');
    }

    public function store(Request $request)
    {
        $data = [
            'id_ormawa' => $request->id_ormawa,
            'nama_ormawa' => $request->nama_ormawa,
            'nama_singkatan' => $request->nama_singkatan
        ];

        Ormawa::insert($data);

        return redirect()->back()->with('success', 'Data berhasil disimpan!');
    }


    public function show($id)
    {
        // Cari data berdasarkan id_ormawa
        $data = Ormawa::findOrFail($id);

        // Kembalikan view edit dengan data yang ditemukan
        return view('edit')->with([
            'data' => $data
        ]);
    }

    public function destroy($id)
    {
        $data = Ormawa::findOrFail($id);
        $data->delete();

        // Redirect kembali dengan pesan sukses
        return redirect()->back()->with('delete_success', 'Data berhasil dihapus!');
    }


    public function informasi($id, Request $request)
{
    // Ambil data Ormawa berdasarkan id_ormawa
    $ormawa = Ormawa::where('id_ormawa', $id)->first();
    $nama_singkatan = $ormawa->nama_singkatan;

    // Tentukan periode maksimum untuk ormawa tertentu
    $maxPeriode = DB::table('kepengurusan_ormawa')
        ->where('id_ormawa', $id)
        ->max('periode');

    // Ambil periode yang dipilih dari request atau gunakan periode maksimum sebagai default
    $selectedPeriode = $request->input('periode', $maxPeriode);

    // Ambil informasi proker
    $informasi = DB::table('proker')
        ->join('kepengurusan_ormawa', 'proker.IdPengurus', '=', 'kepengurusan_ormawa.id_kepengurusan')
        ->select('proker.nama_kegiatan', 'proker.status')
        ->where('proker.id_ormawa', $id)
        ->where('kepengurusan_ormawa.periode', $selectedPeriode)
        ->get();

    // Ambil informasi jabatan
    $jabatan = DB::table('kepengurusan_ormawa')
        ->join('detail_kepengurusan', 'kepengurusan_ormawa.id_kepengurusan', '=', 'detail_kepengurusan.id_detail_kepengurusan')
        ->join('jabatan', 'detail_kepengurusan.jabatan', '=', 'jabatan.id_jabatan')
        ->join('mahasiswa', 'detail_kepengurusan.npm', '=', 'mahasiswa.npm')
        ->join('ormawa', 'kepengurusan_ormawa.id_ormawa', '=', 'ormawa.id_ormawa')
        ->select(
            'ormawa.nama_ormawa',
            'mahasiswa.nama_mahasiswa',
            'mahasiswa.npm',
            'jabatan.nama_jabatan',
            'kepengurusan_ormawa.periode'
        )
        ->where('ormawa.id_ormawa', $id)
        ->where('kepengurusan_ormawa.periode', $selectedPeriode)
        ->orderBy('jabatan.id_jabatan')
        ->get();

    // Ambil data kepengurusan ormawa berdasarkan id_ormawa yang dipilih
    $kepengurusan = DB::table('kepengurusan_ormawa')
        ->select('id_kepengurusan', 'id_ormawa', 'periode')
        ->where('id_ormawa', $id)
        ->get();

    // Kembalikan data dalam bentuk respons
    return view('admin/informasi', [
        'informasi' => $informasi,
        'nama_singkatan' => $nama_singkatan,
        'jabatan' => $jabatan,
        'kepengurusan' => $kepengurusan,
        'selectedPeriode' => $selectedPeriode,
        'id' => $id,
    ]);
}



   public function inputdatabph(Request $request)
{
    // Pastikan Anda mendapatkan id_ormawa dari request yang dikirimkan
    $id_ormawa = $request->input('id_ormawa');

    // Query untuk mendapatkan periode maksimum yang terkait dengan id_ormawa yang dipilih
    $id_kepengurusan = DB::table('kepengurusan_ormawa')
        ->where('id_ormawa', $id_ormawa)
        ->orderByDesc('periode')
        ->value('id_kepengurusan');

    // Pastikan id_kepengurusan adalah bilangan bulat positif
    if (!$id_kepengurusan) {
        // Jika tidak ada id_kepengurusan yang ditemukan, kembalikan pesan kesalahan
        return back()->with('error', 'Data kepengurusan tidak ditemukan');
    }

    // Aturan validasi untuk data yang diterima
    $request->validate([
        'jabatan' => 'required|integer',
        'nama_mahasiswa' => 'required|string|max:100',
        'npm' => 'required|string|unique:mahasiswa,npm',
    ]);

    try {
        // Mulai transaksi database
        DB::transaction(function () use ($request, $id_kepengurusan) {
            // Masukkan data ke dalam tabel 'mahasiswa'
            DB::table('mahasiswa')->insert([
                'nama_mahasiswa' => $request->nama_mahasiswa,
                'npm' => $request->npm,
            ]);

            // Masukkan data ke dalam tabel 'detail_kepengurusan_divisi' menggunakan id_kepengurusan yang diperoleh
            DB::table('detail_kepengurusan')->insert([
                'id_detail_kepengurusan' => $id_kepengurusan,
                'jabatan' => $request->jabatan,
                'npm' => $request->npm,
            ]);
        });

        // Jika berhasil, redirect kembali dengan pesan sukses
        return back()->with('success', 'Data berhasil disimpan');
    } catch (\Exception $e) {
        // Log pesan kesalahan
        Log::error('Gagal menyimpan data detail kepengurusan. Error: '.$e->getMessage());

        // Jika terjadi kesalahan, kembalikan pesan kesalahan
        return back()->with('error', 'Gagal menyimpan data detail kepengurusan. Silakan cek log untuk detailnya.');
    }
}





    public function tambahUser()
    {
        $usersData = User::select('users.username', 'users.user_id', 'roles.role', 'ormawa.nama_ormawa')
            ->join('roles', 'users.id_role', '=', 'roles.id_role')
            ->leftJoin('ormawa', 'users.id_ormawa', '=', 'ormawa.id_ormawa')
            ->get();


        return view('admin/tambahuseradmin', compact('usersData'));
    }

    public function tambah()
    {
       $ormawas = Ormawa::all();
        $roles = DB::table('roles')->get();

        return view('admin/formtambahuser', compact('ormawas', 'roles'));
    }

    public function hapus($user_id)
    {
        // Temukan data pengguna berdasarkan ID
        $user = User::findOrFail($user_id);

        // Lakukan penghapusan
        $user->delete();

        // Simpan pesan sukses ke dalam sesi
        return redirect()->back()->with('delete_success', 'User berhasil dihapus!');
    }



    public function proposalkegiatan(Request $request)
    {

        $proposal = DB::table('proposal_kegiatan')
            ->select('proposal_kegiatan.judul_kegiatan', 'ormawa.nama_ormawa', 'proposal_kegiatan.id_proposal')
            ->join('ormawa', 'proposal_kegiatan.id_ormawa', '=', 'ormawa.id_ormawa')
            ->join('detail_proposal', 'proposal_kegiatan.id_proposal', '=', 'detail_proposal.id_proposal')
            ->where('proposal_kegiatan.jenis_proposal', '=', 'Proker')
            ->where('detail_proposal.status_kemahasiswaan', '=', 'Revisi')
            ->where('detail_proposal.status_kaprodi', '=', 'ACC')
            ->get();


        return view('admin/proposal', ['proposal' => $proposal]);
    }


public function detailproposaladmin($id)
{
$proposal = DB::table('proposal_kegiatan')
            ->where('id_proposal', $id)
            ->first();

        if ($proposal) {
            // Mengambil data waktu kegiatan
            $waktu_kegiatan = DB::table('detail_waktu_kegiatan_proposal')
                ->select('waktu_kegiatan')
                ->where('id_proposal', $id)
                ->get();

            // Menambahkan data waktu kegiatan ke dalam objek proposal
            $proposal->waktu_kegiatan = $waktu_kegiatan;

            return view('admin.detailproposal', compact('proposal'));
        } else {
            // Tangani jika proposal tidak ditemukan
            return view('admin.detailproposal')->with('error', 'Proposal tidak ditemukan.');
        }

}


    public function  proposaleksidentil(Request $request)
    {

        $proposal = DB::table('proposal_kegiatan')
            ->select('proposal_kegiatan.judul_kegiatan', 'ormawa.nama_ormawa', 'proposal_kegiatan.id_proposal')
            ->join('ormawa', 'proposal_kegiatan.id_ormawa', '=', 'ormawa.id_ormawa')
            ->join('detail_proposal', 'proposal_kegiatan.id_proposal', '=', 'detail_proposal.id_proposal')
            ->where('proposal_kegiatan.jenis_proposal', '=', 'Insidentil')
            ->where('detail_proposal.status_kemahasiswaan', '=', 'Revisi')
            ->get();

        return view('admin/eksidentil', ['proposal' => $proposal]);
    }

     public function detaileksidentil($id)
    {
        $proposal = DB::table('proposal_kegiatan')
            ->where('id_proposal', $id)
            ->first();

        if ($proposal) {
            // Mengambil data waktu kegiatan
            $waktu_kegiatan = DB::table('detail_waktu_kegiatan_proposal')
                ->select('waktu_kegiatan')
                ->where('id_proposal', $id)
                ->get();

            // Menambahkan data waktu kegiatan ke dalam objek proposal
            $proposal->waktu_kegiatan = $waktu_kegiatan;

            return view('admin.detaileksidentil', compact('proposal'));
        } else {
            // Tangani jika proposal tidak ditemukan
            return view('admin.detaileksidentil')->with('error', 'Proposal tidak ditemukan.');
        }
    }

   public function updateproposalkemahasiswaan(Request $request, $id)
{
    // Validate data
    $request->validate([
        'status_kemahasiswaan' => 'required',  // This remains required
        'catatan_kemahasiswaan' => 'nullable|string', // Allow null by default
    ]);

    // Update catatan_prodi in proposal_kegiatan
    DB::table('proposal_kegiatan')
        ->where('id_proposal', $id)
        ->update([
            'catatan_kemahasiswaan' => $request->catatan_kemahasiswaan,
        ]);

    // Update status_kaprodi in detail_proposal
    DB::table('detail_proposal')
        ->where('id_proposal', $id)
        ->update([
            'status_kemahasiswaan' => $request->status_kemahasiswaan,
        ]);

    return redirect()->back()->with('success', 'Status dan catatan berhasil diperbarui');
    
}


    public function laporankegiatan(Request $request)
    {

        $laporan = DB::table('laporan')
            ->select('laporan.judul_kegiatan', 'ormawa.nama_ormawa', 'laporan.id_laporan')
            ->join('ormawa', 'laporan.id_ormawa', '=', 'ormawa.id_ormawa')
            ->join('detail_laporan', 'laporan.id_laporan', '=', 'detail_laporan.id_laporan')
            ->where('laporan.jenis_laporan', '=', 'LPJ')
            ->where('detail_laporan.status_kemahasiswaan', '=', 'Revisi')
            ->get();

        return view('admin/laporan', ['laporan' => $laporan]);
    }

    public function detaillaporan($id)
    {

        $laporan = DB::table('laporan')
            ->select('id_laporan', 'judul_kegiatan', 'rencana_kegiatan', 'relasi_kegiatan', 'evaluasi', 'penggunaan_dana','dana_terpakai', 'penutup', 'lampiran')
            ->where('id_laporan', $id)
            ->first();

        return view('admin/detaillaporan', ['laporan' => $laporan]);
    }


    public function laporantahunan(Request $request)
    {

        $laporan = DB::table('laporan')
            ->select('laporan.judul_kegiatan', 'ormawa.nama_ormawa', 'laporan.id_laporan')
            ->join('ormawa', 'laporan.id_ormawa', '=', 'ormawa.id_ormawa')
            ->join('detail_laporan', 'laporan.id_laporan', '=', 'detail_laporan.id_laporan')
            ->where('laporan.jenis_laporan', '=', 'Tahunan')
            ->where('detail_laporan.status_kemahasiswaan', '=', 'Revisi')
            ->get();


        return view('admin/laporantahunan', ['laporan' => $laporan]);
    }

    public function detailtahunan($id)
    {

        $laporan = DB::table('laporan')
            ->select('id_laporan', 'judul_kegiatan', 'rencana_kegiatan', 'relasi_kegiatan', 'evaluasi', 'penggunaan_dana', 'penutup')
            ->where('id_laporan', $id)
            ->first();

        return view('admin/detaillaporantahunan', ['laporan' => $laporan]);
    }

    //update laporan kegiatan dan tahunan
    public function updatelaporan(Request $request, $id)
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

    public function acclaporankegiatan($id)
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
            ->update(['status_kemahasiswaan' => 'ACC']);

        return redirect()->route('laporankegiatan')->with('success', 'Laporan berhasil di-ACC');
    }

    public function acclaporantahunan($id)
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
            ->update(['status_kemahasiswaan' => 'ACC']);

        return redirect()->route('laporantahunan')->with('success', 'Laporan berhasil di-ACC');
    }

    public function monitoring()
    {
        //tampilkan list kegiatan untuk monitoring
        $data = DB::table('monitoring')
            ->join('proposal_kegiatan', 'monitoring.id_proposal', '=', 'proposal_kegiatan.id_proposal')
            ->select('proposal_kegiatan.judul_kegiatan', 'id_monitoring')
            ->where('monitoring.status', '=', 'NOT OKE')
            ->get();



        return view('admin/monitoring', ['data' => $data]);
    }

    public function detailmonitor($id)
    {
        $detailMonitoring = DB::table('monitoring')
                            ->join('proposal_kegiatan', 'monitoring.id_proposal', '=', 'proposal_kegiatan.id_proposal')
                            ->select('proposal_kegiatan.judul_kegiatan', 'monitoring.keterangan', 'monitoring.waktu', 'monitoring.foto', 'monitoring.id_monitoring')
                            ->where('monitoring.status', '=', 'NOT OKE')
                            ->where('monitoring.id_monitoring', '=', $id)
                            ->first();


            return view('admin/detailmonitoring', compact('detailMonitoring'));

    }

    public function updateMonitor($id)
    {
        // Cari record yang akan diupdate berdasarkan primary key
        $monitoring = DB::table('monitoring')
        ->select('id_monitoring')
        ->where('id_monitoring', $id)
        ->first();

       // Periksa apakah record ditemukan
    if ($monitoring) {
        // Update kolom status menjadi 'OKE'
        DB::table('monitoring')
            ->where('id_monitoring', $id)
            ->update(['status' => 'OKE']);

        // Kembalikan response atau redirect sesuai kebutuhan
        return redirect()->route('monitoring')->with('message', 'Kegiatan Sudah Di Periksa');
    } else {
        // Jika record tidak ditemukan, kembalikan response error
        return redirect()->route('monitoring')->with('error', 'Kegiatan Tidak Ditemukan');
    }
    
    }



    public function struktur()
    {
        return view('admin/struktur');
    }


    public function statis()
    {
        return view('admin/analisa');
    }

 public function accProposal_kemahasiswaan($id)
    {
        // Perbarui status_kaprodi menjadi 'ACC'
        DB::table('detail_proposal')
            ->where('id_proposal', $id)
            ->update(['status_kemahasiswaan' => 'ACC']);

        // Redirect kembali ke halaman sebelumnya
        return redirect()->back()->with('success', 'Proposal berhasil disetujui');
    }


     public function findSimilarProposals(Request $request)
{
    // Mengambil semua proposal dari database
    $query = DB::table('proposal_kegiatan')
        ->join('detail_proposal', 'proposal_kegiatan.id_proposal', '=', 'detail_proposal.id_proposal')
        ->select('proposal_kegiatan.*', 'detail_proposal.status_kemahasiswaan')
        ->orderBy('proposal_kegiatan.id_proposal', 'desc'); // Mengurutkan berdasarkan ID Proposal dari yang terbesar

    // Cek jika ada input pencarian ID Proposal
    if ($request->has('id_proposal')) {
        $query->where('proposal_kegiatan.id_proposal', $request->input('id_proposal'));
    }

    // Paginate results, 10 items per page (you can change this number)
    $perPage = 10;
    $proposals = $query->paginate($perPage);

    // Ambil teks pencarian dari request
    $searchText = $request->input('search_text');

    $results = [];
    $proposalComparisons = [];
    $similarityThreshold = $request->input('similarity_threshold', 25);

    // Simpan nilai threshold dalam session
    session(['similarity_threshold' => $similarityThreshold]);

    foreach ($proposals as $proposal) {
        // Ambil teks dari proposal yang akan digunakan untuk perbandingan
        $text = implode(' ', [
            $proposal->tema,
            $proposal->judul_kegiatan,
            $proposal->latar_belakang,
            $proposal->deskripsi_kegiatan,
            $proposal->tujuan_kegiatan,
            $proposal->manfaat_kegiatan,
        ]);

        // Lakukan pencarian teks secara case-insensitive
        $matchPosition = stripos($text, $searchText);

        // Jika ada pencocokan, tambahkan ke dalam hasil
        if ($matchPosition !== false) {
            // Ambil data pembanding dari setiap proposal
            $comparisons = $this->getProposalComparisons($proposal, $similarityThreshold);

            // Kumpulkan semua perbandingan untuk proposal saat ini
            if (!isset($proposalComparisons[$proposal->id_proposal])) {
                $proposalComparisons[$proposal->id_proposal] = [
                    'id_proposal' => $proposal->id_proposal,
                    'judul_kegiatan' => $proposal->judul_kegiatan,
                    'similarities' => [],
                    'suggestions' => []
                ];
            }

            foreach ($comparisons as $comparison) {
                $proposalComparisons[$proposal->id_proposal]['similarities'][] = $comparison['average_similarity'];
                $proposalComparisons[$proposal->id_proposal]['suggestions'][] = $comparison['suggestion'];
            }
        }
    }

    // Hitung rata-rata kesamaan untuk setiap proposal
    foreach ($proposalComparisons as $proposalId => $data) {
        $averageSimilarity = array_sum($data['similarities']) / count($data['similarities']);
        $proposalComparisons[$proposalId]['average_similarity'] = $averageSimilarity;
        $proposalComparisons[$proposalId]['suggestion'] = $this->generateSuggestion($averageSimilarity, $similarityThreshold);
        $results[] = $proposalComparisons[$proposalId];
    }

    // Hitung rata-rata keseluruhan dari seluruh nilai similarity
    $overallAverageSimilarity = count($results) > 0 ? array_sum(array_column($results, 'average_similarity')) / count($results) : 0;

    // Mengembalikan view dengan data hasil paginasi dan hasil pencarian
    return view('admin.ahocorasik', [
        'results' => $results,
        'proposals' => $proposals,
        'similarity_threshold' => $similarityThreshold,
        'overallAverageSimilarity' => $overallAverageSimilarity
    ]);
}



public function detailfindSimilarProposals(Request $request, $id_proposal)
{
    // Mengambil proposal berdasarkan id_proposal yang dipilih
    $proposal = DB::table('proposal_kegiatan')
        ->join('detail_proposal', 'proposal_kegiatan.id_proposal', '=', 'detail_proposal.id_proposal')
        ->select('proposal_kegiatan.*', 'detail_proposal.status_kemahasiswaan')
        ->where('proposal_kegiatan.id_proposal', $id_proposal)
        ->first();

    // Jika proposal tidak ditemukan, kembalikan error atau redirect
    if (!$proposal) {
        return redirect()->back()->withErrors(['error' => 'Proposal not found']);
    }

    // Ambil teks pencarian dari request
    $searchText = $request->input('search_text');

    // Hasil pencarian
    $results = [];

    // Ambil nilai threshold dari session
    $similarityThreshold = session('similarity_threshold', 25); // Default to 25 if not found

    // Ambil teks dari proposal yang akan digunakan untuk perbandingan
    $text = implode(' ', [
        $proposal->tema,
        $proposal->judul_kegiatan,
        $proposal->latar_belakang,
        $proposal->deskripsi_kegiatan,
        $proposal->tujuan_kegiatan,
        $proposal->manfaat_kegiatan,
    ]);

    // Lakukan pencarian teks secara case-insensitive
    $matchPosition = stripos($text, $searchText);

    // Jika ada pencocokan, tambahkan ke dalam hasil
    if ($matchPosition !== false) {
        // Ambil data pembanding dari setiap proposal
        $comparisons = $this->getProposalComparisons($proposal, $similarityThreshold);

        foreach ($comparisons as $comparison) {
            // Ambil periode dari proposal pembanding berdasarkan id_kepengurusan yang berelasi
            $comparisonProposal = DB::table('proposal_kegiatan')
                ->join('kepengurusan_ormawa', 'proposal_kegiatan.id_kepengurusan', '=', 'kepengurusan_ormawa.id_kepengurusan')
                ->select('kepengurusan_ormawa.periode')
                ->where('proposal_kegiatan.id_proposal', $comparison['similar_id_proposal'])
                ->first();

            $results[] = [
                'id_proposal' => $proposal->id_proposal,
                'judul_kegiatan' => $proposal->judul_kegiatan,
                'similar_id_proposal' => $comparison['similar_id_proposal'], // Tambahkan similar_id_proposal di sini
                'similar_judul_kegiatan' => $comparison['similar_judul_kegiatan'],
                'similarity' => $comparison['average_similarity'],
                'indicator_similarities' => $comparison['indicator_similarities'],
                'suggestion' => $comparison['suggestion'],
                'periode' => $comparisonProposal ? $comparisonProposal->periode : 'N/A', // Tambahkan periode di sini
            ];
        }
    }

    // Mengembalikan view dengan data hasil pencarian
    return view('admin.similarity', [
        'results' => $results,
        'proposal' => $proposal,
        'similarity_threshold' => $similarityThreshold
    ]);
}





    protected function getProposalComparisons($proposal, $similarityThreshold)
    {
        // Ambil semua proposal untuk dibandingkan
        $allProposals = DB::table('proposal_kegiatan')->get();

        $comparisons = [];

        foreach ($allProposals as $similarProposal) {
            if ($similarProposal->id_proposal == $proposal->id_proposal) {
                continue; // Skip the same proposal
            }

            // Hitung kesamaan untuk setiap indikator
            $indicators = ['tema', 'judul_kegiatan', 'latar_belakang', 'deskripsi_kegiatan', 'tujuan_kegiatan', 'manfaat_kegiatan', 'tempat_pelaksanaan'];
            $indicatorSimilarities = [];

            foreach ($indicators as $indicator) {
                similar_text($proposal->$indicator, $similarProposal->$indicator, $similarity);
                $indicatorSimilarities[$indicator] = $similarity;
            }

            // Hitung rata-rata kesamaan
            $averageSimilarity = array_sum($indicatorSimilarities) / count($indicatorSimilarities);

            // Tentukan saran berdasarkan nilai kesamaan rata-rata
            $suggestion = $this->generateSuggestion($averageSimilarity, $similarityThreshold);

            $comparisons[] = [
                'similar_id_proposal' => $similarProposal->id_proposal,
                'similar_judul_kegiatan' => $similarProposal->judul_kegiatan,
                'average_similarity' => $averageSimilarity,
                'indicator_similarities' => $indicatorSimilarities,
                'suggestion' => $suggestion,
            ];
        }

        return $comparisons;
    }

    protected function generateSuggestion($averageSimilarity, $similarityThreshold)
    {
        if ($averageSimilarity > $similarityThreshold) {
            return "Tingkat kesamaan yang tinggi ditemukan. Proposal Tidak Disarankan ACC";
        } else {
            return "Proposal Disarankan Dapat di ACC";
        }
    }


    public function detailproposalsimilarity($id_proposal)
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
            // Mengirim data ke view
            return view('admin.detailproposalSPK', compact('proposal'));
        }
    }

      public function storeKepengurusanOrmawa(Request $request)
    {
        // Validasi data
        $validatedData = $request->validate([
            'nama_ormawa' => 'required|string|max:45',
            'id_ormawa' => 'required|integer', // Ubah validasi untuk menerima nama_ormawa
            'periode' => 'required|string|max:15'
        ]);

        // Ambil id_ormawa berdasarkan nama_ormawa yang diinputkan
        $ormawa = DB::table('ormawa')->where('nama_ormawa', $validatedData['nama_ormawa'])->first();

        // Simpan data ke tabel kepengurusan_ormawa
        DB::table('kepengurusan_ormawa')->insert([
            'id_ormawa' => $validatedData['id_ormawa'], // Gunakan id_ormawa yang ditemukan
            'periode' => $validatedData['periode']
        ]);

        // Beri peringatan bahwa data berhasil disimpan
        session()->flash('success', 'Data kepengurusan berhasil ditambahkan.');

        // Redirect kembali ke halaman admin/inputperiode
        return redirect()->route('input_periode');
    }


    public function formperiode()
    {
        // Ambil semua data ormawa dari tabel
        $ormawas = Ormawa::all();

        // Kembalikan view dengan data ormawa
        return view('admin.inputperiode', compact('ormawas'));
    }

}
