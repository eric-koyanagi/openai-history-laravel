<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DataRun extends Model
{
    use HasFactory;

    public function histories(): HasMany
    {
        return $this->hasMany(DataRun::class);
    }

    public function systemRole(): HasOne
    {
        return $this->hasOne(SystemRole::class);
    }
}
