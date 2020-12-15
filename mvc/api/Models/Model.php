<?php
namespace Models;

use DB\DBConnection;
use PDO;
use Exception;
use JsonSerializable;

/**
 * Clase que incluirá todas las funcionalidades básicas de creación, edición, obtención 
 * y eliminación de registros.
 */
class Model implements JsonSerializable
{
	/** @var string - El nombre de la tabla del modelo */
	protected static $table = '';

	/** @var string - El atributo que funcionará como Primary Key */
	protected static $primaryKey = '';
	
	/** @var array - Los atributos de la tabla del modelo */
	protected static $attributes = [];

	/** @var array - Las reglas de validación de los atributos del modelo */
	protected static $rules = [];

	/** @var array - Los campos que son editables */
	protected static $editable = [];

	/**
	 * Constructor del modelo
	 * @param int|null $pk
	 */
	public function __construct($pk = null)
	{
		if (!is_null($pk)) {
			$this->getByPK($pk);
		}
	}

	/**
	 * Obtiene un registro por su llave primaria
	 * @param int $pk
	 * @return void
  	 * @throws Exception - Si hubo algún error en la DB y no se pudo obtener el registro 
	 */
	protected function getByPK(int $pk)
	{
		$db = DBConnection::getCnn();
		$query = "SELECT * FROM " . static::$table .
			" WHERE " . static::$primaryKey . " = ?";
		
		$stmt = $db->prepare($query);

		if ($stmt->execute([$pk])) {
			$this->loadDataFromRow($stmt->fetch(PDO::FETCH_ASSOC));
		} else {
			throw new Exception("Error al recuperar el objeto $pk de la tabla " . static::$table . ".");
		}
	}

	/**
	 * Setea las propiedades del objeto instanciado en base al array proporcionado
	 * @param array $data
	 * @return void
	 */
	public function loadDataFromRow(array $data)
	{
		foreach (static::$attributes as $attr) {
			if (isset($data[$attr])) {
				$setter = 'set' . ucfirst($attr);
				if (method_exists($this, $setter)) {
					$this->{$setter}(ucfirst($data[$attr]));
				}
			}
		}
	}

	/**
	 * Obtiene un array de registros por un atributo pasado como parámetro
	 * @param string $attr
	 * @param mixed $value
	 * @return array - Si no coincidió ningún registro se devuelve array vacío
	 * @throws Exception - Si hubo algún error en la DB y no se pudieron obtener los registros
	 */
	public static function getByAttribute(string $attr, $value) : array
	{
		$db = DBConnection::getCnn();
		$query = "SELECT * FROM " . static::$table . "
				WHERE " . $attr . " = ?";
		
		$stmt = $db->prepare($query);
		$success = $stmt->execute([$value]);

		if ($success) {
			$output = [];
			
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$obj = new static;
				$obj->loadDataFromRow($row);
				$output[] = $obj;
			}

			return $output;
		} else {
			throw new Exception("Error al obtener datos de la tabla " . static::$table . ".");
		}
	}

	/**
	 * Obtiene un array de todos los registros de la tabla
	 * @return array - Si no hay registros devuelve array vacío
	 * @throws Exception - Si hubo algún error en la DB y no se pudieron obtener los registros
	 */
	public static function getAll() : array
	{
		$db = DBConnection::getCnn();
		$query = "SELECT * FROM " . static::$table;
		
		$stmt = $db->prepare($query);
		$success = $stmt->execute();

		if ($success) {
			$output = [];

			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$obj = new static;
				$obj->loadDataFromRow($row);
				$output[] = $obj;
			}

			return $output;
		} else {
			throw new Exception("Error al obtener datos de la tabla " . static::$table . ".");
		}
	}

	/**
	 * Crea un registro nuevo en la tabla
	 * @param array $data
	 * @return static
	 * @throws Exception - Si hubo algún error en la DB y no se pudo crear el registro
	 */
	public static function create(array $data)
	{
		$insertQuery = self::buildInsertQuery($data);
		$insertData = self::filterData($data);
		
		$db = DBConnection::getCnn();

		$stmt = $db->prepare($insertQuery);
		$success = $stmt->execute($insertData);
		
		if ($success && $stmt->rowCount() == 1) {
			$obj = new static;

			// Agrego PK generada automáticamente al array $data
			$data[static::$primaryKey] = $db->lastInsertId();
			
			$obj->loadDataFromRow($data);

			return $obj;
		} else {
			throw new Exception("Error al crear registro de la tabla " . static::$table . ".");
		}
	}

	/**
	 * Actualiza el registro correspondiente a esta instancia
	 * @param array $data - Los datos a actualizar
	 * @return bool - TRUE si se actualizó correctamente el usuario, de lo contrario FALSE.
	 * @throws Exception - Si hubo algún error en la DB y no se pudo actualizar el registro
	 */	
	public function update(array $data) : bool
	{
		$pk = $this->{static::$primaryKey};

		// Armo el query string para actualizar los datos
		$updateQuery = self::buildUpdateQuery($data);

		// Agrego el campo $primaryKey dentro de mis datos para enviar al execute()
		$data[static::$primaryKey] = $pk; 

		$updateData = self::filterData($data);

		$db = DBConnection::getCnn();

		$stmt = $db->prepare($updateQuery);
		$success = $stmt->execute($updateData);

		if ($success) {
			if ($stmt->rowCount() == 0) {
				return FALSE;
			}

			// Actualizo las propiedades de este objeto
			$this->loadDataFromRow($data);
			return TRUE;
		} else {
			throw new Exception("Error al actualizar registro de la tabla " . static::$table . ".");
		}
	}

	/**
	 * Elimina el registro correspondiente a la clave primaria $pk
	 * @param int $pk - Clave primaria del registro a eliminar
	 * @return bool - TRUE si se eliminó correctamente el usuario, de lo contrario FALSE.
	 * @throws Exception - Si hubo algún error en la DB y no se pudo eliminar el registro
	 */	
	public static function delete(int $pk) : bool
	{
		$db = DBConnection::getCnn();

		// Query para eliminar el registro
		$query = "DELETE FROM " . static::$table . " WHERE " . static::$primaryKey . " = ?";

		$stmt = $db->prepare($query);
		$success = $stmt->execute([$pk]);

		if ($success) {
			if ($stmt->rowCount() == 0) {
				return FALSE;
			}
			return TRUE;
		} else {
			throw new Exception("Error al eliminar registro de la tabla " . static::$table . ".");
		}
	}

	/**
	 * Arma el query para un INSERT automáticamente en base a los atributos pasados
	 * @param array $data - Array de atributo->valor
	 * @return string - El query ya armado
	 */
	protected static function buildInsertQuery(array $data) : string
	{
		// Me quedo sólo con nombres de atributos que pertenezcan al modelo
		$fields = array_keys(self::filterData($data));

		$query = "INSERT INTO " . static::$table . " (" . 
			implode(', ', $fields) . ") VALUES (:" . 
			implode(', :', $fields) . ")";
		
		return $query;
	}

	/**
	 * Arma el query para un UPDATE automáticamente en base a los atributos pasados
	 * @param int $pk - La clave primaria del modelo
	 * @param array $data - Array de atributo->valor
	 * @return string - El query ya armado
	 */
	protected static function buildUpdateQuery(array $data) : string
	{
		// Me quedo sólo con nombres de atributos que pertenezcan al modelo
		$fields = array_keys(self::filterData($data));

		$query = 'UPDATE ' . static::$table . ' SET ';

		foreach ($fields as $field) {
			$query .= $field . ' = :' . $field . ', ';
		}

		// Elimino la coma al final del string
		$query = rtrim($query, ', ');

		// Le concateno el WHERE final
		$query .= " WHERE " . static::$primaryKey . " = :" . static::$primaryKey . " LIMIT 1";
		
		return $query;
	}

	/**
	 * Filtra el array $data y retorna un array solamente con los datos reconocidos como atributos
	 * @param array $data - Array de atributo->valor
	 * @return array
	 */
	protected static function filterData(array $data) : array
	{
		$inAttributes = function ($key) {
			return in_array($key, static::$attributes);
		};
		
		return array_filter($data, $inAttributes, ARRAY_FILTER_USE_KEY);
	}

	/**
	 * Filtra el array $rules únicamente con los attributos que son editables
	 * @return array
	 */
	public static function editableRules() : array
	{
		$keys = array_flip(static::$editable);
		return array_intersect_key(static::$rules, $keys);	
	}


	/* Utilizo el método __get para llamaer a los getters de esta instancia desde afuera
	como si se estuviera accediendo directamente (sin encapsulamiento) a dicha propiedad */ 
	/**
	 * __get()
	 * @param string $name - Nombre de la propiedad
	 * @return mixed - Valor de la propiedad
	 */
	public function __get(string $name) 
	{
		$getter = 'get' . ucfirst($name);
		if (method_exists($this, $getter)) {
			return $this->{$getter}();
		} else {
			throw new Exception("Propiedad $name inexistente en " . static::$table . ".");
		}
	} 

	/**
	 * __set()
	 * @param string $name - Nombre de la propiedad
	 * @param mixed $value - Valor de la propiedad
	 * @return void
	 */
	public function __set(string $name, $value) 
	{
		$setter = 'set' . ucfirst($name);
		if (method_exists($this, $setter)) {
			throw new Exception("No se puede cambiar la propiedad $name de sólo lectura en " . static::$table . ".");
		} else {
			throw new Exception("Propiedad $name inexistente en " . static::$table . ".");
		}
	} 	

	///////////////////SERIALIZACIÓN A FORMATO JSON////////////////////
	/**
	 * Método de serialización a JSON.
	 * @return array
	 */
	public function JsonSerialize()
	{
		$json = [];

		foreach (static::$attributes as $attr) {
			$json[$attr] = $this->{$attr};
		}

		return $json;
	}


}