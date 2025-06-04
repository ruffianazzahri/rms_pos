<?php

// app/Models/GeneralJournal.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneralJournal extends Model
{
    protected $table = 'general_journal';

    protected $fillable = [
        'date',
        'account',
        'debit',
        'credit',
        'description',
        'order_id',
        'image',
    ];
}
