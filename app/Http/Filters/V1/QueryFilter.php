<?php

namespace App\Http\Filters\V1;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class QueryFilter
{
    protected Builder $builder;

    /** @var array<int|string, string> */
    protected array $sortable = [];

    public function __construct(
        protected Request $request,
    ) {
        //
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

    protected function sort(string $values): Builder
    {
        $sortAttributes = explode(',', $values);

        foreach ($sortAttributes as $sortAttribute) {
            $direction = 'asc';

            if (strpos($sortAttribute, '-') === 0) {
                $direction = 'desc';
                $sortAttribute = substr($sortAttribute, 1);
            }

            if (
                ! in_array($sortAttribute, $this->sortable)
                && ! array_key_exists($sortAttribute, $this->sortable)
            ) {
                continue;
            }

            $column = $this->sortable[$sortAttribute] ?? $sortAttribute;

            $this->builder->orderBy($column, $direction);
        }

        return $this->builder;
    }
}
