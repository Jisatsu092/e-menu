<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'table_id',
        'code',
        'bowl_size',
        'spiciness_level',
        'total_price',
        'payment_proof',
        'payment_provider_id',
        'status'
    ];

    protected $casts = [
        'status' => 'string', // atau enum jika menggunakan package enum
    ];

    public function getPaymentProofUrlAttribute()
    {
        return $this->payment_proof ? asset('storage/' . $this->payment_proof) : null;
    }

    // Relasi ke tabel meja
    public function table()
    {
        return $this->belongsTo(Table::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function paymentProvider()
    {
        return $this->belongsTo(PaymentProvider::class);
    }

    // Relasi ke detail transaksi
    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    // Scope untuk filter status
    public function scopeFilter($query, $status)
    {
        return $query->where('status', $status);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaction) {
            $transaction->code = TransactionDetail::generateTransactionCode(
                $transaction->customer_name
            );
        });
    }
    
}
