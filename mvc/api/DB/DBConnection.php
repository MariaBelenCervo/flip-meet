<?php
namespace DB;

use PDO;

/**
 * Clase que realizará la conexión a la base de datos
 *
 * Las propiedades y métodos de esta clase serán estáticos, ya que no habrá en ningún caso
 * instancias de esta clase que necesiten acceder a ellos.
 * La conexión es única, y su valor debe existir desde la creación de la clase hasta el final del programa.
 * Sus propiedades y métodos serán también privados, ya que sólo la propia clase necesita acceder a ellos.
 * El único método público será el getter: getCnn(), que devolverá la conexión.
 */
class DBConnection
{
	/***********************************PROPIEDADES***********************************/
	/**
	 * Variable que va a almacenar el Php Data Object (PDO)
	 * @var PDO
	 */
	private static $cnn;

	/***********************************CONSTRUCTOR***********************************/
	/**
	 * Constructor privado para asegurar el Singleton
	 */
	// Esto evita que se puedan crear instancias de esta clase fuera de la misma
	private function __construct() 
	{}

	/******************************MÉTODOS DE LA CONEXIÓN*****************************/
	/**
	* Abre la conexión a la base de datos a través de PDO
	*/
	private static function openCnn()
	{
		// Variables que contienen los datos de conexión
		$DBhost = "localhost";
		$DBbase = "tp2_cervo_mariabelen";
		$DBuser = "root";
		$DBpass = "";

		// Armo el Driver Source Name (DNS) con los datos necesarios
		$DBdns = "mysql:host=$DBhost;dbname=$DBbase;charset=utf8";

		// Realizo la conexión a la base
		self::$cnn = new PDO($DBdns, $DBuser, $DBpass);
	}

	/**
	* Devuelve la conexión a la base de datos en modo Singleton
	* @return PDO
	*/
	public static function getCnn()
	{
		// Si la conexión aún no existe...
		if(!self::$cnn) {
			self::openCnn();
		}

		// En cualquier caso, retorno la conexión
		return self::$cnn;
	}
	
	
}