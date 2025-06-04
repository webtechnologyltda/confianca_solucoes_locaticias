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
        'street_address',
        'number',
        'complement',
        'city',
        'neighborhood',
        'state',
        'zip_code',
        'status',
        'description',
        'type',
        'owner_name',
        'owner_phone',
        'owner_email',
    ];

    protected $casts = [
        'status' => RentalStatus::class,
    ];

    protected function available($query)
    {
        return $query->where('status', RentalStatus::AVAILABLE);
    }
}
