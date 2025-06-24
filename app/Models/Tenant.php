<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use App\Enum\TenantStatus;

class Tenant extends Model implements Auditable
{
    use HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'name',
        'cpf',
        'document_id',
        'identity_document',
        'birth_date',
        'email',
        'phone',
        'monthly_income',
        'occupation',
        'marital_status',
        'additional_notes',
        'status',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'monthly_income' => 'decimal:2',
        'status' => TenantStatus::class,
    ];

    public function rentalAnalyses(): BelongsToMany
    {
        return $this->belongsToMany(RentalAnalysis::class)
            ->withTimestamps()
            ->using(RentalAnalysisTenant::class);
    }
}
