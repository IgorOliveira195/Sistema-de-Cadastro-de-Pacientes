<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'address_id',
        'name',
        'cpf',
        'cns',
        'birth_date',
        'gender',
        'phone',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
        ];
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (empty($search)) {
            return $query;
        }

        $digits = preg_replace('/\D/', '', $search);

        return $query->where(function (Builder $builder) use ($search, $digits) {
            $builder->where('name', 'like', "%{$search}%");

            if ($digits !== '') {
                $builder->orWhere('cpf', 'like', "%{$digits}%")
                    ->orWhere('cns', 'like', "%{$digits}%");
            }
        });
    }

    public function scopeByName(Builder $query, ?string $name): Builder
    {
        if (empty($name)) {
            return $query;
        }

        return $query->where('name', 'like', '%'.$name.'%');
    }

    public function scopeByCpf(Builder $query, ?string $cpf): Builder
    {
        if (empty($cpf)) {
            return $query;
        }

        return $query->where('cpf', preg_replace('/\D/', '', $cpf));
    }

    public function scopeByCns(Builder $query, ?string $cns): Builder
    {
        if (empty($cns)) {
            return $query;
        }

        return $query->where('cns', preg_replace('/\D/', '', $cns));
    }

    public function scopeByGender(Builder $query, ?string $gender): Builder
    {
        if (empty($gender)) {
            return $query;
        }

        return $query->where('gender', $gender);
    }

    public function scopeByAddress(Builder $query, ?int $addressId): Builder
    {
        if (empty($addressId)) {
            return $query;
        }

        return $query->where('address_id', $addressId);
    }
}
