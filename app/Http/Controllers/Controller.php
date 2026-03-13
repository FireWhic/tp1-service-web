<?php

namespace App\Http\Controllers;

define('OK', 200);
define('CREATED', 201);
define('NO_CONTENT', 204);
define('NOT_FOUND', 404);
define('INVALID_DATA', 422);
define('SERVER_ERROR', 500);
define("SONGS_PAGINATION", 5);

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'API Laravel pour la location d`équipements sportifs',
    description: 'Documentation API Laravel avec Swagger'
)]
abstract class Controller
{
    //
}
