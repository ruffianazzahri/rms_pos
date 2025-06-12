<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class OrderVip extends Model
{
    use HasFactory, Sortable;

    protected $table = 'orders_vip';

    protected $fillable = [
        'customer_id',
        'order_date',
        'order_status',
        'total_products',
        'sub_total',
        'vat',
        'invoice_no',
        'total',
        'payment_status',
        'pay',
        'due',
    ];

    public $sortable = [
        'customer_id',
        'order_date',
        'pay',
        'due',
        'total',
    ];

    protected $guarded = [
        'id',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function customerVip()
    {
        return $this->belongsTo(CustomerVip::class, 'customer_id');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetails::class);
    }
}
