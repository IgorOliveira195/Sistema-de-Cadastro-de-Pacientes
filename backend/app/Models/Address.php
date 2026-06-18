<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'street',
        'zip_code',
        'neighborhood',
        'city',
        'state',
    ];

    public function patients(): HasMany
    {
        return $this->hasMany(Patient::class);
    }

    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (empty($search)) {
            return $query;
        }

        $digits = preg_replace('/\D/', '', $search);

        return $query->where(function (Builder $builder) use ($search, $digits) {
            $builder->where('street', 'like', "%{$search}%")
                ->orWhere('neighborhood', 'like', "%{$search}%")
                ->orWhere('city', 'like', "%{$search}%");

            if ($digits !== '') {
                $builder->orWhere('zip_code', 'like', "%{$digits}%");
            }
        });
    }

    public function scopeByCity(Builder $query, ?string $city): Builder
    {
        if (empty($city)) {
            return $query;
        }

        return $query->where('city', 'like', '%'.$city.'%');
    }

    public function scopeByState(Builder $query, ?string $state): Builder
    {
        if (empty($state)) {
            return $query;
        }

        return $query->where('state', strtoupper($state));
    }

    public function scopeByZipCode(Builder $query, ?string $zipCode): Builder
    {
        if (empty($zipCode)) {
            return $query;
        }

        return $query->where('zip_code', preg_replace('/\D/', '', $zipCode));
    }
}
