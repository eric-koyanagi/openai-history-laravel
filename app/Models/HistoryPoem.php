<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HistoryPoem extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function dataRun(): BelongsTo
    {
        return $this->belongsTo(DataRun::class);
    }
}
