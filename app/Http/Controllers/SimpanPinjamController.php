<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SimpanPinjamController extends Controller
{
    // Kembalikan halaman utama
    public function index()
    {
        return view('simpanpinjam.simpanpinjam');
    }

}