<!DOCTYPE html>
<html>
<head>
    <title>Struk #{{ $transaction->id }}</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        @media print {
            @page { 
                size: 80mm auto;
                margin: 2mm;
                margin-top: 10mm;
            }
            body { 
                font-family: 'Courier New', monospace;
                width: 72mm;
                padding: 5px;
                margin: 0 auto;
                font-size: 12px;
                line-height: 1.2;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .no-print { 
                display: none !important; 
            }
            * {
                color: #000 !important;
                background: transparent !important;
            }
            img {
                filter: grayscale(100%);
                max-width: 150px !important;
            }
        }
        .header { 
            text-align: center; 
            padding-bottom: 10px;
        }
        .divider { 
            border-top: 1px dashed #000; 
            margin: 10px 0;
        }
        .item { 
            display: flex; 
            justify-content: space-between;
            margin-bottom: 2px;
            font-size: 11px;
        }
        .payment-proof { 
            margin-top: 15px; 
            text-align: center; 
        }
        img { 
            max-width: 100%; 
            height: auto; 
            border: 1px solid #ddd;
        }
        .text-red-500 {
            color: #ef4444;
        }
    </style>
</head>
<body>
    <div class="no-print" style="text-align: center; padding: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer;">
            Cetak Ulang
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; background: #f44336; color: white; border: none; border-radius: 5px; cursor: pointer;">
            Tutup
        </button>
    </div>

    <div class="header">
        <h3>Warung Seblak Ajnira</h3>
        <p>Jl. Raya Seblak No. 123</p>
        <p>Telp: (022) 1234-5678</p>
    </div>

    <div class="divider"></div>

    <div class="transaction-info">
        <p>No. Struk: {{ $transaction->code }}</p>
        <p>Tanggal: {{ $transaction->created_at->format('d/m/Y H:i') }}</p>
        <p>Kasir: {{ $transaction->user->name }}</p>
        <p>Meja: {{ $transaction->table->number ?? 'N/A' }}</p>
    </div>

    <div class="divider"></div>

    <div class="items">
        @foreach($transaction->details as $detail)
        <div class="item">
            <span>{{ $detail->toping->name }} ({{ $detail->quantity }}x)</span>
            <span>Rp{{ number_format($detail->price * $detail->quantity, 0, ',', '.') }}</span>
        </div>
        @endforeach
    </div>

    <div class="divider"></div>

    <div class="total">
        <div class="item">
            <strong>Total:</strong>
            <strong>Rp{{ number_format($transaction->total_price, 0, ',', '.') }}</strong>
        </div>
        <p>Status: {{ ucfirst($transaction->status) }}</p>
        <p>Metode Bayar: {{ $transaction->paymentProvider->name ?? 'Tunai' }}</p>
    </div>

    @if($transaction->payment_proof && Storage::exists($transaction->payment_proof))
    <div class="payment-proof">
        <div class="divider"></div>
        <h4>Bukti Pembayaran</h4>
        <img src="{{ asset('storage/' . $transaction->payment_proof) }}" 
             alt="Bukti Pembayaran"
             style="max-width: 200px; max-height: 150px; margin: 10px auto;">
    </div>
    @elseif($transaction->payment_proof)
    <p class="text-red-500">Bukti pembayaran tidak tersedia</p>
    @endif

    <div class="footer">
        <div class="divider"></div>
        <p>Terima kasih telah berkunjung</p>
        <p>** Selera Pedas Nusantara **</p>
    </div>

    <script>
        // Auto print dan handling pop-up blocker
        window.addEventListener('load', () => {
            @if(!app()->environment('local'))
            const printAttempt = () => {
                try {
                    window.print();
                } catch (e) {
                    console.error('Print error:', e);
                }
            };
            
            if(window.opener === null) {
                printAttempt();
                const closeTimer = setInterval(() => {
                    if(document.readyState === 'complete') {
                        clearInterval(closeTimer);
                        setTimeout(() => {
                            window.close();
                        }, 1000);
                    }
                }, 100);
            }
            @endif
        });

        window.onafterprint = () => {
            if(window.opener === null) {
                setTimeout(() => {
                    window.close();
                }, 500);
            }
        };
    </script>
</body>
</html>