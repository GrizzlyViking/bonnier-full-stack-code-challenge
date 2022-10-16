<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property \Illuminate\Support\Carbon $start
 * @property \Illuminate\Support\Carbon $end
 */
class Entry extends Model
{
    protected $fillable = [
        'start', 'end', 'project_id'
    ];

    protected $appends = ['humanReadable'];

    protected $dates = ['start', 'end'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function diffInSeconds()
    {
        if ($this->end->greaterThan($this->start)) {
            return $this->end->diffInSeconds($this->start);
        }
        return 0;
    }

    public function getHumanReadableAttribute()
    {
        if ($this->end->greaterThan($this->start)) {
            return $this->end->longAbsoluteDiffForHumans($this->start, 2);
        }

        return 0;
    }
}
