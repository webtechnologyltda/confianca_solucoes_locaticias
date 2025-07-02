<?php

namespace App\Models;

use App\Enum\AnalysisStatus;
use App\Enum\IndiceReantalAnalysis;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
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
        'real_estate_agent_id',
        'indice',
        'contract_number',
        'discount_month',
        'discount_year',
        'has_manual_discount',
        'contract_signature_date',
        'contract_renewal_date'
    ];

    protected $casts = [
        'analysis_date' => 'date',
        'analysis_document' => 'array',
        'credit_score' => 'integer',
        'status' => AnalysisStatus::class,
        'indice' => IndiceReantalAnalysis::class,
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

    public function rentalAnalysisTenants(): HasMany
    {
        return $this->hasMany(RentalAnalysisTenant::class);
    }

    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'documentable');
    }
}
