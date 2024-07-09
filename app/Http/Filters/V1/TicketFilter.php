<?php

namespace App\Http\Filters\V1;

use App\Models\Ticket;
use Illuminate\Contracts\Database\Eloquent\Builder;

class TicketFilter extends QueryFilter
{
    public function include(string $value): Builder
    {
        if (method_exists(new Ticket(), $value)) {
            $this->builder->with($value);
        }

        return $this->builder;
    }

    public function title(string $value): Builder
    {
        return $this->builder->where('title', 'like', str_replace('*', '%', $value));
    }

    public function description(string $value): Builder
    {
        return $this->builder->where('description', 'like', str_replace('*', '%', $value));
    }

    public function status(string $value): Builder
    {
        return $this->builder->whereIn('status', explode(',', $value));
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
