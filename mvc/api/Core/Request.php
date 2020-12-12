<?php
namespace Core;

use Core\App;

/**
 * Clase que se encargará de manipular los datos de las peticiones ajax
 * (el verbo REST, la url y los parámetros)
 */
class Request
{
	/** @var string $url - La ruta buscada a partir de public/api/ */
    private $url;
    /** @var string $method - El verbo obtenido de la petición */
	private $method;
	/** @var array $bodyParams - Los parámetros obtenidos del cuerpo de la petición */
	private $bodyParams = [];

	/**
	 * Devuelve el verbo REST de la petición
	 * @return string
	 */
	public function method()
	{
		return $this->method;
	}

	/**
	 * Devuelve la url de la petición
	 * @return string
	 */
	public function url()
	{
		return $this->url;
	}

	/**
	 * Devuelve los parámetros de la petición
	 * @return object
	 */
	public function bodyParams()
	{
		return $this->bodyParams;
	}

	/**
	 * Constructor de Request
	 */
	public function __construct()
	{
		// Obtengo la ruta buscada luego del directorio "public/"
        // Ejemplo: http://localhost/FlipMeet/mvc/public/login
        // Para ello, primero obtengo la ruta absoluta que pidió el usuario
        $absoluteRoute = $_SERVER['DOCUMENT_ROOT'] . $_SERVER['REQUEST_URI'];
        /*
        Ahora utilizo el path absoluto de la api que guardé previamente en la clase App,
        restándoselo a la ruta absoluta pedida por el usuario
        -> Ejemplo:
        	- a la ruta http://localhost/FlipMeet/mvc/public/login
        	- le resto http://localhost/FlipMeet/mvc/public/
        	- resultado: login
        */
        // Guardo la url pedida
        $this->url = str_replace(App::getPublicPath(), '', $absoluteRoute);

		// Ahora obtengo el verbo REST de los datos del servidor
		$this->method = strtoupper($_SERVER['REQUEST_METHOD']);

		// Y finalmente, obtengo los parámetros recibidos en la petición
		/* Los parámetros recibidos en la url serán obtenidos por Route, por lo que Request
		se encargará de aquellos parámetros recibidos en el cuerpo de la petición ($bodyParams).
		Por ese motivo, sólo se evaluarán los verbos distintos de GET */
		if ($this->method !== 'GET') {
			// Si el verbo REST no es GET...
			// Obtengo y guardo los datos del body de la request
			// $urlData = (object) json_decode(file_get_contents('php://input'), true);
			$this->bodyParams = json_decode(file_get_contents('php://input'), true);
		}

	}

	/**
	 * Genera un único objeto a partir de los parámetros de la url
	 * y de los de el body de la petición
	 * @param array $bodyParams
	 * @param array $urlParams
	 * @return object
	 */
	public static function parseParams($bodyParams, $urlParams) {
		/* Hago un merge entre los parámetros provenientes de la url y los provenientes del cuerpo
		de la petición */
		if ($bodyParams !== null) {
			return (object) array_merge($urlParams, $bodyParams);
		} else {
			return (object) $urlParams;
		}
	}


}