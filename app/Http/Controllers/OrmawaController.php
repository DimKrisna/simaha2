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
        $id_proposal = Crypt::decryptString($encryptedId);

        $proposal = DB::table('proposal_kegiatan')
            ->leftJoin('proker', 'proposal_kegiatan.id_proker', '=', 'proker.id_proker')
            ->select('proposal_kegiatan.*', 'proker.nama_kegiatan')
            ->where('proposal_kegiatan.id_proposal', $id_proposal)
            ->first();
        if (!$proposal) {
            $proposal = DB::table('proposal_kegiatan')
                ->where('id_proposal', $id_proposal)
                ->first();

            if (!$proposal) {
                abort(404);
            }
        }
        $waktu_kegiatan = DB::table('detail_waktu_kegiatan_proposal')
            ->select('waktu_kegiatan')
            ->where('id_proposal', $id_proposal)
            ->get();

        $statuses = DB::table('detail_proposal')
            ->select('status_kaprodi', 'status_kemahasiswaan', 'status_wr3', 'status_dekanat', 'status_akhir')
            ->where('id_proposal', $id_proposal)
            ->first();

        $proposal->waktu_kegiatan = $waktu_kegiatan;
        $proposal->statuses = $statuses;

        if (isset($proposal->nama_kegiatan)) {
            return view('ormawa.proposal.detail_prop_revisi', compact('proposal'));
        } else {
            return view('ormawa.proposal.detail_prop_revisi', compact('proposal'));
        }
    }

    public function tampilpropinsidentil($encryptedId)
    {
        $id_proposal = Crypt::decryptString($encryptedId);

        $proposal = DB::table('proposal_kegiatan')
            ->select('proposal_kegiatan.*')
            ->where('proposal_kegiatan.id_proposal', $id_proposal)
            ->first();

        if (!$proposal) {
            return view('ormawa.proposal.detail_prop_revisi')->with('error', 'Proposal tidak ditemukan.');
        }

        $waktu_kegiatan = DB::table('detail_waktu_kegiatan_proposal')
            ->select('waktu_kegiatan')
            ->where('id_proposal', $id_proposal)
            ->get();

        $statuses = DB::table('detail_proposal')
            ->select('status_kaprodi', 'status_kemahasiswaan', 'status_wr3', 'status_dekanat', 'status_akhir')
            ->where('id_proposal', $id_proposal)
            ->first();

        $proposal->waktu_kegiatan = $waktu_kegiatan;
        $proposal->statuses = $statuses;

        return view('ormawa.proposal.detail_prop_revisi', compact('proposal'));
    }


    public function pengajuanpropproker(Request $request)
    {
        try {
            $request->validate([
                'id_proker' => 'required',
                'tema' => 'required|string|max:150',
                'judul_kegiatan' => 'required|string|max:150',
                'latar_belakang' => 'required|string',
                'deskripsi_kegiatan' => 'required|string',
                'tujuan_kegiatan' => 'required|string',
                'manfaat_kegiatan' => 'required|string',
                'tempat_pelaksanaan' => 'required|string|max:150',
                'anggaran_kegiatan' => 'required|numeric',
                'anggaran_diajukan' => 'required|numeric',
                'waktu_kegiatan' => 'required|array',
                'waktu_kegiatan.*' => 'required|date',
                'lampiran' => 'required|mimes:pdf|max:10000',
            ]);

            $file = $request->file('lampiran');
            $filename = $file->getClientOriginalName();
            $folderPath = "Lampiran";

            $filePath = "$folderPath/$filename";
            Gdrive::put($filePath, $file);
            $lampiranPath = $filePath;

            $idOrmawa = Auth::user()->id_ormawa;

            $periodeTerbesar = DB::table('kepengurusan_ormawa')
                ->where('id_ormawa', $idOrmawa)
                ->max('periode');

            $idKepengurusan = DB::table('kepengurusan_ormawa')
                ->where('id_ormawa', $idOrmawa)
                ->where('periode', $periodeTerbesar)
                ->value('id_kepengurusan');

            $proposalId = DB::table('proposal_kegiatan')->insertGetId([
                'id_ormawa' => $idOrmawa,
                'id_kepengurusan' => $idKepengurusan,
                'jenis_proposal' => 'Proker',
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

            if ($request->has('waktu_kegiatan')) {
                $waktuKegiatan = $request->input('waktu_kegiatan');
                foreach ($waktuKegiatan as $waktu) {
                    DB::table('detail_waktu_kegiatan_proposal')->insert([
                        'id_proposal' => $proposalId,
                        'waktu_kegiatan' => $waktu,
                    ]);
                }
            }

            if (Str::startsWith($idOrmawa, '2')) {
                $statusKaprodi = 'ACC';
                $statusDekan = 'ACC';
            } else {
                $statusKaprodi = 'Revisi';
                $statusDekan = 'Revisi';
            }

            DB::table('detail_proposal')->insert([
                'id_proposal' => $proposalId,
                'status_kaprodi' => $statusKaprodi,
                'status_kemahasiswaan' => 'Revisi',
                'status_wr3' => 'Revisi',
                'status_dekanat' => $statusDekan,
                'status_akhir' => 'Revisi'
            ]);

            Session::flash('success', 'Data berhasil disimpan.');
        } catch (\Exception $e) {
            Session::flash('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
        return redirect()->route('formpropproker');
    }

    public function forminsidentil()
    {
        return view('ormawa.proposal.formpropinsidentil');
    }

    public function pengajuanpropinsidentil(Request $request)
    {
        try {
            $anggaran_kegiatan = intval(str_replace(['Rp', '.', ','], '', $request->post('anggaran_kegiatan') ?? 0));
            $anggaran_diajukan = intval(str_replace(['Rp', '.', ','], '', $request->post('anggaran_diajukan') ?? 0));
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
                'waktu_kegiatan' => 'required|array',
                'waktu_kegiatan.*' => 'required|date',
                'lampiran' => 'required|mimes:pdf|max:10000',
            ]);

            $file = $request->file('lampiran');
            $filename = $file->getClientOriginalName();
            $folderPath = "Lampiran";

            $filePath = "$folderPath/$filename";
            Gdrive::put($filePath, $file);
            $lampiranPath = $filePath;

            $idOrmawa = Auth::user()->id_ormawa;

            $periodeTerbesar = DB::table('kepengurusan_ormawa')
                ->where('id_ormawa', $idOrmawa)
                ->max('periode');

            $idKepengurusan = DB::table('kepengurusan_ormawa')
                ->where('id_ormawa', $idOrmawa)
                ->where('periode', $periodeTerbesar)
                ->value('id_kepengurusan');

            $proposalId = DB::table('proposal_kegiatan')->insertGetId([
                'id_ormawa' => $idOrmawa,
                'id_kepengurusan' => $idKepengurusan,
                'jenis_proposal' => 'Insidentil',
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

            if ($request->has('waktu_kegiatan')) {
                $waktuKegiatan = $request->input('waktu_kegiatan');
                foreach ($waktuKegiatan as $waktu) {
                    DB::table('detail_waktu_kegiatan_proposal')->insert([
                        'id_proposal' => $proposalId,
                        'waktu_kegiatan' => $waktu,
                    ]);
                }
            }

            if (Str::startsWith($idOrmawa, '2')) {
                $statusKaprodi = 'ACC';
                $statusDekan = 'ACC';
            } else {
                $statusKaprodi = 'Revisi';
                $statusDekan = 'Revisi';
            }

            DB::table('detail_proposal')->insert([
                'id_proposal' => $proposalId,
                'status_kaprodi' => $statusKaprodi,
                'status_kemahasiswaan' => 'Revisi',
                'status_wr3' => 'Revisi',
                'status_dekanat' => $statusDekan,
                'status_akhir' => 'Revisi'
            ]);

            Session::flash('success', 'Data berhasil disimpan.');
        } catch (\Exception $e) {
            Log::error('Error saving data: ' . $e->getMessage());
            Session::flash('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
        return redirect()->route('pengajuanpropinsidentil');
    }

    public function tampilkanPropInsiden()
    {
        $id_ormawa = Auth::user()->id_ormawa;
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
            ->paginate(6);

        foreach ($datainsidentil as $proposal) {
            $proposal->tanggal_pengajuan = Carbon::parse($proposal->tanggal_pengajuan)->format('d-m-Y');
        }
        return view('ormawa.proposal.pengajuanpropinsidentil', ['datainsidentil' => $datainsidentil])->with('success', 'Data insidentil berhasil ditampilkan.');
    }


    public function tampilkanPropProker()
    {
        $id_ormawa = Auth::user()->id_ormawa;

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
            ->paginate(6);
        foreach ($datapropproker as $proposal) {
            $proposal->tanggal_pengajuan = Carbon::parse($proposal->tanggal_pengajuan)->format('d-m-Y');
        }

        $allProker = DB::table('proker')
            ->where('id_ormawa', $id_ormawa)
            ->get();
        return view('ormawa.proposal.pengajuanpropproker', [
            'datapropproker' => $datapropproker,
            'allProker' => $allProker
        ]);
    }

    public function formpropproker()
    {
        $prokers = DB::table('proker')
            ->whereNotIn('status', ['Terlaksana', 'Tolak'])
            ->get();

        return view('ormawa.proposal.formpropproker', compact('prokers'));
    }

    public function updateproposal(Request $request, $id_proposal)
    {
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
            ]);

        return redirect()->route('ormawa', $id_proposal)->with('success', 'Proposal berhasil direvisi.');
    }


    // Laporan ===================
    public function tampilkanDataLaporan()
    {
        $id_ormawa = Auth::user()->id_ormawa;

        $laporan = DB::table('laporan')
            ->join('detail_laporan', 'laporan.id_laporan', '=', 'detail_laporan.id_laporan')
            ->where('laporan.jenis_laporan', 'LPJ')
            ->where('laporan.id_ormawa', $id_ormawa)
            ->select('laporan.*', 'detail_laporan.status_akhir')
            ->get();

        return view('ormawa.pelaporankegiatan', ['laporan' => $laporan]);
    }

    public function showFormLaporan()
    {
        $id_ormawa = Auth::user()->id_ormawa;
        $data = DB::table('proposal_kegiatan')
            ->select('id_proposal', 'id_proker', 'id_kepengurusan', 'judul_kegiatan')
            ->where('id_ormawa', $id_ormawa)
            ->get();

        return view('ormawa/formlaporan', ['data' => $data]);
    }

    public function inputLaporan(Request $request)
    {
        try {
            $id_ormawa = Auth::user()->id_ormawa;

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

            $file = $request->file('lampiran');
            $filename = $file->getClientOriginalName();
            $folderPath = "Lampiran";

            $filePath = "$folderPath/$filename";
            Gdrive::put($filePath, $file);
            $lampiranPath = $filePath;

            $idProposal = $validatedData['id_proposal'];

            $data = DB::table('proposal_kegiatan')
                ->select('id_proposal', 'id_proker', 'id_kepengurusan')
                ->where('id_proposal', $idProposal)
                ->first();

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

            $statuses = [
                'id_laporan' => $laporanId,
                'status_kaprodi' => 'Revisi',
                'status_kemahasiswaan' => 'Revisi',
                'status_wr3' => 'Revisi',
                'status_dekanat' => 'Revisi',
                'status_akhir' => 'Revisi',
            ];

            if (substr($id_ormawa, 0, 1) == '2') {
                $statuses['status_kaprodi'] = 'ACC';
                $statuses['status_dekanat'] = 'ACC';
            }
            DB::table('detail_laporan')->insert($statuses);

            return redirect()->back()->with('success', 'Data laporan berhasil disimpan.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function DataLaporanTahunan()
    {
        $id_ormawa = Auth::user()->id_ormawa;

        $laporan = DB::table('laporan')
            ->join('detail_laporan', 'laporan.id_laporan', '=', 'detail_laporan.id_laporan')
            ->where('laporan.jenis_laporan', 'Tahunan')
            ->where('laporan.id_ormawa', $id_ormawa)
            ->select('laporan.*', 'detail_laporan.status_akhir')
            ->get();

        return view('ormawa.pelaporantahunan', ['laporan' => $laporan]);
    }

    public function showFormLaporanTahunan()
    {
        return view('ormawa/formlaptahunan');
    }

    public function TampilDetailLaporan($id_laporan)
    {
        $laporan = DB::table('laporan')->where('id_laporan', $id_laporan)->first();

        if ($laporan && $laporan->id_ormawa !== auth()->user()->id_ormawa) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke laporan ini.');
        }

        $statuses = DB::table('detail_laporan')
            ->select('status_kaprodi', 'status_kemahasiswaan', 'status_wr3', 'status_dekanat', 'status_akhir')
            ->where('id_laporan', $id_laporan)
            ->first();

        return view('ormawa.detaillaporan', ['laporan' => $laporan, 'statuses' => $statuses]);
    }


    public function updateLaporan(Request $request, $id)
    {
        DB::table('laporan')
            ->where('id_laporan', $id)
            ->update([
                'jenis_laporan' => $request->jenis_laporan,
                'judul_kegiatan' => $request->judul_kegiatan,
                'rencana_kegiatan' => $request->rencana_kegiatan,
                'relasi_kegiatan' => $request->relasi_kegiatan,
                'evaluasi' => $request->evaluasi,
                'penggunaan_dana' => $request->penggunaan_dana,
                'dana_terpakai' => $request->dana_terpakai,
                'penutup' => $request->penutup,
                'catatan_prodi' => $request->catatan_prodi,
                'catatan_kemahasiswaan' => $request->catatan_kemahasiswaan,
                'catatan_rektor' => $request->catatan_rektor,
                'catatan_fakultas' => $request->catatan_fakultas,
            ]);
        return dd('data terupdate');
    }


    //Monitoring ===================================
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
        $request->validate([
            'jenis_laporan' => 'required',
            'tanggal' => 'required|date',
            'rencana_kegiatan' => 'required|string',
            'foto_kegiatan' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        try {
            $file = $request->file('foto_kegiatan');

            $destinationPath = base_path('../public_html/foto');

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move($destinationPath, $fileName);
            $filePath = 'foto/' . $fileName;

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


    //kepengurusan====================================================
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
            ->where('id_ormawa', $id_ormawa)
            ->paginate(10);

        return view('ormawa.kepengurusan.datadetailormawa', ['databph' => $databph, 'datadivisi' => $datadivisi, 'prokers' => $prokers]);
    }

    public function showFormProker()
    {
        $prokers = DB::table('proker')->get();

        return view('ormawa.kepengurusan.forminputproker')->with('prokers', $prokers);
    }

    public function inputDataProker(Request $request)
    {
        try {
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
            $id_ormawa = auth()->user()->id_ormawa;

            $id_pengurus = DB::table('kepengurusan_ormawa')
                ->where('id_ormawa', $id_ormawa)
                ->orderByDesc('periode')
                ->value('id_kepengurusan');

            if ($id_pengurus) {
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

                Session::flash('success', 'Berhasil menambahkan data kegiatan.');

                return redirect()->back();
            } else {
                throw new \Exception('Data kepengurusan belum tersedia.');
            }
        } catch (\Exception $e) {
            Session::flash('error', 'Gagal menambahkan data kegiatan: ' . $e->getMessage());

            return redirect()->back();
        }
    }

    public function detailproker($id_proker)
    {
        $proker = DB::table('proker')
            ->where('id_proker', $id_proker)
            ->first();

        if ($proker) {
            return view('ormawa.kepengurusan.detailproker', compact('proker'));
        } else {
            return redirect()->back()->with('error', 'Proker tidak ditemukan.');
        }
    }

    public function updateDataProker(Request $request, $id_proker)
    {
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
            DB::beginTransaction();

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
                throw new \Exception('Proker tidak ditemukan.');
            }

            DB::commit();

            Session::flash('success', 'Berhasil melakukan update proker.');

            return redirect()->route('datapengurusormawa');
        } catch (\Exception $e) {
            DB::rollback();

            Session::flash('error', 'Gagal melakukan update proker.');

            return redirect()->route('datapengurusormawa');
        }
    }

    public function showformbph(Request $request)
    {
        $ormawas = DB::table('ormawa')->get();

        $jabatan = DB::table('jabatan')->pluck('nama_jabatan', 'id_jabatan');

        $npm = $request->input('npm');
        $nama_mahasiswa = null;

        if ($npm) {
            $mahasiswa = DB::table('mahasiswa')->where('npm', $npm)->first();
            if ($mahasiswa) {
                $nama_mahasiswa = $mahasiswa->nama_mahasiswa;
            }
        }
        return view('ormawa.kepengurusan.inputdatabph', compact('ormawas', 'jabatan', 'nama_mahasiswa', 'npm'));
    }


    public function deleteDetailKepengurusan($npm)
    {
        try {
            DB::delete("DELETE FROM detail_kepengurusan WHERE npm = ?", [$npm]);
            DB::delete("DELETE FROM mahasiswa WHERE npm = ?", [$npm]);

            Session::flash('success', 'Data detail kepengurusan berhasil dihapus.');

            $previousUrl = session()->has('previous_url') ? session('previous_url') : '/';

            return redirect()->route('read');
        } catch (\Exception $e) {

            Session::flash('error', 'Gagal menghapus data detail kepengurusan.');
            return redirect()->back();
        }
    }

    public function showformdivisi()
    {
        $jabatan = DB::table('jabatan')->pluck('nama_jabatan', 'id_jabatan');

        $divisi = DB::table('divisi')
            ->join('ormawa', 'divisi.id_ormawa', '=', 'ormawa.id_ormawa')
            ->where('ormawa.id_ormawa', auth()->user()->id_ormawa)
            ->pluck('nama_divisi', 'id_divisi');

        return view('ormawa.kepengurusan.inputpengurusdivisi', compact('jabatan', 'divisi'));
    }


    public function inputDetailKepengurusanDivisi(Request $request)
    {
        $id_ormawa = Auth::user()->id_ormawa;
        $id_kepengurusan = DB::table('kepengurusan_ormawa')
            ->where('id_ormawa', $id_ormawa)
            ->orderByDesc('periode')
            ->value('id_kepengurusan');
        if (!$id_kepengurusan) {
            return back()->with('error', 'Data kepengurusan tidak ditemukan');
        }
        $request->validate([
            'jabatan' => 'required|integer',
            'nama_mahasiswa' => 'required|string|max:100',
            'npm' => 'required|string|unique:mahasiswa,npm',
            'id_divisi' => 'required|integer',
        ]);

        DB::transaction(function () use ($request, $id_kepengurusan) {
            DB::table('mahasiswa')->insert([
                'nama_mahasiswa' => $request->nama_mahasiswa,
                'npm' => $request->npm,
            ]);

            DB::table('detail_kepengurusan_divisi')->insert([
                'id_detail_kepengurusan' => $id_kepengurusan,
                'jabatan' => $request->jabatan,
                'npm' => $request->npm,
                'id_divisi' => $request->id_divisi,
            ]);
        });

        return back()->with('error', 'Gagal menyimpan data detail kepengurusan');
    }


    public function deleteDetailKepengurusanDivisi($npm)
    {
        DB::table('detail_kepengurusan_divisi')->where('npm', $npm)->delete();
        DB::table('mahasiswa')->where('npm', $npm)->delete();

        Session::flash('success', 'Data detail kepengurusan divisi dan data mahasiswa berhasil dihapus.');
        return redirect()->route('datapengurusormawa');
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

    public function strukturormawa()
    {

        return view('ormawa/struktur');
    }

    public function pengesahan($encryptedId)
    {
        try {
            $id_proposal = Crypt::decryptString($encryptedId);
            $proposal = DB::table('proposal_kegiatan')
                ->select('id_proposal', 'tema', 'judul_kegiatan')
                ->where('id_proposal', $id_proposal)
                ->first();

            if ($proposal) {
                $statuses = DB::table('detail_proposal')
                    ->select('status_kaprodi', 'status_kemahasiswaan', 'status_wr3', 'status_dekanat', 'status_akhir')
                    ->where('id_proposal', $id_proposal)
                    ->first();
                $proposal->statuses = $statuses;

                return view('ormawa.pengesahan', compact('proposal'));
            } else {
                return view('ormawa.pengesahan')->with('error', 'Proposal tidak ditemukan.');
            }
        } catch (\Exception $e) {
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
