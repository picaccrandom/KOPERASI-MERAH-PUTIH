<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Struk - {{ $transaksi->kode_transaksi }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            width: 75mm;
            margin: 0;
            padding: 5px;
            font-size: 12px;
            color: #000;
        }

        .text-center {
            text-align: center;
        }

        .divider {
            border-top: 1px dashed #000;
            margin: 8px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .item-name {
            display: block;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 5px;
        }

        .item-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
        }

        .footer {
            margin-top: 15px;
            font-size: 10px;
        }

        .text-right {
            text-align: right;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body onload="window.print()">
    <div class="no-print" style="margin-bottom: 10px; text-align: center; background: #eee; padding: 5px;">
        <button onclick="window.print()" style="padding: 5px 15px; cursor: pointer;">CETAK</button>
        <button onclick="window.history.back()" style="padding: 5px 15px; cursor: pointer;">KEMBALI</button>
    </div>

    <div class="text-center">
        {{-- HEADER DINAMIS --}}
        @if ($transaksi->COA == 'Apotek')
            <h3 style="margin: 0;">APOTEK DESA NANGSRI</h3>
            <p style="margin: 0;">Layanan Obat & Alkes</p>
        @elseif($transaksi->COA == 'Klinik')
            <h3 style="margin: 0;">KLINIK PRATAMA NANGSRI</h3>
            <p style="margin: 0;">Pemeriksaan & Jasa Medis</p>
        @elseif($transaksi->COA == 'Gerai')
            <h3 style="margin: 0;">GERAI USAHA DESA</h3>
            <p style="margin: 0;">Sembako & Kebutuhan Warga</p>
        @else
            <h3 style="margin: 0;">BUMDES NANGSRI</h3>
            <p style="margin: 0;">Layanan Transaksi Umum</p>
        @endif
        <p style="font-size: 9px;">Desa Nangsri, Manisrenggo, Klaten</p>
    </div>

    <div class="divider"></div>

    {{-- INFO DATA TRANSAKSI --}}
    <table>
        <tr>
            <td width="30%">No</td>
            <td>: {{ $transaksi->kode_transaksi }}</td>
        </tr>
        <tr>
            <td>Tgl</td>
            <td>: {{ $transaksi->created_at->format('d/m/Y H:i') }}</td>
        </tr>
        <tr>
            <td>Plgn</td>
            <td>:
                @if ($transaksi->member)
                    {{ $transaksi->member->nama_lengkap }} (Member)
                @else
                    {{ $transaksi->nama ?? 'UMUM' }}
                @endif
            </td>
        </tr>
    </table>

    <div class="divider"></div>

    {{-- DETAIL BARANG / JASA --}}
    @if (count($details) > 0)
        @foreach ($details as $item)
            <span class="item-name">
                {{ $item->nama_obat ?? ($item->barang->nama_barang ?? 'Barang/Obat') }}
            </span>
            <div class="item-row">
                <span>{{ $item->qty }} x
                    {{ number_format($item->harga_satuan ?? $item->subtotal / $item->qty, 0, ',', '.') }}</span>
                <span>{{ number_format($item->subtotal, 0, ',', '.') }}</span>
            </div>
        @endforeach
    @else
        {{-- Jika Jasa Klinik (Menampilkan Keterangan sebagai item utama) --}}
        <div class="item-row" style="margin-top: 5px;">
            <span style="font-weight: bold; text-transform: uppercase; flex: 1;">
                {{ $transaksi->Keterangan ?? 'Layanan Medis Klinik' }}
            </span>
            <span class="text-right">
                {{ number_format($transaksi->Nominal ?? $transaksi->grand_total, 0, ',', '.') }}
            </span>
        </div>
    @endif

    <div class="divider"></div>

    {{-- KALKULASI TOTAL --}}
    <table style="font-weight: bold; font-size: 14px;">
        @if (isset($nominalDiskon) && $nominalDiskon > 0)
            <tr style="font-size: 11px;">
                <td>POTONGAN MEMBER</td>
                <td class="text-right">-{{ number_format($nominalDiskon, 0, ',', '.') }}</td>
            </tr>
        @endif
        <tr>
            <td>TOTAL BAYAR</td>
            <td class="text-right">Rp
                {{ number_format($transaksi->Nominal ?? $transaksi->grand_total, 0, ',', '.') }}</td>
        </tr>
    </table>

    {{-- INFO BAYAR / KEMBALIAN (Hanya jika ada data tunai) --}}
    @if (isset($transaksi->total_tunai) && $transaksi->total_tunai > 0)
        <div style="font-size: 11px; margin-top: 5px;">
            <div class="item-row">
                <span>Tunai</span>
                <span>{{ number_format($transaksi->total_tunai, 0, ',', '.') }}</span>
            </div>
            <div class="item-row">
                <span>Kembali</span>
                <span>{{ number_format($transaksi->total_tunai - ($transaksi->Nominal ?? $transaksi->grand_total), 0, ',', '.') }}</span>
            </div>
        </div>
    @endif

    {{-- STATUS BON dan SIMPANAN--}}
    @php
        $totalBon = $transaksi->total_bon ?? 0;
        $totalTunai = $transaksi->total_tunai ?? 0;
        $metode = $transaksi->tipe_pembayaran ?? ($transaksi->metode_bayar ?? '');
    @endphp

    @if ($metode == 'bon' || $totalBon > 0)
        <div class="text-center" style="margin-top: 10px; border: 1px dashed #000; padding: 5px;">
            <strong style="font-size: 11px;">TAGIHAN BON: Rp {{ number_format($totalBon, 0, ',', '.') }}</strong>
        </div>
    @endif
    @if ($metode == 'simpanan')
        <div class="text-center" style="margin-top: 10px; border: 1px dashed #000; padding: 5px;">
            <strong style="font-size: 11px;">PEMBAAYARAN VIA SIMPANAN: Rp {{ number_format($totalTunai ?? 0, 0, ',', '.') }}</strong>
        </div>
    @endif

    <div class="divider"></div>

    {{-- FOOTER DINAMIS --}}
    <div class="text-center footer">
        @if ($transaksi->COA == 'Apotek' || $transaksi->COA == 'Klinik')
            <p>SEMOGA LEKAS SEMBUH & SEHAT SELALU</p>
        @else
            <p>TERIMA KASIH ATAS KUNJUNGAN ANDA</p>
        @endif
        <p>*** LAYANAN DIGITAL DESA NANGSRI ***</p>
    </div>
</body>

</html>
