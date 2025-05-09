<!DOCTYPE html>
<html>

<head>
    <title>Laporan Detail Transaksi</title>
    <style>
        @page {
            size: A4;
            margin: 20mm;

            @bottom-center {
                content: "Halaman " counter(page);
                font-family: 'Times New Roman';
                font-size: 12px;
            }
        }

        body {
            font-family: 'Times New Roman', serif;
            line-height: 1.5;
            counter-reset: page;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        .title {
            font-size: 24px;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .transaction-header {
            background-color: #f5f5f5;
            font-weight: bold;
        }

        .signature-table {
            width: 100%;
            margin-top: 50px;
        }

        .signature-table td {
            border: none;
            padding: 20px;
            text-align: center;
            vertical-align: bottom;
        }

        .print-date {
            text-align: right;
            margin-bottom: 10px;
            font-size: 12px;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #666;
        }

        @media print {
            tr {
                page-break-inside: avoid;
            }
        }
    </style>
</head>

<body>
    <div class="print-date">
        Dicetak pada: {{ now()->format('d/m/Y H:i') }}
    </div>

    <div class="header">
        <div class="title">Laporan Detail Transaksi</div>
        <div>Resto Makan Enak</div>
        <div>Jl. Contoh No. 123, Kota Contoh</div>
    </div>

    <table>
        <thead>
            <tr>
                <th colspan="4" class="transaction-header">Detail Transaksi</th>
            </tr>
            <tr>
                <th style="border-top: 2px solid #000">Nama Toping</th> <!-- Garis atas tebal -->
                <th style="border-top: 2px solid #000">Harga</th>
                <th style="border-top: 2px solid #000">Qty</th>
                <th style="border-top: 2px solid #000">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $transactionId => $details)
                <tr class="transaction-header">
                    <td colspan="4" style="border-bottom: 2px solid #000"> <!-- Garis bawah tebal -->
                        Kode Transaksi: #{{ $details->first()->transaction->code }} |
                        Tanggal: {{ $details->first()->created_at->format('d/m/Y H:i') }} |
                        Jumlah Item: {{ $details->count() }}
                    </td>
                </tr>

                @foreach ($details as $detail)
                    <tr>
                        <td>{{ $detail->toping->name }}</td>
                        <td>Rp{{ number_format($detail->toping->price, 0, ',', '.') }}</td>
                        <td>{{ $detail->quantity }}</td>
                        <td>Rp{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach

                <tr>
                    <td colspan="4" style="border-bottom: 2px solid #000"></td> <!-- Garis pemisah transaksi -->
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Tanda Tangan -->
    <table class="signature-table">
        <tr>
            <td width="50%">
                Disetujui oleh:<br>
                <br><br><br>
                _________________________<br>
                Direktur Utama
            </td>
            <td width="50%">
                Diterima oleh:<br>
                <br><br><br>
                _________________________<br>
                Kasir
            </td>
        </tr>
    </table>

    <div class="footer">
        Laporan ini dicetak secara otomatis oleh sistem<br>
        &copy; {{ date('Y') }} Resto Makan Enak
    </div>
</body>
<script>
        // Auto print dan tutup window setelah cetak
        window.onload = function() {
            window.print();
            
            // Untuk browser tertentu yang support afterprint event
            window.onafterprint = function() {
                window.close();
            }
            
            // Fallback untuk tutup window setelah 1 detik
            setTimeout(function() {
                window.close();
            }, 1000);
        }
    </script>

</html>
