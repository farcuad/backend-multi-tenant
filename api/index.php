<?php 
// Definir la ruta de storage en /tmp (Serverless)
$_SERVER['SCRIPT_FILENAME'] = __DIR__ . '/../public/index.php';
$_SERVER['SCRIPT_NAME'] = '/index.php';

// Sobreescribe la variable de entorno de Laravel para que use /tmp
putenv('LARAVEL_STORAGE_PATH=/tmp/storage');

// Ejecuta la aplicación
require __DIR__ . '/../public/index.php';
?>