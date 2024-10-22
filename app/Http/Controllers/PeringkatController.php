<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Alternatif;


class PeringkatController extends Controller
{
    //fungsi memasukkan data ke table alternatif dan memilih periode

 public function inputdata(){
     
   // Ambil semua id_ormawa
   $id_ormawas = DB::table('kepengurusan_ormawa')->pluck('id_ormawa')->toArray();

   //mengambil data periode 
   $pilihanperiode = Alternatif::select('periode')->get();
   
   // Array untuk menyimpan hasil
   $hasil1 = [];
   $hasil2 = [];
   $hasil3 = [];

   foreach ($id_ormawas as $id_ormawa) {
       // Query untuk menghitung jumlah laporan
       $query1 = DB::table('laporan')
           ->join('kepengurusan_ormawa', 'laporan.id_kepengurusan', '=', 'kepengurusan_ormawa.id_kepengurusan')
           ->select(DB::raw('COUNT(laporan.id_laporan) as jumlah_laporan'))
           ->where('laporan.id_ormawa', $id_ormawa)
           ->where('kepengurusan_ormawa.periode', function($query) {
               $query->select(DB::raw('MAX(periode)'))
                     ->from('kepengurusan_ormawa');
           })
           ->groupBy('kepengurusan_ormawa.id_ormawa', 'kepengurusan_ormawa.periode')
           ->orderBy('jumlah_laporan', 'desc')
           ->first(); // Menggunakan first() untuk mendapatkan satu baris pertama

       if ($query1) {
           // Jika query1 tidak null, ambil nilai jumlah_laporan dan konversi menjadi float
           $jumlah_laporan = (float) $query1->jumlah_laporan;
       } else {
           // Jika query1 null, set jumlah_laporan menjadi 0 atau nilai default yang sesuai
           $jumlah_laporan = 0.0; // Misalnya, bisa diatur menjadi 0.0 atau null sesuai kebutuhan
       }

       // Query untuk menghitung jumlah proker
       $query2 = DB::table('proker')
           ->join('kepengurusan_ormawa', 'proker.IdPengurus', '=', 'kepengurusan_ormawa.id_kepengurusan')
           ->select(DB::raw('COUNT(proker.id_proker) as jumlah_proker'))
           ->where('proker.id_ormawa', $id_ormawa)
           ->where('kepengurusan_ormawa.periode', function($query) {
               $query->select(DB::raw('MAX(periode)'))
                     ->from('kepengurusan_ormawa');
           })
           ->groupBy('kepengurusan_ormawa.periode')
           ->first(); // Menggunakan first() untuk mendapatkan satu baris pertama

       if ($query2) {
           // Jika query2 tidak null, ambil nilai jumlah_proker dan konversi menjadi float
           $jumlah_proker = (float) $query2->jumlah_proker;
       } else {
           // Jika query2 null, set jumlah_proker menjadi 0 atau nilai default yang sesuai
           $jumlah_proker = 0.0; // Misalnya, bisa diatur menjadi 0.0 atau null sesuai kebutuhan
       }

       // Menghitung selisih jumlah laporan dan jumlah proker
       $selisih1 = $jumlah_laporan - $jumlah_proker;
       
       //logikan menentukan nilai C1
    if ($selisih1 >= -2 && $selisih1 <= 2) {
        $C3 = 5;
    } elseif (($selisih1 < -2 && $selisih1 >= -5) || ($selisih1 >= 3 && $selisih1 <= 5)) {
        $C3 = 3;
    } else {
        $C3 = 1;
    }
    
    

       $hasil1[] = [
           'id_ormawa' => $id_ormawa,
           'selisih1' => $selisih1,
           'C3' => $C3,
       ];

       $query3 = DB::table('proker')
->select('proker.id_ormawa', DB::raw('SUM(proker.estimasi_anggaran) as total_anggaran'))
->join('kepengurusan_ormawa', 'proker.IdPengurus', '=', 'kepengurusan_ormawa.id_kepengurusan')
->where('proker.id_ormawa', $id_ormawa)
->where('kepengurusan_ormawa.periode', function ($query) use ($id_ormawa) {
   $query->select(DB::raw('MAX(periode)'))
       ->from('kepengurusan_ormawa')
       ->where('id_ormawa', $id_ormawa);
})
->groupBy('proker.id_ormawa')
->first();

if ($query3) {
   // Jika query1 tidak null, ambil nilai jumlah_laporan dan konversi menjadi float
   $total_anggaran = (float) $query3->total_anggaran;
} else {
   // Jika query1 null, set jumlah_laporan menjadi 0 atau nilai default yang sesuai
   $total_anggaran = 0.0; // Misalnya, bisa diatur menjadi 0.0 atau null sesuai kebutuhan
}

$query4 = DB::table('proposal_kegiatan')
->select(DB::raw('SUM(proposal_kegiatan.anggaran_kegiatan) as total_anggaran_terpakai'), 'proposal_kegiatan.id_ormawa')
->join('kepengurusan_ormawa', 'proposal_kegiatan.id_kepengurusan', '=', 'kepengurusan_ormawa.id_kepengurusan')
->where('proposal_kegiatan.id_ormawa', $id_ormawa)
->where('kepengurusan_ormawa.periode', function ($query) use ($id_ormawa) {
   $query->select(DB::raw('MAX(periode)'))
       ->from('kepengurusan_ormawa')
       ->where('id_ormawa', $id_ormawa);
})
->groupBy('proposal_kegiatan.id_ormawa')
->first();

if ($query4) {
   // Jika query1 tidak null, ambil nilai jumlah_laporan dan konversi menjadi float
   $total_anggaran_terpakai = (float) $query4->total_anggaran_terpakai;
} else {
   // Jika query1 null, set jumlah_laporan menjadi 0 atau nilai default yang sesuai
   $total_anggaran_terpakai = 0.0; // Misalnya, bisa diatur menjadi 0.0 atau null sesuai kebutuhan
}

// Menghitung selisih jumlah laporan dan jumlah proker
$selisih2 = $total_anggaran_terpakai - $total_anggaran;

if ($selisih2 >= -3000000 && $selisih2 <= 3000000) {
    $C2 = 5;
} elseif (($selisih2 > 3000000 && $selisih2 <= 6000000) || ($selisih2 < -3000000 && $selisih2 >= -6000000)) {
    $C2 = 3;
} else {
    $C2 = 1;
}  




// Tambahkan hasil query ke dalam array hasil2 => 1
   $hasil2[] = [
       'id_ormawa' => $id_ormawa,
       'selisih2' => $selisih2,
       'C2' => $C2,
       ];

       $query5 = DB::table('laporan')
       ->select(DB::raw('SUM(laporan.dana_terpakai) as total_dana_terlapor'))
       ->join('kepengurusan_ormawa', 'laporan.id_kepengurusan', '=', 'kepengurusan_ormawa.id_kepengurusan')
       ->where('laporan.id_ormawa', $id_ormawa)
       ->where('kepengurusan_ormawa.periode', function($query) {
               $query->select(DB::raw('MAX(periode)'))
       ->from('kepengurusan_ormawa')
       ->whereColumn('kepengurusan_ormawa.id_ormawa', 'laporan.id_ormawa');
           })
       ->first();

       if ($query5) {
           // Jika query1 tidak null, ambil nilai jumlah_laporan dan konversi menjadi float
           $total_dana_terlapor = (float) $query5->total_dana_terlapor;
       } else {
           // Jika query1 null, set jumlah_laporan menjadi 0 atau nilai default yang sesuai
           $total_dana_terlapor = 0.0; // Misalnya, bisa diatur menjadi 0.0 atau null sesuai kebutuhan
       }

       $query6 = DB::table('proposal_kegiatan')
       ->select(DB::raw('SUM(proposal_kegiatan.anggaran_kegiatan) as total_dana_terpakai'), 'proposal_kegiatan.id_ormawa')
       ->join('kepengurusan_ormawa', 'proposal_kegiatan.id_kepengurusan', '=', 'kepengurusan_ormawa.id_kepengurusan')
       ->where('proposal_kegiatan.id_ormawa', $id_ormawa)
       ->where('kepengurusan_ormawa.periode', function ($query) use ($id_ormawa) {
           $query->select(DB::raw('MAX(periode)'))
               ->from('kepengurusan_ormawa')
               ->where('id_ormawa', $id_ormawa);
       })
       ->groupBy('proposal_kegiatan.id_ormawa')
       ->first();

       if ($query6) {
           // Jika query1 tidak null, ambil nilai jumlah_laporan dan konversi menjadi float
           $total_dana_terpakai = (float) $query6->total_dana_terpakai;
       } else {
           // Jika query1 null, set jumlah_laporan menjadi 0 atau nilai default yang sesuai
           $total_dana_terpakai = 0.0; // Misalnya, bisa diatur menjadi 0.0 atau null sesuai kebutuhan
       }

        //logikan menentukan nilai C1 hasil => 5
        $selisih3 = $total_dana_terlapor - $total_dana_terpakai;

        if ($selisih3 >= -3000000 && $selisih3 <= 3000000) {
            $C1 = 5;
        } elseif (($selisih3 > 3000000 && $selisih3 <= 6000000) || ($selisih3 < -3000000 && $selisih3 >= -6000000)) {
            $C1 = 3;
        } else {
            $C1 = 1;
        }  

        



       $hasil3[] = [
           'id_ormawa' => $id_ormawa,
           'selisih3' => $selisih3,
           'C1' => $C1,
           ];

           $periode = DB::table('kepengurusan_ormawa')
           ->where('id_ormawa', $id_ormawa)
           ->max('periode');

           Alternatif::updateOrCreate(
               ['id_ormawa' => $id_ormawa, 'periode' => $periode],
               ['C1' => $C1, 'C2' => $C2, 'C3' => $C3]
           );


   } 

          // Menampilkan hasil
   return view('admin/peringkat', ['pilihanperiode' => $pilihanperiode]);

 }

 public function handleNormalisasi(Request $request) {
   $periode = $request->input('period');
   return redirect()->route('normalisasi.get', ['periode' => $periode]);
}

public function normalisasi($periode) {

   //nilai  kriteria ketepatan proker(C3), biaya proposal-proker(C2), biaya laporan-proker(C1)
    /*
    tabel matrix kriteria :
        [ 1    1/3   1/5  ]
        [ 3      1   3/5  ]
        [ 5    5/3    1   ]
    
    tabel matrix normalisasi :
        [ 0.111   0.111   0.111  ]
        [ 0.333   0.333   0.333  ]
        [ 0.556   0.556   0.556  ]
     
    nilai akhir kriteria :
        [ 0.111, 0.333, 0.556 ] => C1 = 0.556, C2 = 0.111, C3 = 0.333
   */

   // Ambil data dari kolom 'id_ormawa' dan 'c1'
   $datac1 = DB::table('alternatif')
       ->join('ormawa', 'alternatif.id_ormawa', '=', 'ormawa.id_ormawa')
       ->select('ormawa.nama_ormawa', 'ormawa.id_ormawa', 'alternatif.C1')
       ->where('alternatif.periode', '=', $periode)
       ->get();

   // Buat array untuk menyimpan matriks perbandingan berpasangan
   $pairwiseMatrix1 = [];

   // Buat matriks perbandingan berpasangan berdasarkan nilai 'c1'
   foreach ($datac1 as $i => $row) {
       $pairwiseMatrix1[$i] = [];
       foreach ($datac1 as $j => $col) {
           $pairwiseMatrix1[$i][$j] = $row->C1 / $col->C1;
       }
   }

   // Hitung jumlah setiap kolom
   $columnSums1 = array_fill(0, count($datac1), 0);
   foreach ($pairwiseMatrix1 as $row) {
       foreach ($row as $j => $value) {
           $columnSums1[$j] += $value;
       }
   }

   // Normalisasi matriks perbandingan
   $normalizedMatrix1 = [];
   foreach ($pairwiseMatrix1 as $i => $row) {
       $normalizedMatrix1[$i] = [];
       foreach ($row as $j => $value) {
           $normalizedMatrix1[$i][$j] = $value / $columnSums1[$j];
       }
   }

   // Hitung rata-rata dari setiap baris yang dinormalisasi untuk mendapatkan bobot alternatif
   $weights1 = [];
   foreach ($normalizedMatrix1 as $i => $row) {
       $weights1[$i] = array_sum($row) / count($row);
   }

   // Lakukan hal yang sama untuk c2
   $datac2 = DB::table('alternatif')
   ->join('ormawa', 'alternatif.id_ormawa', '=', 'ormawa.id_ormawa')
   ->select('ormawa.nama_ormawa', 'ormawa.id_ormawa', 'alternatif.C2')
   ->where('alternatif.periode', '=', $periode)
   ->get();

   // Buat array untuk menyimpan matriks perbandingan berpasangan
   $pairwiseMatrix2 = [];

   // Buat matriks perbandingan berpasangan berdasarkan nilai 'c2'
   foreach ($datac2 as $i => $row) {
       $pairwiseMatrix2[$i] = [];
       foreach ($datac2 as $j => $col) {
           $pairwiseMatrix2[$i][$j] = $row->C2 / $col->C2;
       }
   }

   // Hitung jumlah setiap kolom
   $columnSums2 = array_fill(0, count($datac2), 0);
   foreach ($pairwiseMatrix2 as $row) {
       foreach ($row as $j => $value) {
           $columnSums2[$j] += $value;
       }
   }

   // Normalisasi matriks perbandingan
   $normalizedMatrix2 = [];
   foreach ($pairwiseMatrix2 as $i => $row) {
       $normalizedMatrix2[$i] = [];
       foreach ($row as $j => $value) {
           $normalizedMatrix2[$i][$j] = $value / $columnSums2[$j];
       }
   }

   // Hitung rata-rata dari setiap baris yang dinormalisasi untuk mendapatkan bobot alternatif
   $weights2 = [];
   foreach ($normalizedMatrix2 as $i => $row) {
       $weights2[$i] = array_sum($row) / count($row);
   }

   // Lakukan hal yang sama untuk c3
   $datac3 = DB::table('alternatif')
   ->join('ormawa', 'alternatif.id_ormawa', '=', 'ormawa.id_ormawa')
   ->select('ormawa.nama_ormawa', 'ormawa.id_ormawa', 'alternatif.C3')
   ->where('alternatif.periode', '=', $periode)
   ->get();

   // Buat array untuk menyimpan matriks perbandingan berpasangan
   $pairwiseMatrix3 = [];

   // Buat matriks perbandingan berpasangan berdasarkan nilai 'c3'
   foreach ($datac3 as $i => $row) {
       $pairwiseMatrix3[$i] = [];
       foreach ($datac3 as $j => $col) {
           $pairwiseMatrix3[$i][$j] = $row->C3 / $col->C3;
       }
   }

   // Hitung jumlah setiap kolom
   $columnSums3 = array_fill(0, count($datac3), 0);
   foreach ($pairwiseMatrix3 as $row) {
       foreach ($row as $j => $value) {
           $columnSums3[$j] += $value;
       }
   }

   // Normalisasi matriks perbandingan
   $normalizedMatrix3 = [];
   foreach ($pairwiseMatrix3 as $i => $row) {
       $normalizedMatrix3[$i] = [];
       foreach ($row as $j => $value) {
           $normalizedMatrix3[$i][$j] = $value / $columnSums3[$j];
       }
   }

   // Hitung rata-rata dari setiap baris yang dinormalisasi untuk mendapatkan bobot alternatif
   $weights3 = [];
   foreach ($normalizedMatrix3 as $i => $row) {
       $weights3[$i] = array_sum($row) / count($row);
   }

   // Gabungkan hasil dengan ID Ormawa untuk c1
   $normalizedData1 = [];
   foreach ($datac1 as $i => $row) {
       $normalizedData1[] = [
           'id_ormawa' => $row->id_ormawa,
           'nama_ormawa' => $row->nama_ormawa,
           'weight' => $weights1[$i] * 0.556
       ];
   }

   // Gabungkan hasil dengan ID Ormawa untuk c2
   $normalizedData2 = [];
   foreach ($datac2 as $i => $row) {
       $normalizedData2[] = [
           'id_ormawa' => $row->id_ormawa,
           'nama_ormawa' => $row->nama_ormawa,
           'weight' => $weights2[$i] * 0.111 
       ];
   }

   // Gabungkan hasil dengan ID Ormawa untuk c3
   $normalizedData3 = [];
   foreach ($datac3 as $i => $row) {
       $normalizedData3[] = [
           'id_ormawa' => $row->id_ormawa,
           'nama_ormawa' => $row->nama_ormawa,
           'weight' => $weights3[$i] * 0.333
       ];
   }

   // Menghitung nilai totalc
   $final = [];
   for ($i = 0; $i < sizeof($normalizedData3); $i++) {
       $final[] = [
           'id_ormawa' => $normalizedData1[$i]['id_ormawa'],
           'nama_ormawa' => $normalizedData1[$i]['nama_ormawa'],
           'totalc' => $normalizedData1[$i]['weight'] + $normalizedData2[$i]['weight'] + $normalizedData3[$i]['weight']
       ];
   }

   // Mengurutkan berdasarkan nilai totalc
   usort($final, function ($a, $b) {
       return $b['totalc'] <=> $a['totalc'];
   });

   // Mendapatkan nilai minimum dan maksimum dari totalc
   $minTotalc = min(array_column($final, 'totalc'));
   $maxTotalc = max(array_column($final, 'totalc'));

   // Menentukan batasan kelas
   $range = $maxTotalc - $minTotalc;
   $baikSekaliThreshold = $minTotalc + 2 * ($range / 3);
   $baikThreshold = $minTotalc + ($range / 3);

   // Mengklasifikasikan nilai-nilai ke dalam kelas
   foreach ($final as &$item) {
       if ($item['totalc'] >= $baikSekaliThreshold) {
           $item['kelas'] = 'Unggul';
       } elseif ($item['totalc'] >= $baikThreshold) {
           $item['kelas'] = 'Baik Sekali';
       } else {
           $item['kelas'] = 'Baik';
       }
   }
   

   // Mengembalikan data ke view
   return view('admin/hasil', ['datac1' => $datac1,
            'normalizedData1' => $normalizedData1,
            'datac2' => $datac2,
            'normalizedData2' => $normalizedData2,
            'datac3' => $datac3,
            'normalizedData3' => $normalizedData3,
            'final' => $final,
            'periode' => $periode]);
}

public function datastatistik($id){
   
   //nama ormawa
   $data1 = DB::table('ormawa')
   ->where('id_ormawa', $id)
   ->value('nama_ormawa');

   //jumlah proker wajib
   $data2 = DB::table('proker')
   ->join('ormawa', 'proker.id_ormawa', '=', 'ormawa.id_ormawa')
   ->where('proker.jenis_kegiatan', 'Wajib')
   ->where('ormawa.id_ormawa', $id)
   ->count('proker.id_proker');

    //jumlah proker kebidangan
    $data3 = DB::table('proker')
    ->join('ormawa', 'proker.id_ormawa', '=', 'ormawa.id_ormawa')
    ->where('proker.jenis_kegiatan', 'Kebidangan')
    ->where('ormawa.id_ormawa', $id)
    ->count('proker.id_proker');

    //jumlah proker unggulan
    $data10 = DB::table('proker')
    ->join('ormawa', 'proker.id_ormawa', '=', 'ormawa.id_ormawa')
    ->where('proker.jenis_kegiatan', 'Unggulan')
    ->where('ormawa.id_ormawa', $id)
    ->count('proker.id_proker');
    
    //jumlah total proker
    $data11 = DB::table('proker')
    ->where('id_ormawa', $id)
    ->count('id_proker');

    //jumlah proker yang di ajukan 
    $data4 = DB::table('proker')
    ->where('id_ormawa', $id)
    ->count('id_proker');

    //jumlah proposal yang di ajukan
    $data5 = DB::table('proposal_kegiatan')
            ->where('id_ormawa', $id)
            ->count();

   //jumlah laporan yang di ajukan 
   $data6 = DB::table('laporan')
            ->where('id_ormawa', $id)
            ->count();
   
   //jumlah dana yang diajukan di proker
   $data7 = DB::table('proker')
            ->where('id_ormawa', $id)
            ->sum('estimasi_anggaran');

   //jumlah dana yang diajukan di proposal
   $data8 = DB::table('proposal_kegiatan')
                    ->where('id_ormawa', $id)
                    ->sum('anggaran_kegiatan');

   //jumlah dana yang diajukan di laporan 
   $data9 = DB::table('laporan')
                    ->where('id_ormawa', $id)
                    ->sum('dana_terpakai');

   return view('admin/datastatistik', ['data1' => $data1,
                                     'data2' => $data2, 
                                     'data3' => $data3,
                                     'data4' => $data4,
                                     'data5' => $data5,
                                     'data6' => $data6,
                                     'data7' => $data7,
                                     'data8' => $data8,
                                     'data9' => $data9,
                                     'data10' => $data10,
                                     'data11' => $data11,]);
}
}
