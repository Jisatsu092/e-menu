<!DOCTYPE html>
<html>
<head>
    <title>Laporan Transaksi</title>
    <style>
        body { 
            font-family: Arial, sans-serif;
            margin: 0;
            position: relative;
        }
        
        /* Kop Surat */
        .letterhead {
            display: flex;
            align-items: center;
            padding: 20px;
            border-bottom: 3px solid #000;
            margin-bottom: 20px;
        }
        
        .logo {
            width: 80px;
            height: 80px;
            object-fit: contain;
            margin-right: 20px;
        }
        
        .company-info {
            flex: 1;
        }
        
        .company-info h1 {
            margin: 0 0 5px 0;
            font-size: 24px;
        }
        
        .company-info p {
            margin: 2px 0;
            font-size: 14px;
        }
        
        /* Tabel */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            page-break-inside: auto;
        }
        
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        
        th {
            background-color: #f2f2f2;
            position: sticky;
            top: 0;
        }
        
        /* Tanda Tangan */
        .signature {
            float: right;
            text-align: right;
            margin-top: 40px;
            width: 300px;
        }
        
        .signature-line {
            border-top: 1px solid #000;
            width: 200px;
            margin: 60px 0 5px auto;
        }
        
        /* Halaman */
        @media print {
            body {
                margin: 0;
                padding-top: 120px;
            }
            
            .letterhead {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                background: white;
                z-index: 999;
            }
            
            th {
                background-color: #f2f2f2 !important;
                -webkit-print-color-adjust: exact;
            }
            
            table { 
                page-break-inside: auto;
            }
            
            tr { 
                page-break-inside: avoid;
                page-break-after: auto;
            }
            
            .signature {
                position: fixed;
                bottom: 50px;
                right: 50px;
            }
        }
    </style>
</head>
<body>
    <!-- Kop Surat -->
    <div class="letterhead">
        <img src="{{ asset('images/logo.png') }}" alt="Logo Warung" class="logo">
        <div class="company-info">
            <h1>WARUNG SEBLAK AJNIRA</h1>
            <p>Jl. Raya Seblak No. 123, Kota Bandung</p>
            <p>Telp: (022) 1234-5678 | Email: ajnira@seblak.com</p>
        </div>
    </div>

    <!-- Isi Laporan -->
    <div class="container">
        <div class="header">
            <h1>LAPORAN TRANSAKSI</h1>
            <div class="date-range">
                Periode: {{ request('start') ? date('d/m/Y', strtotime(request('start'))) : '-' }} 
                s/d 
                {{ request('end') ? date('d/m/Y', strtotime(request('end'))) : '-' }}
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>User</th>
                    <th>Meja</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transactions as $transaction)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $transaction->user->name ?? '-' }}</td>
                        <td>{{ $transaction->table->number ?? '-' }}</td>
                        <td>Rp{{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                        <td>{{ ucfirst($transaction->status) }}</td>
                        <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="signature">
            {{-- <div class="signature-line"></div> --}}
            <p style="margin: 0">(__________________________)</p>
            <p style="margin: 0">Pimpinan Warung</p>
            <p style="margin: 0">Warung Seblak Ajnira</p>
            <p style="margin: 0">Tanggal: {{ date('d/m/Y') }}</p>
        </div>
    </div>
</body>
</html>