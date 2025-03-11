<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RektorController;
use App\Http\Controllers\FakultasController;
use App\Http\Controllers\OrmawaController;
use App\Http\Controllers\ProdiController;
use App\Http\Controllers\PeringkatController;
use Illuminate\Routing\Route as RoutingRoute;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//tampilan awal landing page
Route::get('/', function () {
    return view('home', ['title' => 'Home']);
})->name('home');

//login, tambah user, ganti password dan logout
//Route::get('register', [UserController::class, 'register'])->name('register');
Route::get('login', [UserController::class, 'login'])->name('login');
Route::post('login', [UserController::class, 'login_action'])->name('login.action');


Route::post('register', [UserController::class, 'register_action'])->name('register.action');
Route::get('password', [UserController::class, 'password'])->middleware('auth')->name('password');
Route::post('password', [UserController::class, 'password_action'])->name('password.action');
Route::get('logout', [UserController::class, 'logout'])->name('logout');


//---------------------------------------route admin---------------------------------------
Route::get('/data', [AdminController::class, 'read'])->name('read');
Route::get('/tambah-ormawa', [AdminController::class, 'create'])->name('create');
Route::get('/tambah-data', [AdminController::class, 'store'])->name('store');
Route::delete('/ormawa/{id}', [AdminController::class, 'destroy'])->name('ormawa.destroy');
Route::get('/show/{id}', [AdminController::class, 'show']);
Route::get('/informasi{id}', [AdminController::class, 'informasi'])->name('informasi');
Route::get('/struktur-organisasi', [AdminController::class, 'struktur'])->name('struktur');
Route::get('/tambah-user', [AdminController::class, 'tambahUser'])->name('tambahUser');
Route::get('/tambah-form', [AdminController::class, 'tambah'])->name('tambah');
Route::delete('/users/{user_id}', [AdminController::class, 'hapus'])->name('hapus');
Route::get('/monitoring-admin', [AdminController::class, 'monitoring'])->name('monitoring');
Route::get('/detail-monitor/{id}', [AdminController::class, 'detailmonitor'])->name('detailmonitor');
Route::post('/update-monitor/{id}', [AdminController::class, 'updateMonitor'])->name('updateMonitor');
Route::get('/analisa', [AdminController::class, 'statis'])->name('statis');
Route::get('/proposal-kegiatan-admin', [AdminController::class, 'proposalkegiatan'])->name('proposalkegiatan');
Route::get('/proposal-detail-kegiatan-admin{id}', [AdminController::class, 'detailproposaladmin'])->name('detailproposaladmin');
Route::get('/laporan-kegiatan-admin', [AdminController::class, 'laporankegiatan'])->name('laporankegiatan');
Route::get('/proposal-eksidentil-admin', [AdminController::class, 'proposaleksidentil'])->name('proposaleksidentil');
Route::get('/proposal-detail-eksidentil-admin{id}', [AdminController::class, 'detaileksidentil'])->name('detaileksidentil');
Route::get('/laporan-detail-kegiatan-admin{id}', [AdminController::class, 'detaillaporan'])->name('detaillaporan');
Route::put('/update-laporan-kemahasiswaan/{id}', [AdminController::class, 'updatelaporankemahasiswaan'])->name('updatelaporankemahasiswaan');
Route::post('/acc-laporan-kegiatan/{id}', [AdminController::class, 'acclaporankegiatan'])->name('acclaporankegiatan');
Route::get('/laporan-tahunan-admin', [AdminController::class, 'laporantahunan'])->name('laporantahunan');
Route::get('/laporan-detail-tahunan-admin{id}', [AdminController::class, 'detailtahunan'])->name('detailtahunan');
Route::post('/acc-laporan-tahunan/{id}', [AdminController::class, 'acclaporantahunan'])->name('acclaporantahunan');
Route::put('/proposal_kemahasiswaan/{id}', [AdminController::class, 'updateproposalkemahasiswaan'])->name('updateproposalkemahasiswaan');
Route::put('/acc_proposal_kemahasiswaan/{id}', [AdminController::class, 'accProposal_kemahasiswaan'])->name('accproposalkemahasiswaan');
Route::get('/proposalsimilarity/{id_proposal}', [AdminController::class, 'detailproposalsimilarity'])->name('detailsimilarity');
Route::post('/store-kepengurusan', [AdminController::class, 'storeKepengurusanOrmawa'])->name('store.kepengurusan');
Route::get('/input-periode', [AdminController::class, 'formperiode'])->name('input_periode');
Route::get('/get-ormawa/{id}', 'AdminController@getOrmawa');
Route::post('/inputdatabph', [AdminController::class, 'inputdatabph'])->name('inputdatabph');
// Route::get('/proposals/similar/{id_proposal}', [AdminController::class, 'detailfindSimilarProposals'])->name('proposals.similar');
// Route::get('/find-similar-proposals', [AdminController::class, 'findSimilarProposals'])->name('find.similar.proposals');

Route::middleware(['web'])->group(function () {
    Route::get('/find-similar-proposals', [AdminController::class, 'findSimilarProposals'])->name('find.similar.proposals');
    Route::get('/detail-similar-proposals/{id_proposal}', [AdminController::class, 'detailfindSimilarProposals'])->name('proposals.similar');
});


//---------------------------------------route wakil rektor 3--------------------------------------- kurang proposal
Route::get('/data-hima', [RektorController::class, 'baca'])->name('baca');
Route::get('/struktur-organisasi1', [RektorController::class, 'struktur1'])->name('struktur1');
Route::get('/laporan-kegiatan-rektor', [RektorController::class, 'laporankegiatanwr3'])->name('laporankegiatanwr3');
Route::post('/acc-laporan-kegiatan-wr3/{id}', [RektorController::class, 'acclaporankegiatanwr3'])->name('acclaporankegiatanwr3');
Route::get('/laporan-tahunan-rektor', [RektorController::class, 'laporantahunanwr3'])->name('laporantahunanwr3');
Route::get('/laporan-detail-tahunan-rektor{id}', [RektorController::class, 'detailtahunanwr3'])->name('detailtahunanwr3');
Route::post('/acc-laporan-tahunan-wr3/{id}', [RektorController::class, 'acclaporantahunanwr3'])->name('acclaporantahunanwr3');
Route::get('/analisa1', [RektorController::class, 'statis1'])->name('statis1');
Route::get('/rektor-info{id}', [RektorController::class, 'informasiRektor'])->name('informasiRektor');
Route::get('/proposalkegiatanrektor', [RektorController::class, 'proposalkegiatanrektor'])->name('proposalkegiatanrektor');
Route::get('/proposalinsidentilrektor', [RektorController::class, 'proposalinsidentilrektor'])->name('proposalinsidentilrektor');
Route::get('/detail-proposal-kegiatan-rektor/{encryptedId}', [RektorController::class, 'tampilpropprokerrektor'])->name('tampilpropprokerrektor');
Route::get('/detail-proposal-insidentil-rektor/{encryptedId}', [RektorController::class, 'tampilpropinsidentilrektor'])->name('tampilpropinsidentilrektor');
Route::put('/revisi-proposal-rektor/{id}', [RektorController::class, 'updateproposalrektor'])->name('updateproposalrektor');
Route::put('/acc_proposal_rektor/{id}', [RektorController::class, 'accProposal_rektor'])->name('accProposal_rektor');
//---------------------------------------route wakil rektor 3 penambahan fitur laporan---------------------------------------
Route::get('/list-laporan-wr3', [RektorController::class, 'ListLaporanRektor'])->name('ListLaporanRektor');
Route::get('/list-laporan-tahunan-wr3', [RektorController::class, 'ListLaporanTahunanRektor'])->name('ListLaporanTahunanRektor');
Route::get('/laporan-detail-kegiatan-rektor{id}', [RektorController::class, 'detaillaporanwr3'])->name('detaillaporanwr3');
Route::put('/update-laporan-rektor/{id}', [RektorController::class, 'UpdateLaporanRektor'])->name('UpdateLaporanRektor');
Route::put('/update-proposal/{id}', [RektorController::class, 'updateproposal'])->name('update-proposal');

//---------------------------------------route fakultas---------------------------------------


//---------------------------------------saintek
Route::get('/datahimafst', [FakultasController::class, 'ormawafst'])->name('ormawafst');
Route::get('/datapropprokerfst', [FakultasController::class, 'proposalkegiatanprokerFST'])->name('proposalkegiatanprokerFST');
Route::get('/datapropinsidentilfst', [FakultasController::class, 'proposalkegiataninsidentilFST'])->name('proposalkegiataninsidentilFST');
Route::get('/datalaporankegiatanfst', [FakultasController::class, 'laporankegiatanFST'])->name('laporankegiatanFST');
Route::get('/datalaporantahunanfst', [FakultasController::class, 'laporantahunanFST'])->name('laporantahunanFST');
Route::get('/monitoring-kegiatanfst', [FakultasController::class, 'monitoringkegiatanfst'])->name('monitoringkegiatanFST');
Route::put('/update-proposalFakultas/{id}', [FakultasController::class, 'updateproposalfakultas'])->name('update.proposalFakultasSaintek');
Route::get('/detail-proposal/{id_proposal}', [FakultasController::class, 'detailProposalFakultas'])->name('detail_proposal_fakultas_saintek');
Route::get('/detail-monitor-kegiatanfst/{id}', [FakultasController::class, 'detailmonitorkegiatanFakultas'])->name('detail.monitor.kegiatan.Fakultas');
Route::get('/detail-laporan-fakultas/{id_laporan}', [FakultasController::class, 'showDetailLaporanFakultas'])->name('showDetailLaporanFakultas');
Route::put('/acc-proposal-fakultas/{id}', [FakultasController::class, 'accProposalFakultas'])->name('acc.proposal.fakultas');
Route::put('/update-laporan-fakultas/{id}', [FakultasController::class, 'updatelaporanFakultas'])->name('update.laporan.fakultas');
Route::put('/acc-laporan-fakultas/{id}', [FakultasController::class, 'acclaporanfakultas'])->name('acc.laporan.fakultas');
Route::get('/data-kepengurusan-ormawa-fakultas/{encryptedId}', [FakultasController::class, 'datakepengurusanormawaFakultas'])->name('datakepengurusanormawaFakultas');
Route::get('/detail-proker-fakultas/{encryptedId}', [FakultasController::class, 'datadetailprokerFakultas'])->name('detailProkerFakultas');

//---------------------------------------bishum
Route::get('/datahimabishum', [FakultasController::class, 'ormawabishum'])->name('ormawabishum');
Route::get('/proposal-kegiatan-proker-bishum', [FakultasController::class, 'proposalkegiatanprokerbishum'])->name('proposalkegiatanprokerbishum');
Route::get('/proposal-kegiatan-insidentil-bishum', [FakultasController::class, 'proposalkegiataninsidentilbishum'])->name('proposalkegiataninsidentilbishum');
Route::get('/laporan-kegiatan-bishum', [FakultasController::class, 'laporankegiatanbishum'])->name('laporankegiatanbishum');
Route::get('/laporan-tahunan-bishum', [FakultasController::class, 'laporantahunanbishum'])->name('laporantahunanbishum');
Route::get('/monitoring-kegiatan-bishum', [FakultasController::class, 'monitoringkegiatanbishum'])->name('monitoringkegiatanbishum');
Route::put('/update-proposalFakultasbishum/{id}', [FakultasController::class, 'updateproposalfakultasbishum'])->name('update.proposalFakultasBishum');
Route::get('/detail-proposal-bishum/{id_proposal}', [FakultasController::class, 'detailProposalfakultasbishum'])->name('detail_proposal_fakultas');
Route::get('/detail-monitor-kegiatanbishum/{id}', [FakultasController::class, 'detailmonitorkegiatanfakultasbishum'])->name('detail.monitor.kegiatan.fakultasbishum');
Route::get('/detail-laporan-fakultasbishum/{id_laporan}', [FakultasController::class, 'showDetailLaporanfakultasbishum'])->name('showDetailLaporanfakultasbishum');
Route::put('/acc-proposal-fakultasbishum/{id}', [FakultasController::class, 'accProposalfakultasbishum'])->name('acc.proposal.fakultasbishum');
Route::put('/update-laporan-fakultasbishum/{id}', [FakultasController::class, 'updatelaporanfakultasbishum'])->name('update.laporan.fakultasbishum');
Route::put('/acc-laporan-fakultasbishum/{id}', [FakultasController::class, 'acclaporanfakultasbishum'])->name('acc.laporan.fakultasbishum');
Route::get('/data-kepengurusan-ormawa-fakultasbishum/{encryptedId}', [FakultasController::class, 'datakepengurusanormawaFakultas'])->name('datakepengurusanormawafakultasbishum');
Route::get('/detail-proker-fakultasbishum/{encryptedId}', [FakultasController::class, 'datadetailprokerfakultasbishum'])->name('detailProkerfakultasbishum');

//---------------------------------------diploma
// Route::get('/data-hima1', [FakultasController::class, 'ormawafst'])->name('ormawafst');

//---------------------------------------route prodi------------------------------------------
Route::get('/DataProdi', [ProdiController::class, 'DataProdi'])->name('DataProdi');
Route::get('/proposal-kegiatan-prodi', [ProdiController::class, 'proposalkegiatanproker'])->name('proposalkegiatanproker');
Route::get('/detail-proposal-kegiatan-prodi/{id_proposal}', [ProdiController::class, 'detailproposal'])->name('detailproposal');
Route::get('/proposal-insidentil', [ProdiController::class, 'proposalinsidentil'])->name('proposalinsidentil');
Route::put('/update-proposal/{id}', [ProdiController::class, 'updateproposal'])->name('update-proposal');
Route::put('/proposal/{id}/acc', [ProdiController::class, 'accProposal'])->name('accProposal');
Route::get('/laporan-kegiatan-prodi', [ProdiController::class, 'laporankegiatanprodi'])->name('laporankegiatanprodi');
Route::get('/laporan-tahunan-prodi', [ProdiController::class, 'laporantahunanprodi'])->name('laporantahunanprodi');
Route::get('/monitoring-kegiatan', [ProdiController::class, 'monitoringkegiatan'])->name('monitoringkegiatan');
Route::get('/detail-monitor-kegiatan/{id}', [ProdiController::class, 'detailmonitorkegiatan'])->name('detail.monitor.kegiatan');
Route::get('/detail-laporan/{id_laporan}', [ProdiController::class, 'showDetailLaporan'])->name('showDetailLaporan');

//update buat ulang sesuai dengan alur bisnis baru
Route::put('/update-laporan-prodi/{id}', [ProdiController::class, 'updatelaporanprodi'])->name('updatelaporanprodi');
Route::put('/acclaporan/{id}', [ProdiController::class, 'acclaporan'])->name('acclaporan');
Route::get('/data-kepengurusan-ormawa', [ProdiController::class, 'datakepengurusanormawa'])->name('datakepengurusanormawa');
Route::get('/detailproker/{id_proker}', [ProdiController::class, 'datadetailproker'])->name('datadetailproker');
Route::get('/struktur-organisasi-prodi', [ProdiController::class, 'strukturprodi'])->name('strukturprodi');


//-----------------route ormawa------------------------------------------
Route::get('ormawa', [OrmawaController::class, 'ormawa'])->name('ormawa');

Route::get('/tampilpropproker', [OrmawaController::class, 'tampilkanPropProker'])->name('tampilkanPropProker');
Route::get('/form-pengajuan-prop-proker', [OrmawaController::class, 'formpropproker'])->name('formpropproker');
Route::post('/pengajuanproposalproker', [OrmawaController::class, 'pengajuanpropproker'])->name('pengajuanpropproker');

Route::get('/pengajuanpropinsidentil', [OrmawaController::class, 'tampilkanPropInsiden'])->name('pengajuanpropinsidentil');
Route::get('/form-pengajuan-insidentil', [OrmawaController::class, 'forminsidentil'])->name('forminsidentil');
Route::post('/pengajuanpropinsidentil', [OrmawaController::class, 'pengajuanpropinsidentil'])->name('pengajuanprop_insidentil');

Route::get('/pdf_proposal/{id_proposal}', [OrmawaController::class, 'showlampiranproposal'])->name('showlampiranproposal');

Route::get('/infopengajuan', [OrmawaController::class, 'informasiPengajuan'])->name('informasiPengajuan');
Route::get('/proposal_kegiatan/{encryptedId}', [OrmawaController::class, 'tampilprop'])->name('tampilprop');
Route::get('/proposal_kegiatan/insidentil/{encryptedId}', [OrmawaController::class, 'tampilpropinsidentil'])->name('tampilpropinsidentil');
Route::get('/laporan/{id_laporan}', [OrmawaController::class, 'TampilDetailLaporan'])->name('DetailLaporan');
Route::get('/databphormawa', [OrmawaController::class, 'datapengurusormawa'])->name('datapengurusormawa');
Route::put('/revisi_proposal_kegiatan/{id_proposal}', [OrmawaController::class, 'updateproposal'])->name('proposal_update');
Route::put('/laporan/{id_laporan}', [OrmawaController::class, 'updateLaporan'])->name('laporan_update');
Route::put('/proker/{id_proker}', [OrmawaController::class, 'updateDataProker'])->name('proker_update');

//pengurus ormawa
Route::get('/pengajuananggotabph', [OrmawaController::class, 'showformbph'])->name('showformbph');
Route::delete('/delete-detail-kepengurusan/{npm}', [OrmawaController::class, 'deleteDetailKepengurusan'])->name('deleteDetailKepengurusan');
Route::get('/show-form-divisi', [OrmawaController::class, 'showFormDivisi'])->name('showFormDivisi');
Route::post('/input-detail-kepengurusan-divisi', [OrmawaController::class, 'inputDetailKepengurusanDivisi'])->name('inputDetailKepengurusanDivisi');
Route::post('/input-detail-kepengurusan-divisi', [OrmawaController::class, 'inputDetailKepengurusanDivisi'])->name('inputDetailKepengurusanDivisi');
Route::delete('/delete-detail-kepengurusan-divisi/{npm}', [OrmawaController::class, 'deleteDetailKepengurusanDivisi'])->name('deleteDetailKepengurusanDivisi');

Route::get('/pdf_proposal/{id_proposal}', [OrmawaController::class, 'showlampiranproposal'])->name('showlampiranproposal');


//route pelaporan LPJ
Route::get('/laporan', [OrmawaController::class, 'tampilkanDataLaporan'])->name('laporan_kegiatan');
Route::get('/lpj/form', [OrmawaController::class, 'showFormLaporan'])->name('form_lpj');
Route::post('/input-laporan', [OrmawaController::class, 'inputLaporan'])->name('input_laporanLPJ');
Route::get('/pdf/{id_laporan}', [OrmawaController::class, 'showlampiran'])->name('showlampiran');
//route pelaporan Tahunan
Route::get('/laporantahunan', [OrmawaController::class, 'DataLaporanTahunan'])->name('laporan_tahunan');
Route::get('/tahunan/form', [OrmawaController::class, 'showFormLaporanTahunan'])->name('form_tahunan');
//route monitoring
Route::get('/monitoring', [OrmawaController::class, 'tampilmonitoring'])->name('tampilmonitoring');
Route::post('/input-monitoring', [OrmawaController::class, 'inputmonitoring'])->name('inputmonitoring');
//route proker
Route::get('/formproker', [OrmawaController::class, 'showFormProker'])->name('showFormProker');
Route::post('/input_proker', [OrmawaController::class, 'inputDataProker'])->name('inputDataProker');
Route::get('/proker/{id_proker}', [OrmawaController::class, 'detailproker'])->name('detailproker');
Route::get('/struktur-organisasi-ormawa', [OrmawaController::class, 'strukturormawa'])->name('strukturormawa');

//upload file proposal proker ke drive
Route::get('/upload-proposal-pdf', function () {
    return view('ormawa/formuploadfile');
})->name('uploadproposalpdf');
Route::post('/upload-proposal-proker', [OrmawaController::class, 'uploadproposalproker'])->name('uploadproposalproker');

//upload file laporan ke drive
Route::get('/upload-laporan-pdf', function () {
    return view('ormawa/formuploadfilelap');
})->name('uploadlaporanpdf');
Route::post('/upload-laporan-kegiatan', [OrmawaController::class, 'uploadlaporankegiatan'])->name('uploadlaporankegiatan');

Route::get('/download-pengesahan', [OrmawaController::class, 'downloadFile'])->name('download.pengesahan');
Route::get('/pengesahan/{encryptedId}', [OrmawaController::class, 'pengesahan'])->name('proposal.pengesahan');
Route::get('/pengesahanlaporan/{encryptedId}', [OrmawaController::class, 'pengesahanlaporan'])->name('laporan.pengesahan');


//route untuk peringkat
Route::get('/spk-input-data', [PeringkatController::class, 'inputdata'])->name('inputdata');
Route::post('/spk-normalisasi', [PeringkatController::class, 'handleNormalisasi'])->name('normalisasi.post');
Route::get('/spk-normalisasi/{periode}', [PeringkatController::class, 'normalisasi'])->name('normalisasi.get');
Route::get('/spk-data-statistik/{id}', [PeringkatController::class, 'datastatistik'])->name('datastatistik');
