<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SimpanPinjamController extends Controller
{
    // Kembalikan halaman utama
    public function index()
    {
        return view('admin.simpanpinjam');
    }

    //kembalikan halaman pinjaman
    public function pinjamanIndex()
    {
        return view('admin.pinjaman');
    }

    //kembalikan halaman simpanan
    public function simpananIndex()
    {
        return view('admin.simpanpinjam');
    }

    //kembalikan halaman create pinjaman
    public function createPinjaman()
    {
        return view('admin.pinjaman');
    }


    //kembalikan halaman create simpanan
    public function createSimpanan()
    {
        // return view('admin.simpanan.create');
    }
}