<!DOCTYPE html>
<html>
<head>
    <title>Struk #{{ $transaction->id }}</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        @media print {
            @page { 
                size: 80mm auto; /* Ukuran kertas thermal 80mm */
                margin: 2mm 4mm; /* Margin kiri-kanan lebih kecil */
                padding: 0;
            }
            body { 
                font-family: 'Courier New', monospace;
                width: 72mm !important; /* Lebar konten sesungguhnya */
                margin: 0 auto !important;
                padding: 0;
                font-size: 12px;
                line-height: 1.1;
                -webkit-print-color-adjust: exact;
            }
            * {
                color: #000 !important;
                background: transparent !important;
                text-shadow: none !important;
            }
            .no-print { 
                display: none !important; 
            }
            img {
                max-width: 68mm !important;
                height: auto !important;
            }
        }

        /* Style preview browser */
        @media screen {
            body {
                font-family: 'Courier New', monospace;
                width: 72mm;
                margin: 20px auto;
                padding: 10px;
                border: 1px dashed #ccc;
                font-size: 12px;
                line-height: 1.1;
            }
        }

        /* Layout utama */
        .header { 
            text-align: center; 
            padding: 2mm 0;
        }
        .divider {
            border-top: 1px solid #000;
            margin: 3mm 0;
        }
        .item-row {
            display: flex;
            justify-content: space-between;
            margin: 1.5mm 0;
        }
        .item-name {
            flex: 2;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .item-price {
            flex: 1;
            text-align: right;
            padding-left: 2mm;
        }
        
        /* Spesifik untuk thermal printer */
        .thermal-text {
            font-weight: bold;
            letter-spacing: -0.5px;
        }
        
        /* Optimasi gambar */
        .payment-proof img {
            border: 1px solid #ccc;
            padding: 1mm;
            margin: 2mm auto;
        }
    </style>
</head>
<body>
    <!-- Tombol Control -->
    <div class="no-print" style="text-align: center; padding: 5mm;">
        <button onclick="window.print()" style="margin: 2mm; padding: 3mm 5mm; background: #4CAF50; color: white; border: none; cursor: pointer;">
            🖨️ Cetak Ulang
        </button>
        <button onclick="window.close()" style="margin: 2mm; padding: 3mm 5mm; background: #f44336; color: white; border: none; cursor: pointer;">
            ❌ Tutup
        </button>
    </div>

    <!-- Konten Struk -->
    <div class="thermal-text">
        <div class="header">
            <h3 style="margin: 1mm 0; font-size: 14px;">WARUNG SEBLAK AJNIRA</h3>
            <p>Jl. Raya Seblak No. 123</p>
            <p>📞 (022) 1234-5678</p>
        </div>

        <div class="divider"></div>

        <!-- Info Transaksi -->
        <div class="transaction-info">
            <div class="item-row">
                <span>No. Struk:</span>
                <span>{{ $transaction->code }}</span>
            </div>
            <div class="item-row">
                <span>Tanggal:</span>
                <span>{{ $transaction->created_at->format('d/m/y H:i') }}</span>
            </div>
            <div class="item-row">
                <span>Kasir:</span>
                <span>{{ $transaction->user->name }}</span>
            </div>
            <div class="item-row">
                <span>Meja:</span>
                <span>{{ $transaction->table->number ?? '-' }}</span>
            </div>
        </div>

        <div class="divider"></div>

        <!-- Daftar Item -->
        <div class="items">
            @foreach($transaction->details as $detail)
            <div class="item-row">
                <div class="item-name">{{ $detail->toping->name }} ({{ $detail->quantity }}x)</div>
                <div class="item-price">Rp{{ number_format($detail->price * $detail->quantity, 0, ',', '.') }}</div>
            </div>
            @endforeach
        </div>

        <div class="divider"></div>

        <!-- Total -->
        <div class="total">
            <div class="item-row" style="font-weight: bold;">
                <span>TOTAL</span>
                <span>Rp{{ number_format($transaction->total_price, 0, ',', '.') }}</span>
            </div>
            <div class="item-row">
                <span>Status</span>
                <span>{{ ucfirst($transaction->status) }}</span>
            </div>
            <div class="item-row">
                <span>Pembayaran</span>
                <span>{{ $transaction->paymentProvider->name ?? 'TUNAI' }}</span>
            </div>
        </div>

        <!-- Bukti Bayar -->
        @if($transaction->payment_proof && Storage::exists($transaction->payment_proof))
        <div class="payment-proof">
            <div class="divider"></div>
            <p style="text-align: center; margin: 2mm 0;">BUKTI PEMBAYARAN</p>
            <img src="{{ asset('storage/' . $transaction->payment_proof) }}" 
                 alt="Bukti Bayar"
                 style="max-width: 100%; height: auto; max-height: 40mm;">
        </div>
        @endif

        <!-- Footer -->
        <div class="footer" style="margin-top: 4mm;">
            <div class="divider"></div>
            <p style="text-align: center; margin: 3mm 0;">💐 Terima kasih telah berkunjung</p>
            <p style="text-align: center; font-size: 10px;">www.seblak-ajnira.id</p>
        </div>
    </div>

    <script>
        // Auto print dengan timeout untuk menghindari popup blocker
        window.addEventListener('load', function() {
            @if(!app()->environment('local'))
            setTimeout(function() {
                window.print();
                setTimeout(function() {
                    window.close();
                }, 1000);
            }, 500);
            @endif
        });
    </script>
</body>
</html>