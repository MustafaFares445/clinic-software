<?php

namespace App\Http\Controllers;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="CLinic Software Api ",
 *     version="1.0.0",
 *     description="API Description"
 * )
 * @OA\SecurityScheme(
 *    securityScheme="bearerAuth",
 *    type="http",
 *    scheme="bearer"
 * )
 */
abstract class Controller
{
    //
}
