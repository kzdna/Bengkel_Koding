<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\Periksa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiwayatPasienController extends Controller
{
    public function index()
    {
        $riwayatPasien = Periksa::whereHas('daftarPoli.jadwalPeriksa', function($query){
            $query->where('id_dokter', Auth::id());
        })->with([
            'daftarPoli.pasien',
            'daftarPoli.jadwalPeriksa.dokter',
            'detailPeriksas.obat'
        ])->orderBy('tgl_periksa', 'desc')->get();

        return view('admin.dokter.riwayat-pasien.index', compact('riwayatPasien'));
    }

    public function show(string $id)
    {
        $periksa = Periksa::whereHas('daftarPoli.jadwalPeriksa', function($query) {
            $query->where('id_dokter', Auth::id());
        })->with([
            'daftarPoli.pasien',
            'daftarPoli.jadwalPeriksa.dokter',
            'detailPeriksas.obat'
        ])->findOrFail($id);

        return view('admin.dokter.riwayat-pasien.show', compact('periksa'));
    }
}