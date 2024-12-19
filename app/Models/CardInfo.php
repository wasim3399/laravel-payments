<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'card_bin',
        'brand',
        'issuer',
        'type',
        'level',
        'iso_country',
        'country_card_issue',
        'iso_a3',
        'iso_number',
        'www',
        'phone',
        'extra1',
        'extra2',
        'extra3',
    ];
}
