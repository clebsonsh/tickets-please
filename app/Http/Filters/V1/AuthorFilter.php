<?php

namespace App\Http\Filters\V1;

use App\Models\User;
use Illuminate\Contracts\Database\Eloquent\Builder;

class AuthorFilter extends QueryFilter
{
    protected array $sortable = [
        'name',
        'email',
        'createdAt' => 'created_at',
        'updatedAt' => 'updated_at',
    ];

    public function include(string $value): Builder
    {
        if (method_exists(new User(), $value)) {
            $this->builder->with($value);
        }

        return $this->builder;
    }

    public function id(string $value): Builder
    {
        return $this->builder->whereIn('id', explode(',', $value));
    }

    public function name(string $value): Builder
    {
        return $this->builder->where('name', 'like', str_replace('*', '%', $value));
    }

    public function email(string $value): Builder
    {
        return $this->builder->where('email', 'like', str_replace('*', '%', $value));
    }

    public function createdAt(string $value): Builder
    {
        $dates = explode(',', $value);

        if (count($dates) > 1) {
            return $this->builder->whereBetween('created_at', $dates);
        }

        return $this->builder->whereDate('created_at', $value);
    }

    public function updatedAt(string $value): Builder
    {
        $dates = explode(',', $value);

        if (count($dates) > 1) {
            return $this->builder->whereBetween('updated_at', $dates);
        }

        return $this->builder->whereDate('updated_at', $value);
    }
}
