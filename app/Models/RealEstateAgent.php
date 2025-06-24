<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RealEstateAgent extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'creci',
        'property_agency',
    ];

    public function rentalAnalysis()
    {
        return $this->hasMany(RentalAnalysis::class);
    }
}
