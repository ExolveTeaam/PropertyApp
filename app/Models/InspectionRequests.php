<?php

namespace App\Models;

use App\Models\User;
use App\Models\Transactions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function user(){
    return $this->belongsTo(User::class, 'user_id');
    }

    public function transaction(){
        return $this->hasOne(Transactions::class,'transaction_reference');
    }

    public function inspector(){
        return $this->belonsTo(User::class,'inspector_id');
    }
}
