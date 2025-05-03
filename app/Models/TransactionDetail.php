<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransactionDetail extends Model
{
    protected $fillable = [
        'transaction_id',
        'toping_id', 
        'quantity',
        'subtotal'
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function toping()
    {
        return $this->belongsTo(Toping::class);
    }

    public static function generateTransactionCode($customerName)
    {
        return DB::transaction(function () use ($customerName) {
            // Format tanggal dan waktu (14 digit)
            $datetime = Carbon::now()->format('YmdHis');
            
            // Format nama (3 karakter pertama tanpa spasi)
            $name = Str::upper(substr(preg_replace('/\s+/', '', $customerName), 0, 3));
            $name = str_pad($name, 3, 'X', STR_PAD_RIGHT);
            
            // Ambil counter terakhir untuk hari ini
            $lastTransaction = Transaction::whereDate('created_at', today())
                ->lockForUpdate()
                ->latest()
                ->first();

            $counter = $lastTransaction ? 
                intval(substr($lastTransaction->code, -4)) + 1 : 1;
            
            // Format counter 4 digit
            $counter = str_pad($counter, 4, '0', STR_PAD_LEFT);
            
            return "TRX-{$datetime}-{$name}-{$counter}";
        });
    }
}
