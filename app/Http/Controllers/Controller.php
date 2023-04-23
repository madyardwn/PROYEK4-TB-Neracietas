<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *     description="Dokumentasi API untuk aplikasi mobile Himakom",
 *     version="1.0.0",
 *     title="API Docs"
 * )
 *
 * @OA\SecurityScheme(
 *    securityScheme="sanctum",
 *    type="http",
 *    scheme="bearer",
 *    name="Authorization Control",
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
