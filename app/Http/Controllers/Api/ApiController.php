<?php

namespace App\Http\Controllers\Api;

use App\Traits\ApiResponses;
use Illuminate\Auth\Access\Response;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;

abstract class ApiController
{
    use ApiResponses;

    protected string $policyClass;

    public function include(string $relationship): bool
    {
        $param = request()->string('include');

        if ($param->isEmpty()) {
            return false;
        }

        $includeValues = explode(',', strtolower($param));

        return in_array(strtolower($relationship), $includeValues);
    }

    public function isAble(string $ability, Model $targetModel): Response
    {
        return Gate::authorize($ability, [$targetModel, $this->policyClass]);
    }
}
