<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SystemRole extends Model
{
    use HasFactory;

    public function dataRun(): BelongsTo
    {
        return $this->belongsTo(DataRun::class);
    }
}
