<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DeathEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'deceased_name',
        'date_of_death',
        'description',
        'published_at',
        'status',
        'created_by',
    ];

    protected $casts = [
        'date_of_death' => 'date',
        'published_at' => 'datetime',
    ];

    public function contributions(): HasMany
    {
        return $this->hasMany(DeathContribution::class);
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }
}
