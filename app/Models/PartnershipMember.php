<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PartnershipMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'partnership_id',
        'user_id',
        'employee_id',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
        ];
    }

    /**
     * A partnership member belongs to one partnership.
     */
    public function partnership(): BelongsTo
    {
        return $this->belongsTo(Partnership::class);
    }

    /**
     * A partnership member belongs to one user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
