<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspectionRequests extends Model
{
    use HasFactory;
    protected $table = 'inspectionrequests';
    protected $fillable = [
        'unit_name',
        'location',
        'property_type',
        'images',
        'is_occupied',
        'occupants_name',
        'occupants_contact',
        'transaction_reference',
        'first_date',
        'second_date'
    ];
}
