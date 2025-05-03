<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Toping extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'category_id', 'price', 'stock', 'image'];

    public function category() {
        return $this->belongsTo(Category::class);
    }
    public function transactions() {
        return $this->belongsToMany (Transaction::class, 'transaction_topings') ->withPivot('quantity');
        
    }
    

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}
