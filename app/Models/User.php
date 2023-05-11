<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    use HasFactory;

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $dates = [
        'updated_at',
        'created_at',
    ];

    protected $fillable = [
        'group_id',
        'teacher_id',
        'role_id',
        'first_name',
        'last_name',
        'email',
        'password',
        'created_at',
        'updated_at',
    ];

    public function getFullNameAttribute(): string
    {
        return "$this->first_name $this->last_name";
    }

    public function scopeWhereRole(Builder $query, ...$roles): Builder
    {
        return $query->whereHas('role', function ($q) use ($roles) {
            $q->whereIn('slug', $roles);
        });
    }

    public function adminlte_profile_url(): string
    {
        return route('admin.users.self-edit');
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function students(): HasMany
    {
        return $this->hasMany(User::class, 'teacher_id');
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class, 'teacher_id');
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [];
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new PasswordReset($token, $this));
    }
}
