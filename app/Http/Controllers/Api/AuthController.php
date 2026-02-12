<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\SimpananDetail;
use App\Models\KreditAnggota;
use App\Models\Transaksi_SP;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nik' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $member = Member::where('nik', $request->nik)->first();

        if (!$member) {
            return response()->json([
                'success' => false,
                'message' => 'NIK tidak terdaftar sebagai Anggota.'
            ], 404);
        }

        if (empty($member->password)) {
            $member->password = Hash::make($request->password);
            $member->save();
        }

        if (!Hash::check($request->password, $member->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password salah!'
            ], 401);
        }

        $token = $member->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login Berhasil!',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => [
                'nama' => $member->nama_lengkap,
                'nik' => $member->nik,
                'alamat' => $member->alamat
            ]
        ]);
    }

    public function profile(Request $request)
    {
        $member = $request->user();

        $totalCredit = SimpananDetail::whereHas('transaksi', function($query) use ($member) {
            $query->where('member_id', $member->id)
                ->where('Debit/Credit', 'Credit'); 
        })->sum('saldo'); 

        $totalDebit = SimpananDetail::whereHas('transaksi', function($query) use ($member) {
            $query->where('member_id', $member->id)
                ->where('Debit/Credit', 'Debit');
        })->sum('saldo');

        $saldoSimpanan = $totalCredit - $totalDebit;


        $semuaPinjaman = Transaksi_SP::where('member_id', $member->id)
                                    ->where('COA', '1101')
                                    ->get();

        $totalPlafon = $semuaPinjaman->sum('Nominal');
        
        $totalAngsuranLunas = 0;
        foreach ($semuaPinjaman as $pinjam) {
            $totalAngsuranLunas += $pinjam->angsuranPeminjamans()
                                        ->where('status', 'lunas')
                                        ->sum('jumlah_angsuran');
        }

        $sisaHutang = $totalPlafon - $totalAngsuranLunas;

        $limitBonData = KreditAnggota::where('member_id', $member->id)->first();
        $limitBonValue = $limitBonData ? $limitBonData->Nominal_Limit : 0;

        return response()->json([
            'success' => true,
            'message' => 'Data Profile & Keuangan Member Berhasil Diambil',
            'data' => [
                'user_info' => [
                    'id'             => $member->id,
                    'nama'           => $member->nama_lengkap,
                    'nik'            => $member->nik,
                    'email'          => $member->email,
                    'status_anggota' => $member->status,
                ],
                'keuangan' => [
                    'total_simpanan' => (int) $saldoSimpanan,
                    'total_pinjaman' => (int) $sisaHutang, 
                    'limit_bon'      => (int) $limitBonValue,
                    'mata_uang'      => 'IDR'
                ]
            ]
        ], 200);
    }

    public function history(Request $request)
    {
        $member = $request->user();
        $riwayat = Transaksi_SP::where('member_id', $member->id)
                    ->orderBy('tanggal', 'desc')
                    ->paginate(10);

        return response()->json([
            'success' => true,
            'message' => 'Riwayat Transaksi Member',
            'data'    => $riwayat->map(function($item) {
                return [
                    'no_transaksi' => $item->no_transaksi_sp,
                    'tanggal'      => $item->tanggal,
                    'keterangan'   => $item->Keterangan, // Cek apakah K-nya kapital di DB
                    'nominal'      => (int) $item->Nominal, // Cek apakah N-nya kapital di DB
                    'tipe'         => $item->{'Debit/Credit'},
                    'coa'          => $item->COA
                ];
            })
        ]);
    }


    public function loanSummary(Request $request)
    {
        $member = $request->user();

        $pinjaman = Transaksi_SP::where('member_id', $member->id)
                    ->where('COA', '1101') 
                    ->get();

        $totalPlafon = $pinjaman->sum('Nominal');
        
        $totalDibayar = 0;
        foreach ($pinjaman as $p) {
            $totalDibayar += $p->angsuranPeminjamans()
                            ->where('status', 'lunas')
                            ->sum('jumlah_angsuran'); 
        }

        $sisaHutang = $totalPlafon - $totalDibayar;

        return response()->json([
            'success' => true,
            'message' => 'Detail Pinjaman Member',
            'data' => [
                'ringkasan' => [
                    'total_pinjaman_awal' => (int) $totalPlafon,
                    'total_telah_dibayar' => (int) $totalDibayar,
                    'sisa_hutang'         => (int) $sisaHutang,
                ],
                'daftar_pinjaman' => $pinjaman->map(function($item) {
                    $terbayar = $item->angsuranPeminjamans()->where('status', 'lunas')->sum('jumlah_angsuran');
                    
                    return [
                        'no_transaksi' => $item->no_transaksi_sp,
                        'tanggal'      => $item->tanggal,
                        'nominal_awal' => (int) $item->Nominal,
                        'telah_dibayar'=> (int) $terbayar,
                        'sisa_saldo'   => (int) ($item->Nominal - $terbayar),
                        'keterangan'   => $item->Keterangan
                    ];
                })
            ]
        ]);
    }
}