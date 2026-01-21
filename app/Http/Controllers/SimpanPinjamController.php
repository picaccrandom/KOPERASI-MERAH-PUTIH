<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SimpanPinjamController extends Controller
{
    // Kembalikan halaman utama
    public function index()
    {
        return view('simpanpinjam');
    }

    //kembalikan halaman pinjaman
    public function pinjamanIndex()
    {
        return view('pinjaman');
    }

    //kembalikan halaman simpanan
    public function simpananIndex()
    {
        return view('simpanpinjam');
    }

    //kembalikan halaman create pinjaman
    public function createPinjaman()
    {
        return view('pinjaman');
    }


    //kembalikan halaman create simpanan
    public function createSimpanan()
    {
        // return view('admin.simpanan.create');
    }
}