<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class RentalAnalysisTenant extends Pivot
{
    use HasFactory;

    protected $table = 'rental_analysis_tenant';

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function rentalAnalysis(): BelongsTo
    {
        return $this->belongsTo(RentalAnalysis::class);
    }
}
