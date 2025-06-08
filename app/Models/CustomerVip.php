<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Kyslik\ColumnSortable\Sortable;

class CustomerVip extends Model
{

    protected $table = 'customers_vip';

    protected $keyType = 'string';

    public $incrementing = false;

    use HasFactory, Sortable;

    protected $fillable = [
        'name',
        'phone',
        'address',
        'city',
        'uid',
        'balance' // ← added
    ];

    public $sortable = [
        'name',
        'phone',
        'city',
        'uid'
    ];

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? false, function ($query, $search) {
            return $query->where('name', 'like', '%' . $search . '%')->orWhere('shopname', 'like', '%' . $search . '%');
        });
    }

    public function getMaskedUidAttribute()
    {
        $uid = $this->attributes['uid'] ?? '';
        $visible = 2; // jumlah karakter yang ingin ditampilkan
        $length = strlen($uid);

        if ($length <= $visible) {
            return $uid;
        }

        $maskedPart = str_repeat('*', $length - $visible);
        return substr($uid, 0, $visible) . $maskedPart;
    }

    public function getMaskedPhoneAttribute()
    {
        $phone = $this->attributes['phone'] ?? '';
        $visible = 2; // jumlah karakter yang ingin ditampilkan
        $length = strlen($phone);

        if ($length <= $visible) {
            return $phone;
        }

        $maskedPart = str_repeat('*', $length - $visible);
        return substr($phone, 0, $visible) . $maskedPart;
    }
}
