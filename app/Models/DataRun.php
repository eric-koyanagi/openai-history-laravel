<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DataRun extends Model
{
    const HARD_DELAY = 100;

    use HasFactory;

    protected $guarded = [];

    public function histories(): HasMany
    {
        return $this->hasMany(History::class, 'run_id');
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
        $existing = self::where([["done", false], ["system_role_id", $roleId]])->with(['systemRole'])->orderBy("id","desc")->first();
        if (!$existing) 
        {
            return DataRun::create([
                'start_year' => env('HISTORY_START_YEAR'),
                'end_year' => env('HISTORY_END_YEAR'),
                'current_year' => env('HISTORY_START_YEAR'),
                'system_role_id' => $roleId
            ])->with(['systemRole'])->first();
        }

        return $existing;
    }

    protected function incrementMonth() 
    {
        $this->current_month++;

        // increment year
        if ($this->current_month >= 13) {
            $this->current_month = 1;
            $this->current_year++; 

            if ($this->current_year > $this->end_year) {
                $this->done = true;                                
            }
        }
    }
}
