<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

class Laureate extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'birth_date',
        'citizenship',
        'award_year',
        'works',
        'image_path'
    ];

    protected function casts(): array
    {
        return [
            'works' => 'array',
            'award_year' => 'integer',
        ];
    }

    protected function birthDate(): Attribute
    {
        return Attribute::make(
            get: fn ($value) =>
                $value ? Carbon::parse($value) : null,

            set: fn ($value) =>
                $value
                    ? Carbon::parse($value)->format('Y-m-d')
                    : null
        );
    }
}
