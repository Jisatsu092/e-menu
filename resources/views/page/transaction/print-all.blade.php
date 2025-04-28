
<!DOCTYPE html>
<html>
<head>
    <title>Laporan Transaksi</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { width: 100%; max-width: 1200px; margin: 0 auto; }
        .header { text-align: center; margin: 20px 0; }
        .header h1 { font-size: 24px; color: #2c3e50; }
        .date-range { margin: 10px 0; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background-color: #f2f2f2; }
        .footer { text-align: center; margin-top: 40px; }
    </style>
</head>
<body>
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
                        <td>
                            <span class="{{ $transaction->status == 'paid' ? 'text-green-500' : 'text-yellow-500' }}">
                                {{ ucfirst($transaction->status) }}
                            </span>
                        </td>
                        <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="footer">
            <p>Dicetak pada: {{ now()->format('d/m/Y H:i') }}</p>
        </div>
    </div>
</body>
</html>