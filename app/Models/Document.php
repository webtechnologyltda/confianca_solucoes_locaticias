<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'name',
        'path',
        'documentable_type',
    ];

    protected $casts = [
        'path' => 'array',
    ];

    public function documentable()
    {
        return $this->morphTo();
    }
}
