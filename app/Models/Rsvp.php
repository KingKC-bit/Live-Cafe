<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Database\Factories\RsvpFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * A member's RSVP for a run or event. Extra runners are a number only;
 * members don't have to name the people they bring.
 *
 * @property int $id
 * @property int $user_id
 * @property int $event_id
 * @property int $extras
 * @property string $status
 * @property CarbonImmutable|null $cancelled_at
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 */
class Rsvp extends Model
{
    /** @use HasFactory<RsvpFactory> */
    use HasFactory;

    public const STATUS_GOING = 'going';

    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'user_id',
        'event_id',
        'extras',
    ];

    protected $attributes = [
        'extras' => 0,
        'status' => self::STATUS_GOING,
    ];

    protected function casts(): array
    {
        return [
            'extras' => 'integer',
            'cancelled_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<Event, $this>
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * @param  Builder<Rsvp>  $query
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('status', self::STATUS_GOING);
    }

    public function isGoing(): bool
    {
        return $this->status === self::STATUS_GOING;
    }

    /**
     * The member plus the extra runners they're bringing.
     */
    public function headcount(): int
    {
        return 1 + $this->extras;
    }
}
