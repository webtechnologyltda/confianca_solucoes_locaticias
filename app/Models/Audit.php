<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Audit extends \OwenIt\Auditing\Models\Audit
{
    public function user() : MorphTo
    {
        return $this->morphTo();
    }
}
