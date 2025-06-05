<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterCharge extends Model
{
    protected $table = 'master_charges';

    protected $fillable = [
        'name',
        'type',
        'percentage',
        'is_active',
    ];
}
