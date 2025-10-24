<?php

namespace App\Http\Controllers;
/**
 * @OA\Info(
 * version="1.0.0",
 * title="Documentación de la API de Nodos",
 * description="API para gestionar la estructura de nodos.",
 * @OA\Contact(
 * email="soporte@ejemplo.com"
 * ),
 * @OA\License(
 * name="Apache 2.0",
 * url="http://www.apache.org/licenses/LICENSE-2.0.html"
 * )
 * )
 * * @OA\Server(
 * url=L5_SWAGGER_CONST_HOST,
 * description="Servidor de la API"
 * )
 * )
 *
 * @OA\Components(
 * @OA\SecurityScheme(
 * securityScheme="csrf_token", 
 * type="apiKey",
 * in="header",
 * name="X-CSRF-TOKEN", 
 * description="Token CSRF requerido por Laravel para solicitudes POST-PUT-DELETE."
 * )
 * )
 */

abstract class Controller
{
    //
}
