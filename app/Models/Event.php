<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Database\Factories\EventFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Str;

/**
 * A run or event on the run club calendar. A run is a club run, sponsored or
 * not; an event can be anything else, like a tasting or a launch.
 *
 * The start is stored as two columns, event_date and event_time, because
 * other modules already read them. startsAt() joins them into one moment
 * for every rule that depends on time.
 *
 * @property int $id
 * @property string $title
 * @property string $type
 * @property CarbonImmutable $event_date
 * @property string $event_time
 * @property string $address
 * @property float|null $distance_km
 * @property string|null $pace
 * @property string|null $dress_code
 * @property string|null $sponsor
 * @property string $description
 * @property string $status
 * @property CarbonImmutable|null $cancelled_at
 * @property int $total_attendees
 */
class Event extends Model
{
    /** @use HasFactory<EventFactory> */
    use HasFactory;

    /**
     * New RSVPs close this many hours before the start, which gives the
     * organisers time to plan for the numbers (the eight-hour rule).
     */
    public const RSVP_CUTOFF_HOURS = 8;

    public const TYPE_RUN = 'run';

    public const TYPE_EVENT = 'event';

    public const STATUS_SCHEDULED = 'scheduled';

    public const STATUS_CANCELLED = 'cancelled';

    /**
     * Placeholder photo (in public/images/running) for runs without an upload.
     * Events don't get one, because a running photo would be wrong for, say,
     * a coffee tasting.
     */
    private const FALLBACK_PHOTOS = [
        'images/running/run-pink.jpg',
        'images/running/run-road.jpg',
        'images/running/crew.jpg',
    ];

    protected $fillable = [
        'title',
        'type',
        'event_date',
        'event_time',
        'address',
        'distance_km',
        'pace',
        'dress_code',
        'sponsor',
        'description',
    ];

    protected $attributes = [
        'type' => self::TYPE_RUN,
        'status' => self::STATUS_SCHEDULED,
        'description' => '',
        'total_attendees' => 0,
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'date',
            'distance_km' => 'float',
            'total_attendees' => 'integer',
            'cancelled_at' => 'datetime',
        ];
    }

    /**
     * Store times as HH:MM:SS whatever the input looked like ("07:00" from a
     * form, "07:00:00" from MySQL), so SQLite and MySQL hold the same value.
     *
     * @return Attribute<string, string>
     */
    protected function eventTime(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => CarbonImmutable::parse($value)->format('H:i:s'),
        );
    }

    /**
     * The description column is required in the database, but a run doesn't
     * need one, so a blank description is stored as an empty string.
     *
     * @return Attribute<string, string|null>
     */
    protected function description(): Attribute
    {
        return Attribute::make(
            set: fn (?string $value) => $value ?? '',
        );
    }

    /**
     * @return HasMany<Rsvp, $this>
     */
    public function rsvps(): HasMany
    {
        return $this->hasMany(Rsvp::class);
    }

    /**
     * RSVPs from members who are still going (cancelled ones are kept as history).
     *
     * @return HasMany<Rsvp, $this>
     */
    public function activeRsvps(): HasMany
    {
        return $this->rsvps()->where('status', Rsvp::STATUS_GOING);
    }

    /**
     * The photo an admin uploaded, stored in the shared polymorphic images table.
     *
     * @return MorphOne<Image, $this>
     */
    public function photo(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    /**
     * Runs and events that haven't started yet, including cancelled ones so
     * members can see that a run they planned for is off.
     *
     * The date and time are compared separately (whereDate / whereTime) because
     * Laravel translates those into the right SQL for both SQLite and MySQL.
     *
     * @param  Builder<Event>  $query
     */
    public function scopeUpcoming(Builder $query): void
    {
        $now = now();

        $query->where(function (Builder $query) use ($now) {
            $query->whereDate('event_date', '>', $now->toDateString())
                ->orWhere(function (Builder $query) use ($now) {
                    $query->whereDate('event_date', $now->toDateString())
                        ->whereTime('event_time', '>', $now->format('H:i:s'));
                });
        });
    }

    /**
     * Runs and events that have already started.
     *
     * @param  Builder<Event>  $query
     */
    public function scopePast(Builder $query): void
    {
        $now = now();

        $query->where(function (Builder $query) use ($now) {
            $query->whereDate('event_date', '<', $now->toDateString())
                ->orWhere(function (Builder $query) use ($now) {
                    $query->whereDate('event_date', $now->toDateString())
                        ->whereTime('event_time', '<=', $now->format('H:i:s'));
                });
        });
    }

    /**
     * @param  Builder<Event>  $query
     */
    public function scopeScheduled(Builder $query): void
    {
        $query->where('status', self::STATUS_SCHEDULED);
    }

    /**
     * @param  Builder<Event>  $query
     */
    public function scopeChronological(Builder $query): void
    {
        $query->orderBy('event_date')->orderBy('event_time');
    }

    public function startsAt(): CarbonImmutable
    {
        return CarbonImmutable::parse($this->event_date->format('Y-m-d').' '.$this->event_time);
    }

    public function rsvpClosesAt(): CarbonImmutable
    {
        return $this->startsAt()->subHours(self::RSVP_CUTOFF_HOURS);
    }

    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    public function hasStarted(): bool
    {
        return now()->greaterThanOrEqualTo($this->startsAt());
    }

    /**
     * Whether members can still join or add extra runners.
     */
    public function rsvpIsOpen(): bool
    {
        return ! $this->isCancelled() && now()->lessThan($this->rsvpClosesAt());
    }

    /**
     * Whether members can still cancel or bring fewer people. This stays open
     * after the cutoff until the start, because lower numbers never hurt the
     * organisers' planning.
     */
    public function rsvpCanBeReduced(): bool
    {
        return ! $this->isCancelled() && ! $this->hasStarted();
    }

    /**
     * Recount the expected headcount from scratch: one per member going plus
     * the extra runners they're bringing. Recounting (instead of adding or
     * subtracting one) means the number can't drift away from the RSVP rows.
     */
    public function refreshAttendance(): void
    {
        $members = $this->activeRsvps()->count();
        $extras = (int) $this->activeRsvps()->sum('extras');

        $this->forceFill(['total_attendees' => $members + $extras])->saveQuietly();
    }

    public function isRun(): bool
    {
        return $this->type === self::TYPE_RUN;
    }

    public function typeLabel(): string
    {
        return $this->isRun() ? 'Run' : 'Event';
    }

    /**
     * "run" or "event", for sentences like "This run has been cancelled".
     */
    public function noun(): string
    {
        return strtolower($this->typeLabel());
    }

    /**
     * What the people a member brings along are called: "extra runners" on a
     * run, "guests" at an event.
     */
    public function extrasNoun(int $count = 2): string
    {
        return Str::plural($this->isRun() ? 'extra runner' : 'guest', $count);
    }

    public function startTimeLabel(): string
    {
        return substr($this->event_time, 0, 5);
    }

    /**
     * "5 km", "21.1 km", or null when no distance was set.
     */
    public function distanceLabel(): ?string
    {
        if ($this->distance_km === null) {
            return null;
        }

        return rtrim(rtrim(number_format($this->distance_km, 2, '.', ''), '0'), '.').' km';
    }

    /**
     * The uploaded photo, the placeholder for a run without one, or null for an
     * event without one.
     */
    public function photoUrl(): ?string
    {
        if ($this->photo !== null) {
            return asset('storage/'.$this->photo->path);
        }

        if (! $this->isRun()) {
            return null;
        }

        return asset(self::FALLBACK_PHOTOS[$this->id % count(self::FALLBACK_PHOTOS)]);
    }

    public function mapUrl(): string
    {
        return 'https://www.google.com/maps/search/?api=1&query='.urlencode($this->address);
    }
}
