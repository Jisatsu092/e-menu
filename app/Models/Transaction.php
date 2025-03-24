<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'table_id',
        'total_price',
        'status'
    ];

    // Relasi ke tabel meja
    public function table()
    {
        return $this->belongsTo(Table::class);
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