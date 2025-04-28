
<!DOCTYPE html>
<html>
<head>
    <title>Bukti Transaksi #{{ $transaction->id }}</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { width: 800px; margin: 0 auto; padding: 40px; }
        .header { text-align: center; margin-bottom: 30px; }
        .details { margin: 20px 0; }
        .details div { margin: 10px 0; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background-color: #f2f2f2; }
        .footer { text-align: center; margin-top: 40px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Bukti Transaksi #{{ $transaction->id }}</h2>
            <p>Tanggal: {{ $transaction->created_at->format('d/m/Y H:i') }}</p>
        </div>
        
        <div class="details">
            <div><strong>User:</strong> {{ $transaction->user->name ?? '-' }}</div>
            <div><strong>Meja:</strong> {{ $transaction->table->number ?? '-' }}</div>
            <div><strong>Total:</strong> Rp{{ number_format($transaction->total_price, 0, ',', '.') }}</div>
            <div><strong>Status:</strong> 
                <span class="{{ $transaction->status == 'paid' ? 'text-green-500' : 'text-yellow-500' }}">
                    {{ ucfirst($transaction->status) }}
                </span>
            </div>
        </div>
        
        <table>
            <tr>
                <th>Item</th>
                <th>Deskripsi</th>
                <th>Jumlah</th>
                <th>Harga</th>
            </tr>
            <tr>
                <td>Mie Pedas</td>
                <td>
                    Ukuran: {{ ucfirst($transaction->bowl_size) }}<br>
                    Level Pedas: {{ ucfirst($transaction->spiciness_level) }}
                </td>
                <td>1</td>
                <td>Rp{{ number_format($transaction->total_price, 0, ',', '.') }}</td>
            </tr>
        </table>
        
        <div class="footer">
            <p>Terima kasih atas transaksi Anda!</p>
            <p class="text-sm">Dicetak pada: {{ now()->format('d/m/Y H:i') }}</p>
        </div>
    </div>
</body>
</html>