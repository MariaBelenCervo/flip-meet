<?php
namespace Core;

use Exception; /*nativa*/
use \Closure;


/**
 * Clase que se encargará de correr y manejar todo lo referente a la aplicación
 */
class App
{
	//private $rootPath;
	//private $appPath;
	//private static $urlPath;
	/**
   	 * @var string $rootPath - Ruta absoluta del directorio raíz del servidor
   	 * @var string $publicPath - Ruta absoluta del directorio /public del servidor
     * @var Request $request - Guardará cada nuevo objeto Request
     * @var string $controller - Contiene el nombre y método del controller a ejecutar
     * @var string $controllerName - Contiene el nombre del controller a ejecutar
     * @var string $controllerMethod - Contiene el método que deberá ejecutar el controller
     */
	private static $rootPath;
	private static $publicPath;
	private $request;
	private $controller;
	private $controllerName;
	private $controllerMethod;
	
	/**
	 * Constructor de App
	 * @param string $rootPath
	 */
	public function __construct(string $rootPath)
	{
		/* self::$rootPath   = $rootPath;
		self::$appPath    = $rootPath . "/app";
		self::$publicPath = $rootPath . "/www"; */
		self::$rootPath   = $rootPath;
		self::$publicPath = $rootPath . '/public/';

		//Esta concatenación va a obtener la url exacta a la que se dirigió el pedido ajax
		//Ejemplo: http://localhost/FlipMeet/mvc/public/login
		//$url = $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

		//Guardo esta url para ser recuperada luego por la clase Request
		//$this->urlPath = $url;

	}

	/**
	 * Inicia la aplicación (ejecuta los controladores)
	 */
	public function run()
	{
		// Instancio una nueva Request
		$this->request = new Request;
		// Obtengo su método, url y parámetros (parámetros del cuerpo de la request)
		$method = $this->request->method();
		$url = $this->request->url();
		$bodyParams = $this->request->bodyParams();

		// Checkeo si esos datos pertenecen a alguna de las rutas pre-definidas por Route
		if(Route::exists($method, $url)) {
			/* Si existe, obtengo el nombre del controller y su método (Controller@método)
			para ese verbo REST y esa url en particular */
			$controller = Route::getController($method, $url);
			// Separo el nombre del controlador del nombre del método y los guardo
			list($this->controllerName, $this->controllerMethod) = explode('@', $controller);

			/* Por otro lado, ahora que sé que la ruta existe, obtengo los parámetros que pudieran
			haber llegado en la url de la petición y los agrego al array $bodyParams*/
			$params = Request::parseParams($bodyParams, Route::getUrlParams());

			// Función a ejecutar del controller
			$controllerFunc = $this->controllerClosure($this->controllerName, $this->controllerMethod, $params);

			$middlewareClass = Route::getMiddleware($method, $url);

			// No hay middleware, ejecutar directamente el controller
			if ($middlewareClass === null) {
				$controllerFunc(null);
			} else {
				// Si hay un middleware, ejecutarlo pasándole el closure del controller como parámetro
				$mid = new $middlewareClass();
				$mid->handle($this->request, $controllerFunc);
			}
		} 
	}


	/**
     * Wrappea la acción del controller en un Closure (para pasárselo al middleware)
	 * @param string $controller
	 * @param string $method
	 * @param object $params
     * @return Closure
     */
    private function controllerClosure(string $controller, string $method, $params) : Closure
    {
        return function($data) use($controller, $method, $params) {
            $this->runController($controller, $method, $params, $data);
        };
    }

	/**
	 * Ejecuta el controller
	 * @param string $controller
	 * @param string $method
	 * @param object $params
	 * @param array $data
	 */
	private function runController(string $controller, string $method, $params, $data = null)
	{
		// Antes que nada, agrego el namespace del controller
		// Ej: \Controllers\LogController
		$controller = "\\Controllers\\" . $controller;
		// Instancio ese controller
		// Ej: $this->controller = new \Controllers\HomeController;
		$this->controller = new $controller;

		// Ejecuto el método con sus parámetros
		$this->controller->{$method}($params, $data);
	}


	//////////////////////////////GETTERS//////////////////////////////

	/**
	 * Obtiene la rita absoluta del directorio /public del servidor
	 * @return string
	 */
	public static function getPublicPath()
	{
		return self::$publicPath;
	}

	
}