<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SimpanPinjamController extends Controller
{
    /**
     * Display the simpan pinjam dashboard
     */
    public function index()
    {
        return view('simpanpinjam.index');
    }

    /**
     * Display the pinjaman index page
     */
    public function pinjamanIndex()
    {
        return view('simpanpinjam.pinjaman.index');
    }

    /**
     * Display the create pinjaman form
     */
    public function createPinjaman()
    {
        return view('simpanpinjam.pinjaman.create');
    }

    /**
     * Display the simpanan index page
     */
    public function simpananIndex()
    {
        return view('simpanpinjam.simpanan.index');
    }

    /**
     * Display the create simpanan form
     */
    public function createSimpanan()
    {
        return view('simpanpinjam.simpanan.create');
    }
}