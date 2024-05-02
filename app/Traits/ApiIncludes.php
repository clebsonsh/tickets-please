<?php

namespace App\Traits;

trait ApiIncludes
{
    public function include(string $relationship): bool
    {
        $param = request('include');

        if (!isset($param)) return false;

        $includeValuess = explode(',', strtolower($param));

        return in_array(strtolower($relationship), $includeValuess);
    }
}
