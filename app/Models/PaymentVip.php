<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentVip extends Model
{

    protected $table = 'payments_vip';
    use HasFactory;

    protected $fillable = [
        'order_id',
        'method',
        'amount',
        'status',
        'paid_at',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    // Relasi: Payment milik satu Order
    public function order()
    {
        return $this->belongsTo(OrderVip::class);
    }
}
