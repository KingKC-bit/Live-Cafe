<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Database\Factories\AnnouncementFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * News from Live Cafe. It can be about anything, like a new flavour or a
 * route change, and can point at a run or event so members can open it and
 * RSVP. The body lives in the existing description column. An empty
 * published_at means the announcement is a draft.
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $event_id
 * @property string $title
 * @property string $description
 * @property bool $is_pinned
 * @property CarbonImmutable|null $published_at
 * @property CarbonImmutable|null $created_at
 */
class Announcement extends Model
{
    /** @use HasFactory<AnnouncementFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'is_pinned',
        'event_id',
    ];

    protected $attributes = [
        'is_pinned' => false,
    ];

    protected function casts(): array
    {
        return [
            'is_pinned' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    /**
     * The admin who wrote it.
     *
     * @return BelongsTo<User, $this>
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * The run or event it's about, if any.
     *
     * @return BelongsTo<Event, $this>
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * @param  Builder<Announcement>  $query
     */
    public function scopePublished(Builder $query): void
    {
        $query->whereNotNull('published_at')->where('published_at', '<=', now());
    }

    /**
     * Pinned announcements first, then the newest.
     *
     * @param  Builder<Announcement>  $query
     */
    public function scopeForDisplay(Builder $query): void
    {
        $query->orderByDesc('is_pinned')
            ->orderByDesc('published_at')
            ->orderByDesc('id');
    }

    public function isPublished(): bool
    {
        return $this->published_at !== null && $this->published_at->lessThanOrEqualTo(now());
    }
}
