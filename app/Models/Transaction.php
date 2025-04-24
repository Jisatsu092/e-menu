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
        'total_price',
        'status',
        'payment_proof'
    ];

    protected $casts = [
        'status' => 'string', // atau enum jika menggunakan package enum
    ];

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