<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_time',
        'event_date',
        'address',
        'description',
        'total_attendees',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'date',
            'total_attendees' => 'integer',
        ];
    }

        /**
     * An event can have many RSVPs.
     */
    public function rsvps(): HasMany
    {
        return $this->hasMany(Rsvp::class);
    }


}
