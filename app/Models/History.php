<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
 
class History extends Model
{
    use HasFactory;

    public function dataRun(): HasOne
    {
        return $this->hasOne(DataRun::class);
    }
}
