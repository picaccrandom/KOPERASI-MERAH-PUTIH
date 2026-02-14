<?php

namespace App\Http\Controllers\KantorKoperasi;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\Transaksi_SP;
use App\Models\SimpananDetail;
use App\Models\Barang;
use App\Models\Member;
use App\Models\Obat;
use App\Models\TransaksiFaskes;
use App\Models\PendaftaranKlinik;
use App\Models\TransaksiObat;
use App\Models\Jurnal;
use App\Models\Account;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    /**
     * PUSAT LAPORAN TERPADU - Menampilkan berbagai jenis laporan
     */
    public function index(Request $request)
    {
        $type = $request->get('type', 'omzet');
        $tgl_mulai = $request->get('tgl_mulai', date('Y-m-01'));
        $tgl_selesai = $request->get('tgl_selesai', date('Y-m-d'));

        $data = collect();
        $summary = [];

        switch ($type) {
            // 1. LAPORAN OMZET (SEMUA UNIT)
            case 'omzet':
                $gerai = Transaksi::whereBetween('created_at', [$tgl_mulai . ' 00:00:00', $tgl_selesai . ' 23:59:59'])
                    ->get();
                
                $faskes = TransaksiFaskes::whereBetween('created_at', [$tgl_mulai . ' 00:00:00', $tgl_selesai . ' 23:59:59'])
                    ->get();
                
                $data = $gerai->concat($faskes)->sortByDesc('created_at');
                
                $summary = [
                    'total_gerai' => $gerai->sum('grand_total') ?? 0,
                    'total_faskes' => $faskes->sum('total_harga') ?? 0,
                    'total_keseluruhan' => ($gerai->sum('grand_total') ?? 0) + ($faskes->sum('total_harga') ?? 0),
                    'jumlah_transaksi' => $gerai->count() + $faskes->count(),
                ];
                // dd($summary);
                break;

            // 2. LAPORAN SIMPANAN ANGGOTA
            case 'simpanan':
                $data = SimpananDetail::whereBetween('created_at', [$tgl_mulai . ' 00:00:00', $tgl_selesai . ' 23:59:59'])
                    ->with('transaksi.member')
                    ->orderBy('created_at', 'desc')
                    ->get();
                
                $summary = [
                    'total_simpanan' => $data->sum('saldo') ?? 0,
                    'total_pokok' => $data->where('jenis', 'POKOK')->sum('saldo') ?? 0,
                    'total_wajib' => $data->where('jenis', 'WAJIB')->sum('saldo') ?? 0,
                    'total_sukarela' => $data->where('jenis', 'SUKARELA')->sum('saldo') ?? 0,
                    'jumlah_transaksi' => $data->count(),
                ];
                break;

            // 3. LAPORAN PINJAMAN ANGGOTA
            case 'pinjaman':
                $pinjaman = Transaksi_SP::where('COA', 'Pinjaman')
                    ->whereBetween('created_at', [$tgl_mulai . ' 00:00:00', $tgl_selesai . ' 23:59:59'])
                    ->with('member')
                    ->orderBy('created_at', 'desc')
                    ->get();
                
                $angsuran = Transaksi_SP::where('COA', 'Angsuran')
                    ->whereBetween('created_at', [$tgl_mulai . ' 00:00:00', $tgl_selesai . ' 23:59:59'])
                    ->with('member')
                    ->orderBy('created_at', 'desc')
                    ->get();
                
                $data = $pinjaman->concat($angsuran)->sortByDesc('created_at');
                
                $summary = [
                    'total_pinjaman' => $pinjaman->sum('Nominal') ?? 0,
                    'total_angsuran' => $angsuran->sum('Nominal') ?? 0,
                    'saldo_pokok' => ($pinjaman->sum('Nominal') ?? 0) - ($angsuran->sum('Nominal') ?? 0),
                    'jumlah_peminjam' => $pinjaman->count(),
                ];
                break;

            // 4. LAPORAN ANGGOTA AKTIF
            case 'anggota':
                $data = Member::whereBetween('created_at', [$tgl_mulai . ' 00:00:00', $tgl_selesai . ' 23:59:59'])
                    ->orderBy('status', 'asc')
                    ->get();
                
                $summary = [
                    'total_anggota' => $data->count(),
                    'aktif' => $data->where('status', 'aktif')->count(),
                    'nonaktif' => $data->where('status', 'nonaktif')->count(),
                ];
                break;

            // 5. LAPORAN STOK BARANG KRITIS
            case 'stok':
                $data = Barang::where('stok', '<', 15)
                    ->orderBy('stok', 'asc')
                    ->get();
                
                $summary = [
                    'total_item_kritis' => $data->count(),
                    'total_barang' => Barang::count(),
                    'total_stok_keseluruhan' => Barang::sum('stok') ?? 0,
                ];
                break;

            // 6. LAPORAN PIUTANG / BON
            case 'piutang':
                $geraiBon = Transaksi::where('total_bon', '>', 0)
                    ->whereBetween('created_at', [$tgl_mulai . ' 00:00:00', $tgl_selesai . ' 23:59:59'])
                    ->with('member')
                    ->get();

                $faskesBon = TransaksiFaskes::where('Debit/Credit', 'Credit')
                    ->where('Nominal', '>', 0)
                    ->whereBetween('created_at', [$tgl_mulai . ' 00:00:00', $tgl_selesai . ' 23:59:59'])
                    ->with('member')
                    ->get();

                $data = $geraiBon->concat($faskesBon);
                
                $summary = [
                    'total_piutang_gerai' => $geraiBon->sum('total_bon') ?? 0,
                    'total_piutang_faskes' => $faskesBon->sum('Nominal') ?? 0,
                    'total_piutang' => ($geraiBon->sum('total_bon') ?? 0) + ($faskesBon->sum('Nominal') ?? 0),
                ];
                break;

            // 7. LAPORAN KLINIK
            case 'klinik':
                $data = PendaftaranKlinik::whereBetween('created_at', [$tgl_mulai . ' 00:00:00', $tgl_selesai . ' 23:59:59'])
                    ->with(['member', 'rekamMedis'])
                    ->orderBy('created_at', 'desc')
                    ->get();
                
                $summary = [
                    'total_kunjungan' => $data->count(),
                    'total_pendapatan' => $data->sum('biaya_pendaftaran') ?? 0,
                    'rata_rata' => $data->count() > 0 ? ($data->sum('biaya_pendaftaran') ?? 0) / $data->count() : 0,
                ];
                break;

            // 8. LAPORAN LABA RUGI
            case 'labarugi':
                $data = collect();
                
                $pendapatan = Jurnal::whereHas('account', function ($q) {
                    $q->where('kategori', 'Pendapatan');
                })->whereBetween('tgl_transaksi', [$tgl_mulai, $tgl_selesai])->get();

                $beban = Jurnal::whereHas('account', function ($q) {
                    $q->where('kategori', 'Beban');
                })->whereBetween('tgl_transaksi', [$tgl_mulai, $tgl_selesai])->get();

                $summary = [
                    'total_pendapatan' => $pendapatan->sum('kredit') ?? 0,
                    'total_beban' => $beban->sum('debit') ?? 0,
                    'laba_bersih' => ($pendapatan->sum('kredit') ?? 0) - ($beban->sum('debit') ?? 0),
                ];
                break;

            // 9. LAPORAN NERACA
            case 'neraca':
                $data = Account::with('jurnals')->orderBy('kategori')->get();
                
                $aset = Account::where('kategori', 'Aset')->get()->sum(function ($acc) {
                    return $acc->saldo_awal + ($acc->jurnals->sum('debit') - $acc->jurnals->sum('kredit'));
                });

                $liabilitas = Account::where('kategori', 'Liabilitas')->get()->sum(function ($acc) {
                    return $acc->saldo_awal + ($acc->jurnals->sum('kredit') - $acc->jurnals->sum('debit'));
                });

                $summary = [
                    'total_aset' => $aset,
                    'total_liabilitas' => $liabilitas,
                    'total_ekuitas' => $aset - $liabilitas,
                ];
                break;
        }

        return view('kantor.akuntansi.pusat_laporan', compact('data', 'type', 'tgl_mulai', 'tgl_selesai', 'summary'));
    }

    /**
     * Export laporan ke Excel/PDF
     */
    public function export(Request $request)
    {
        // Implementasi export bisa dikembangkan dengan maatwebsite/excel
        $type = $request->get('type');
        
        return response()->json([
            'status' => 'success',
            'message' => 'Export fitur akan segera tersedia'
        ]);
    }
}
