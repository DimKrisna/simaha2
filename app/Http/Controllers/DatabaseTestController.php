<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB; // Import DB facade
use Illuminate\Database\DatabaseManager; // Import DatabaseManager

class DatabaseTestController extends Controller
{
    public function testConnection()
    {
        try {
            DB::connection()->getPdo();
            return "Koneksi database berhasil!";
        } catch (\Exception $e) {
            return "Koneksi database gagal: " . $e->getMessage();
        }
    }
}
