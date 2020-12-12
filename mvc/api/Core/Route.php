<?php
namespace Core;

/**
 * Clase que se encargará de la administración de las rutas
 */
class Route
{
	/** @var array $routes
	 * Array que almacenará los posibles verbos de las peticiones con sus posibles
	 * urls, controller y métodos
	 */
	private static $routes = [
		'GET'    => [
			/*
			url => controller
			Ej:
			'users' => 'UsersController@add'
			*/
		],
		'POST'   => [],
		'PUT' 	 => [],
		'DELETE' => []
	];

	/** @var array $urlParams - Los parámetros de la url recibida, en caso de haberlos */
	private static $urlParams = [];

	/** @var string $controllerAction - El nombre y método del controller que corresponde a la ruta */
	private static $controllerAction;

	/**
	 * Constructor privado para asegurar el Singleton
	 */
	// Esto evita que se puedan crear instancias de esta clase fuera de la misma
	private function __construct() 
	{}

	/**
	 * Agrega una ruta al array de rutas de la app
	 * @param string $method
	 * @param string $url
	 * @param string $controller - Con el formato "NombreController@nombreMétodo".
	 */
	public static function add(string $method, string $url, string $controller)
	{
		$method = strtoupper($method);
		// Guardo el controlador y su método en la clave $url de la clave $method del array $routes
		self::$routes[$method][$url] = $controller;
	}

	/**
	 * Verifica si la ruta para ese verbo existe
	 * @param string $method
	 * @param string $url
	 * @return bool
	 */
	public static function exists($method, $url)
	{
		// Si la url es del tipo "endpoint" (ej: login, users, etc)...
		if (isset(self::$routes[$method][$url])) {
			return true;

		// Si no lo es, puede ser que se trate de una url con parámetros...
		} else if (self::paramedRouteExists($method, $url)) {
			return true;

		// Si no es ninguna de los dos tipos de ruta, significa que no existe
		} else {
			return false;
		}
	}

	/**
	 * Informa si existe una ruta en $routes que coincida con la url de la petición,
	 * teniendo en cuenta los parámetros ({valor})
	 * @param string $method
	 * @param string $url
	 * @return bool
	 */
	public static function paramedRouteExists($method, $url)
	{
		// Separo la url recibida en la petición en sus distintas partes
		$urlParts = explode('/', $url);

		// Por otro lado, dentro de la propiedad $route puede haber más de una url para el mismo método
		// Busco entonces todas las url disponibles para el método recibido
		$routesToCheck = self::$routes[$method];

		// Recorro una por una todas esas posibles rutas
		foreach ($routesToCheck as $route => $controllerAction) {
			// Separo la url de la ruta actual en sus distintas partes
			$routeParts = explode('/', $route);

			/* Como una primera verificación, cuento la cantidad de "niveles" de la url de la ruta actual.
			Esos "niveles" pueden corresponder a endpoints (sólo texto) o a parámetros (ej: {id}) */
			if (count($urlParts) == count($routeParts)) {
				// Ahora necesito saber si esas partes coinciden con las de la url de la ruta actual
				if(self::compareParts($urlParts, $routeParts)) {
					// La ruta existe, así que almaceno el nombre del controller
					self::$controllerAction = $controllerAction;

					return true;
				}
			}
		}

		// Si llegué hasta acá es porque todas las rutas fueron recorridas y ninguna retornó true
		return false;
	}

	/**
	 * Compara si las partes de la ruta especificada de $routes
	 * coinciden con las partes de la url recibida en la petición
	 * @param array $urlParts
	 * @param array $routeParts
	 * @return bool
	 */
	public static function compareParts($urlParts, $routeParts)
	{
		// Ejemplo de $urlParts: ['', 'users', '1']
		// Ejemplo de un $routeParts: ['', 'users', '{id}']
		// Ejemplo de otro $routeParts: ['', 'posts', '{id}']

		/* Para verificar si los elementos de $urlParts coinciden con los de $routeParts,
		recorro cada uno de esos elementos y los voy comparando */
		foreach($routeParts as $key => $value) {
			// Si el valor de $routeParts no coincide con el valor de $urlParts en ese mismo key...
			if($value != $urlParts[$key]) {
				/*
				Puede pasar por 2 razones:
					1) Son dos endpoints distintos (ej: 'users' y 'posts')
					2) El value es un parámetro (en $routeParts aparece el nombre de la key con llaves
					y en $urlParts aparece directamente el valor)
				*/
				// Si el valor de $routeParts empieza con "{"...
				if(strpos($value, '{') === 0) {
					// ...entonces se trata de un parámetro
					// Guardo el parámetro encontrado en la propiedad $urlParams
					// Para eso necesito el nombre del parámetro (le quito las {})
					$paramName = substr($value, 1, -1);
					/* Y ahora guardo el valor de ese parámetro que se encuentra
					en la misma key del $urlParts */
					self::$urlParams[$paramName] = $urlParts[$key];
				} else {
					/* Si el elemento de $routeParts que no coincide con el valor de $urlParts
					en ese mismo key NO es un parámetro (ej: $routePart = 'users'; $urlPart = 'login';),
					la ruta ya no es válida */
					return false;
				}
			}
		}

		// Si llegué hasta acá, es porque esta ruta coincide con la url solicitada
		return true;
	}

	//////////////////////////////GETTERS//////////////////////////////
	
	/**
	 * Retorna el controller que se está pidiendo (en formato "Controller@método")
	 * @param string $method
	 * @param string $url
	 * @return string
	 */
	public static function getController(string $method, string $url) : string
	{
		if(!empty(self::$controllerAction)) {
			return self::$controllerAction;
		}

		return self::$routes[$method][$url];
	}

	/**
	 * Retorna los parámetros de la url solicitada
	 * @param string $method
	 * @param string $url
	 * @return string
	 */
	public static function getUrlParams()
	{
		return self::$urlParams;
	}

	
}