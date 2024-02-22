<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reports extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'summary',
        'property_name',
        'images',
        'property_type',
        'inspection_id',
        'door_accessing_property',
        'stairway',
        'door_hinges',
        'door_locks',
        'conduit_wiring',
        'plumbing_leakage',
        'flooring',
        'electrical',
        'kitchen_sink',
        'kitchen_slab',
        'paintings',
        'windows_nets',
        'ceiling_pop',
        'bathtubs',
        'rooms_bedrooms_cabinet',
        'overall',
        'input_criteria'
    ];
}
