<?php

namespace App;

use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = ['name'];

    protected $appends = ['totalProjectTime'];

    public function entries()
    {
        return $this->hasMany(Entry::class);
    }

    public function getTotalProjectTimeAttribute()
    {
        $seconds = $this->entries()->get()->reduce(function ($carry, Entry $entry) {
            return $carry + $entry->diffInSeconds();
        }, 0);

        return CarbonInterval::seconds($seconds)->cascade()->forHumans();
    }
}
