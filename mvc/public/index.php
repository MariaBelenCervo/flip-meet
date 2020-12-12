<?php
/*Este archivo realizará el enrutamiento de todas las peticiones ajax
que realicen los servicios del front-end*/

use Core\App;

//Incluyo el autoload
require __DIR__ . '/../autoload.php';

//La variable $rootPath me va a devolver el directorio raíz de mi servidor (FlipMeet/mvc/)
$rootPath = realpath(__DIR__ . "/..");
//Reemplazo las \ por / en el rootPath
$rootPath = str_replace('\\', '/', $rootPath);

//Requiero las rutas de la aplicación
require $rootPath . '/api/routes.php';

//Instancio la clase App
//Para que calcule las rutas más fácilmente, le paso el $rootPath
$app = new App($rootPath);

//Finalmente, ejecuto la aplicación
$app->run();