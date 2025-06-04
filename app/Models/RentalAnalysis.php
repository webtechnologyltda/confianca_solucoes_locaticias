<?php

namespace App\Models;

use App\Enum\AnalysisStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class RentalAnalysis extends Model implements Auditable
{
    use HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'property_id',
        'status',
        'credit_score',
        'tax',
        'other_tax',
        'house_rental_value',
        'observations',
        'analysis_document',
        'analysis_date',
        'analyst_id',
    ];

    protected $casts = [
        'analysis_date' => 'date',
        'analysis_document' => 'array',
        'credit_score' => 'integer',
        'status' => AnalysisStatus::class,
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function tenants(): BelongsToMany
    {
        return $this->belongsToMany(Tenant::class)
            ->withTimestamps()
            ->using(RentalAnalysisTenant::class);
    }

    public function analyst(): BelongsTo
    {
        return $this->belongsTo(User::class, 'analyst_id');
    }

    public function realEstateAgent(): BelongsTo
    {
        return $this->belongsTo(RealEstateAgent::class);
    }

    public function rentalAnalysisTenants() : HasMany
    {
        return $this->hasMany(RentalAnalysisTenant::class);
    }

}
