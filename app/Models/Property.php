<?php

namespace App\Models;

use App\Enum\RentalStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Property extends Model implements Auditable
{
    use HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'title',
        'street_address',
        'number',
        'complement',
        'neighborhood',
        'city',
        'state',
        'zip_code',
        'rental_price',
        'property_tax',
        'condo_fee',
        'total_area',
        'bedrooms',
        'bathrooms',
        'parking_spaces',
        'status',
        'description',
        'features',
    ];

    protected $casts = [
        'rental_price' => 'decimal:2',
        'property_tax' => 'decimal:2',
        'condo_fee' => 'decimal:2',
        'total_area' => 'decimal:2',
        'features' => 'array',
        'status' => RentalStatus::class,
    ];

    protected function available($query)
    {
        return $query->where('status', RentalStatus::AVAILABLE);
    }
}
