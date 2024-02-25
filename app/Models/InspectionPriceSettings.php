<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspectionPriceSettings extends Model
{
    use HasFactory;

    protected $table = 'inspection_price_settings';

    protected $fillable = [
        'inspection_fee',
        'commission',
        'property_type'
    ];
}
