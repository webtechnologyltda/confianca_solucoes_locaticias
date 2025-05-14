<?php

namespace App\Models;

use App\Enum\AnalysisStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class RentalAnalysis extends Model implements Auditable
{
    use HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'tenant_id',
        'property_id',
        'status',
        'credit_score',
        'observations',
        'analysis_document',
        'analysis_date',
        'analyst_id',
    ];

    protected $casts = [
        'analysis_date' => 'date',
        'credit_score' => 'decimal:2',
        'status' => AnalysisStatus::class,
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function analyst(): BelongsTo
    {
        return $this->belongsTo(User::class, 'analyst_id');
    }

    // Método de escopo para análises pendentes
    public function scopePending($query)
    {
        return $query->where('status', AnalysisStatus::PENDING);
    }
}
