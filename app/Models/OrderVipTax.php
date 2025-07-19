<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderVipTax extends Model
{
    use HasFactory;

    protected $table = 'order_vip_taxes';

    protected $fillable = [
        'order_id',
        'tax_name',
        'tax_percent',
        'tax_amount',
        'created_at',
    ];

    public $timestamps = false; // karena hanya ada created_at (tidak ada updated_at)

    // Relasi ke order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
