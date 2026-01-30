<!DOCTYPE html>
<html>
<head>
    <title>Struk</title>

    <style>
        @media print {
            body {
                width: 58mm;
                font-family: monospace;
                margin: 0;
            }
        }

        body {
            font-family: monospace;
            width: 58mm;
            font-size: 12px;
        }

        .center {
            text-align: center;
        }

        .line {
            border-top: 1px dashed #000;
            margin: 5px 0;
        }
    </style>
</head>
<body onload="window.print()">
<div class="center">
    <strong>KOPERASI MERAH PUTIH</strong><br>
    Jl. Contoh No. 1<br>
</div>

<div class="line"></div>

No: {{ $transaksi->first()->kode_transaksi }}<br>
Tanggal: {{ $transaksi->created_at->format('d/m/Y H:i') }}<br>
Kasir: {{ auth()->user()->name}}<br>

<div class="line"></div>

@foreach($transaksi->details as $item)
{{ $item->barang->nama }}<br>
{{ $item->qty }} x {{ number_format($item->harga) }}
    {{ number_format($item->subtotal) }}<br>
@endforeach

<div class="line"></div>

<strong>
TOTAL : Rp {{ number_format($transaksi->grand_total) }}
</strong>

<br><br>
<div class="center">
    Terima kasih 🙏
</div>

</body>
</html>
