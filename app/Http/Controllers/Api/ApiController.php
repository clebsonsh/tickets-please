<?php

namespace App\Http\Controllers\Api;

use App\Traits\ApiIncludes;
use App\Traits\ApiResponses;

abstract class ApiController
{
    use ApiIncludes, ApiResponses;
}
