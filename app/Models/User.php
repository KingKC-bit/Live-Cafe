<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'surname',
        'email',
        'password',
        'role',
        'phone_number',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * A user can have many online orders.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * A user can process many physical POS sales.
     */
    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    /**
     * A user can create many RSVPs.
     */
    public function rsvps(): HasMany
    {
        return $this->hasMany(Rsvp::class);
    }

    /**
     * A user can belong to many partnerships.
     */
    public function partnershipMembers(): HasMany
    {
        return $this->hasMany(PartnershipMember::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isStaff(): bool
    {
        return $this->role === 'staff';
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    public function isStaffOrAdmin(): bool
    {
        return in_array($this->role, ['staff', 'admin'], true);
    }
}
