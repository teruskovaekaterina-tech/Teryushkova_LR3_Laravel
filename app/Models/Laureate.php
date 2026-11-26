<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class Laureate extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'birth_date',
        'citizenship',
        'award_year',
        'works',
        'image_path',
    ];

    protected function casts(): array
    {
        return [
            'works' => 'array',
            'award_year' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    protected static function booted(): void
    {
        static::updating(function (Laureate $laureate) {
            Gate::authorize('manage-laureate', $laureate);
        });

        static::deleting(function (Laureate $laureate) {
            Gate::authorize($laureate->isForceDeleting() ? 'force-delete-laureate' : 'manage-laureate', $laureate);
        });

        static::restoring(function (Laureate $laureate) {
            Gate::authorize('restore-laureate', $laureate);
        });

        static::forceDeleting(function (Laureate $laureate) {
            Gate::authorize('force-delete-laureate', $laureate);
        });

        static::forceDeleted(function (Laureate $laureate) {
            if ($laureate->image_path) {
                Storage::disk('public')->delete($laureate->image_path);
            }
        });
    }

    protected function birthDate(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? Carbon::parse($value) : null,
            set: fn ($value) => $value ? Carbon::parse($value)->format('Y-m-d') : null,
        );
    }
}
