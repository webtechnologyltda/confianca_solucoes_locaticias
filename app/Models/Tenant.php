<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Tenant extends Model implements Auditable
{
    use HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'name',
        'document_id',
        'identity_document',
        'birth_date',
        'email',
        'phone',
        'monthly_income',
        'occupation',
        'marital_status',
        'additional_notes',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'monthly_income' => 'decimal:2',
    ];

    public function rentalAnalyses(): HasMany
    {
        return $this->hasMany(RentalAnalysis::class);
    }
}
