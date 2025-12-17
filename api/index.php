<?php 
// Definir la ruta de storage en /tmp (Serverless)
define('LARAVEL_START', microtime(true));

die('A'); // ¿Muestra A?

require __DIR__.'/../vendor/autoload.php';

die('B'); // ¿Muestra B?

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| This script returns the application instance that is used to serve the
| incoming request from the client to this application. We'll start it
| up and load any necessary services and dependencies.
|
*/
$app = require_once __DIR__.'/../bootstrap/app.php';

die('C'); // ¿Muestra C?
?>