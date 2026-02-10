<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Simpanan;
use App\Models\SimpananDetail;
use Illuminate\Http\Request;
use App\Models\SimpananTransaksi;
use App\Models\Transaksi_SP;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Services\AccountingService; // Import Service Akuntansi
use Illuminate\Container\Attributes\Auth;

class SimpananController extends Controller
{
    /**
     * Menampilkan daftar transaksi simpanan dan tarikan
     */
    public function index()
    {
        $members = Member::all();
        $simpanans = Transaksi_SP::with('member', 'simpananDetails')
            ->whereIn('COA', ['Simpan', 'Tarik'])
            ->orderBy('created_at', 'desc')
            ->get();

        // cek berkala status dan ubah menjadi aktif/nonaktif berdasarkan adanya simpanan di tiap 3 bulan
        foreach ($simpanans as $simpanan) {
            $hasSimpanan = SimpananDetail::where('no_transaksi_sp', $simpanan->no_transaksi_sp)->whereMonth('created_at', '>=', now()->subMonths(3))->exists();
            $simpanan->status = $hasSimpanan ? 'aktif' : 'nonaktif';
        }
        
        return view('simpanpinjam.simpanan', compact('simpanans', 'members'));
    }

    /**
     * Form tambah simpanan
     */
    public function create()
    {
        $members = Member::where('status', 'aktif')->get();
        // cek simpanan pokok dan wajib yang sudah ada
        $SimpanansPokok = SimpananDetail::where('jenis', 'POKOK') 
            ->whereHas('transaksiSP', function ($q) {
                $q->whereNotNull('member_id');
            })
            ->with('transaksiSP')
            ->get()
            ->map(function($item) {
                return [
                    'member_id' => $item->transaksiSP->member_id
                ];
            });
        // cek simpanan wajib yang sudah ada dibulan ini
        $SimpanansWajib = SimpananDetail::where('jenis', 'WAJIB') 
            ->whereHas('transaksiSP', function ($q) {
                $q->whereNotNull('member_id');
            })
            ->whereMonth('tanggal', date('m'))
            ->whereYear('tanggal', date('Y'))
            ->with('transaksiSP')
            ->get()
            ->map(function($item) {
                return [
                    'member_id' => $item->transaksiSP->member_id
                ];
            });
        return view('simpanpinjam.simpanan-create', compact('members', 'SimpanansPokok', 'SimpanansWajib'));
    }

    /**
     * Proses Simpan (Setoran Anggota) + Jurnal Otomatis
     */
    public function store(Request $request)
    {
        $namaMember = Member::where('id', $request->member_id)->first();

        if (!$namaMember) {
            return redirect()->back()->with('error', 'Member tidak ditemukan.');
        }

        
        $transaksi = DB::transaction(function () use ($request, $namaMember) {

            $administrasi = 0;  
            $nominal = (float)$request->nominal;
        
            if($request->jenis === 'Sukarela'){
                $administrasi = 2;
                $administrasi = ($nominal * $administrasi)/100;
            }

            // 1. Buat Transaksi Simpanan (Unit SP)
            $transaksi = Transaksi_SP::create([
                'no_transaksi_sp' => 'SP-S-' . date('YmdHis') . rand(1000, 9999)    ,
                'tanggal' => now(),
                'member_id' => $request->member_id,
                'nama' => $namaMember->nama_lengkap,
                'COA' => 'Simpan',
                'Debit/Credit' => 'Credit', // Uang masuk ke unit SP
                'Nominal' => $nominal,
                'Keterangan' => 'Simpanan ' . $request->jenis . ': ' . ($request->catatan ?? '-'),
            ]);
            
            // 2. Buat Detail Simpanan
            SimpananDetail::create([
                'no_transaksi_sp' => $transaksi->no_transaksi_sp,
                'tanggal' => now(),
                'saldo' => $nominal - $administrasi,
                'biaya_admin' => $administrasi,
                'jenis' => strtoupper($request->jenis), 
                'status' => 'aktif',
            ]);

            /** * 3. INTEGRASI AKUNTANSI KANTOR KOPERASI
             * Skema: Debit Kas (1101), Kredit Simpanan (2101)
             */
            
            // DEBIT: Kas Koperasi bertambah
            // AccountingService::post(
            //     now(),
            //     "Setoran Simpanan " . strtoupper($request->jenis) . " - " . $namaMember->nama_lengkap,
            //     $transaksi->no_transaksi_sp,
            //     (float)$request->nominal, 0,
            //     '1101'
            // );

            // KREDIT: Kewajiban Simpanan Anggota bertambah
            // AccountingService::post(
            //     now(),
            //     "Penerimaan Tabungan Anggota (" . $transaksi->no_transaksi_sp . ")",
            //     $transaksi->no_transaksi_sp,
            //     0, (float)$request->nominal,
            //     '2101'
            // );
            return $transaksi;
        });

        // Log Aktivitas
        writeLog(
            'Simpanan', 'Create', 'transaksi_s_ps',
            Transaksi_SP::latest()->first()->id ?? null,
            null, json_encode($request->all()),
            'Menambahkan simpanan & Jurnal otomatis untuk: ' . $namaMember->nama_lengkap,
            'info', 'success'
        );

        return redirect()->route('simpanan.strukSimpan', ['modul' => 'Simpanan', 'no_transaksi_sp' => $transaksi->no_transaksi_sp]);
    }

    /**
     * Proses Tarik (Penarikan Anggota) + Jurnal Otomatis
     */
    public function reduce(Request $request)
    {
        $request->validate([
            'member_id' => 'required|exists:members,id',
            'nominal' => 'required|numeric|min:1000',
        ]);

        $namaMember = Member::where('id', $request->member_id)->first();
        $nominal = (float)$request->nominal;
        $administrasi = ($nominal * 2)/100;

        if (!$namaMember) {
            return redirect()->back()->with('error', 'Member tidak ditemukan.');
        }

        $transaksi = DB::transaction(function () use ($request, $namaMember ,$nominal, $administrasi) {
            // 1. Buat Transaksi Penarikan (Unit SP)
            $transaksi = Transaksi_SP::create([
                'no_transaksi_sp' => 'SP-ST-' . date('YmdHis') . rand(1000, 9999),
                'tanggal' => now(),
                'member_id' => $request->member_id,
                'nama' => $namaMember->nama_lengkap,
                'COA' => 'Tarik',
                'Debit/Credit' => 'Debit', // Uang keluar dari unit SP
                'Nominal' => $nominal,
                'Keterangan' => 'Penarikan Simpanan Sukarela: ' . ($request->catatan ?? '-'),
            ]);
            

            // 2. Buat Detail Simpanan (Minus untuk mengurangi saldo)
            SimpananDetail::create([
                'no_transaksi_sp' => $transaksi->no_transaksi_sp,
                'tanggal' => now(),
                'saldo' => - $nominal + $administrasi, 
                'biaya_admin' => $administrasi,
                'jenis' => 'sukarela',
                'status' => 'aktif',
            ]);

            /** * 3. INTEGRASI AKUNTANSI KANTOR KOPERASI
             * Skema: Debit Simpanan (2101), Kredit Kas (1101)
             */

            // // DEBIT: Kewajiban Simpanan Berkurang
            // AccountingService::post(
            //     now(),
            //     "Penarikan Simpanan - " . $namaMember->nama_lengkap,
            //     $transaksi->no_transaksi_sp,
            //     (float)$request->nominal, 0,
            //     '2101'   
            // );

            // // KREDIT: Kas Koperasi Berkurang
            // AccountingService::post(
            //     now(),
            //     "Pengeluaran Kas Penarikan (" . $transaksi->no_transaksi_sp . ")",
            //     $transaksi->no_transaksi_sp,
            //     0, (float)$request->nominal,
            //     '1101'
            // );
            return $transaksi;
        }); 

        // Log Aktivitas
        writeLog(
            'Simpanan', 'Create', 'transaksi_s_ps',
            Transaksi_SP::latest()->first()->id ?? null,
            null, json_encode($request->all()),
            'Penarikan simpanan & Jurnal otomatis untuk: ' . $namaMember->nama_lengkap,
            'info', 'success'
        );
        
        return redirect()->route('simpanan.strukSimpan', ['modul' => 'Penarikan', 'no_transaksi_sp' => $transaksi->no_transaksi_sp]);
        // return redirect()->route('simpanan.index')->with('success', 'Penarikan berhasil & kas kantor diperbarui!');
    }

    // --- Fungsi Bawaan Lainnya (Dibiarkan Tetap) ---

    public function show($id)
    {
        if (request()->wantsJson() || request()->expectsJson()) {
            $member = Member::findOrFail($id);
            $total_simpanan_sukarela = SimpananDetail::whereHas('transaksiSP', function($q) use ($id) {
                $q->where('member_id', $id)->where('jenis','sukarela');
            })->sum('saldo');
            $total_simpanan_all = SimpananDetail::whereHas('transaksiSP', function($q) use ($id) {
                $q->where('member_id', $id);
            })->sum('saldo');
            $status = SimpananDetail::whereHas('transaksiSP', function($q) use ($id) {
                $q->where('member_id', $id);
            })->exists() ? 'aktif' : 'nonaktif';

            return response()->json([
                'member' => $member,
                'status' => $status,
                'total_simpanan_all' => $total_simpanan_all,
                'total_simpanan_sukarela' => $total_simpanan_sukarela,
            ]);
        }
    }

    public function edit($id)
    {
        $transaksi = Transaksi_SP::with(['member', 'simpananDetails'])
            ->where('id', $id)
            ->where('COA', 'Simpan')
            ->firstOrFail();
        
        $members = Member::all(); 
        return view('simpanpinjam.simpanan-edit', compact('transaksi', 'members'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'nominal' => 'required|numeric|min:1000',
            'jenis' => 'required|in:wajib,pokok,sukarela',
        ]);

        DB::transaction(function () use ($request, $id) {
            $transaksi = Transaksi_SP::where('id', $id)->where('COA', 'Simpan')->firstOrFail();
            $transaksi->update([
                'tanggal' => $request->tanggal,
                'Nominal' => (float)$request->nominal,
                'Keterangan' => $request->keterangan,
            ]);

            $detail = SimpananDetail::where('no_transaksi_sp', $transaksi->no_transaksi_sp)->firstOrFail();
            $detail->update([
                'tanggal' => $request->tanggal,
                'saldo' => (float)$request->nominal,
                'jenis' => $request->jenis,
                'status' => $request->status ?? $detail->status,
            ]);
        });

        return redirect()->route('simpanan.show', $id)->with('success', 'Data simpanan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $transaksi = Transaksi_SP::where('id', $id)->where('COA', 'Simpan')->firstOrFail();
        DB::transaction(function () use ($transaksi) {
            SimpananDetail::where('no_transaksi_sp', $transaksi->no_transaksi_sp)->delete();
            $transaksi->delete();
        });

        return redirect()->route('simpanan.index')->with('success', 'Data simpanan berhasil dihapus.');
    }

    public function createTarik()
    {
        $members = Member::where('status', 'aktif')->get(   );
        $saldoSimpanan = SimpananDetail::whereIn('jenis', ['sukarela'])
            ->whereHas('transaksiSP', function ($q) {
                $q->whereNotNull('member_id');
            })
            ->with('transaksiSP')
            ->get()
            ->map(function($item) {
                return [        
                    'member_id' => $item->transaksiSP->member_id,
                    'jenis_simpanan' => $item->jenis,
                    'saldo' => $item->saldo,
                ];
            });

        return view('simpanpinjam.TarikSimpanan', compact('members', 'saldoSimpanan'));
    }

    public function strukSimpan( $modul, $no_transaksi_sp) {
        $user = User::findOrFail(Auth()->User()->id);
        $transaksi = Transaksi_SP::with('member', 'simpananDetails')->where('no_transaksi_sp', $no_transaksi_sp)->first();
        return view('simpanpinjam.struk-simpanan', compact('transaksi','user', 'modul'));
    }
}