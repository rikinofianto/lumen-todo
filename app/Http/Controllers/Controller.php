<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

/**
 * @OA\Info(
 * title="Todos API",
 * version="1.0.0",
 * description="Dokumentasi API Todos",
 * @OA\Contact(
 * email="rikinofianto@gmail.com"
 * )
 * ),
 * * @OA\SecurityScheme(
 * securityScheme="bearerAuth",
 * type="http",
 * scheme="bearer",
 * bearerFormat="JWT",
 * description="Masukkan token JWT Anda di sini (tanpa 'Bearer ')"
 * )
 */
class Controller extends BaseController
{
    //
}
