<?php

namespace App\Http\Filters\V1;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class QueryFilter
{
    protected Builder $builder;

    public function __construct(
        protected Request $request,
    ) {
        //
    }

    /**
     * @param  array<string, string>  $filters
     */
    protected function filter(array $filters): Builder
    {
        foreach ($filters as $key => $value) {
            if (method_exists($this, $key) && $value) {
                $this->$key($value);
            }
        }

        return $this->builder;
    }

    public function apply(Builder $builder): Builder
    {
        $this->builder = $builder;

        foreach ($this->request->all() as $key => $value) {
            if (method_exists($this, $key) && ! is_null($value)) {
                $this->$key($value);
            }
        }

        return $this->builder;
    }
}
