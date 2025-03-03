<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = ['table_id', 'total_price', 'status'];

    public function table() {
        return $this->belongsTo(Table::class);
    }

    public function topings() {
        return $this->belongsToMany(Toping::class, 'transaction_toppings');
    }

    public function details() {
        return $this->hasMany(TransactionDetail::class);
    }
}
