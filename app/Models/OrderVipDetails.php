<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderVipDetails extends Model
{
    use HasFactory;

    protected $table = 'order_vip_details';

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'unitcost',
        'total',
    ];

    protected $guarded = [
        'id',
    ];
    protected $with = ['product'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
