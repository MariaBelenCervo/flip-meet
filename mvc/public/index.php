<?php
/*Este archivo realizará el enrutamiento de todas las peticiones ajax
que realicen los servicios del front-end*/

use Core\App;
use Exceptions\HttpException;
use Exceptions\UnprocessableEntityHttpException;
use Responses\HttpErrorResponse;
use Responses\HttpResponse;


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

try {
    //Finalmente, ejecuto la aplicación
    $app->run();
} catch (UnprocessableEntityHttpException $e) {
    // Atajo excepciones especificamente de errores de validación
    $resp = new HttpErrorResponse($e);
    $resp->validationErrors = $e->getValidationErrors();
    $resp->send();
} catch (HttpException $e) { 
    // Atajo excepciones provenientes de http
    (new HttpErrorResponse($e))->send();
} catch (Exception $e) {
    // Atajo excepciones mas generales
    (new HttpResponse(500, "Internal Server Error", $e->getMessage()))->send();
}


