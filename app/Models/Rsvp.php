<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rsvp extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'event_id',
        'extras',
    ];

    protected function casts(): array
    {
        return [
            'extras' => 'integer',
        ];
    }

    /**
     * An RSVP belongs to one user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * An RSVP belongs to one event.
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}