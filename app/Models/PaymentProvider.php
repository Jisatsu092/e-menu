<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PaymentProvider extends Model
{
    use HasFactory;

    protected $table = 'payment_providers';
    protected $fillable = [
        'name',
        'account_number',
        'account_name',
        'type',
        'instructions',
        'logo',
        'is_active'
    ];



    protected $casts = [
        'is_active' => 'boolean'
    ];
    
    public function getLogoUrlAttribute()
    {
        return $this->logo ? Storage::url($this->logo) : null;
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
