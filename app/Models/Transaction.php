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
        'bowl_size',
        'spiciness_level',
        'total_price',
        'payment_proof',
        'status'
    ];

    protected $casts = [
        'status' => 'string', // atau enum jika menggunakan package enum
    ];

    public function setPaymentProofAttribute($value)
{
    $this->attributes['payment_proof'] = $value->store('payment_proofs');
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

    // Relasi ke detail transaksi
    public function details() {
        return $this->hasMany(TransactionDetail::class);
    }

    // Scope untuk filter status
    public function scopeFilter($query, $status) {
        return $query->where('status', $status);
    }
}