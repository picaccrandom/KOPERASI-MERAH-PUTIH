<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Obat;
use Illuminate\Http\Request;
use App\Models\Produk;

class ProdukController extends Controller
{
    public function index()
    {
        try {
            $gerai = Barang::select('id', 'nama_barang as nama', 'harga_jual as harga', 'stok', 'satuan')
                ->where('stok', '>', 0)->get()
                ->map(function($item) {
                    $item['unit'] = 'Gerai';
                    return $item;
                });

            $apotek = Obat::select('id', 'nama_obat as nama', 'harga_jual as harga', 'stok_apotek as stok', 'satuan')
                ->where('stok_apotek', '>', 0)->get()
                ->map(function($item) {
                    $item['unit'] = 'Apotek';
                    return $item;
                });

            return response()->json([
                'success' => true,
                'message' => 'Katalog Produk Nangsri Dimuat',
                'data'    => $gerai->concat($apotek)
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}