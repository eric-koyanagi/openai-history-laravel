<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DataRun extends Model
{
    const HARD_DELAY = 1000;

    use HasFactory;

    protected $guarded = [];

    public function histories(): HasMany
    {
        return $this->hasMany(DataRun::class);
    }

    public function systemRole(): BelongsTo
    {
        return $this->belongsTo(SystemRole::class);
    }

    public function next() 
    {
        $this->incrementMonth();
        $this->save();
        usleep(self::HARD_DELAY);
    }

    public static function getActiveRun($roleId) 
    {
        $existing = self::where([["done", false], ["system_id", $roleId]])->orderBy("id","desc")->first();
        if (!$existing) 
        {
            return DataRun::create([
                'start_year' => env('HISTORY_START_YEAR'),
                'end_year' => env('HISTORY_END_YEAR'),
                'current_year' => env('HISTORY_START_YEAR'),
                'system_id' => $roleId
            ]);
        }

        return $existing;
    }

    protected function incrementMonth() 
    {
        $this->currentMonth++;

        // increment year
        if ($this->currentMonth >= 13) {
            $this->currentMonth = 1;
            $this->currentYear++; 

            if ($this->currentYear > $this->endYear) {
                $this->done = true;                                
            }
        }
    }
}
