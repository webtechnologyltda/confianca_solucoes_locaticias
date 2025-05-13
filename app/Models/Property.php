<?php

namespace App\Models;

use App\Enum\RentalStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Property extends Model
{
    use HasFactory, SoftDeletes;

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

    public function rentalAnalyses(): HasMany
    {
        return $this->hasMany(RentalAnalysis::class);
    }

    // Método de escopo para propriedades disponíveis
    public function scopeAvailable($query)
    {
        return $query->where('status', RentalStatus::AVAILABLE);
    }
}
