<?php
namespace Models;

use Exception;

/**
 * Clase que manejará todo lo referente a la tabla posts (publicaciones simples en el muro de un usuario)
 */
class Post extends Model
{
	// Redefino variables heredadas de Model que son comunes a todas las instancias de esta clase
	/**
   	 * @var string $table - Nombre de la tabla de Post
   	 * @var string $primaryKey - Clave primaria de Post
     * @var array $attributes - Campos de los registros de Post
     * @var array $rules - Reglas de validación
	 * @var array $editable - Los campos que son editables
     */
	protected static $table = 'posts';
	protected static $primaryKey = 'id';
	protected static $attributes = [
		'id',
		'text',
		'creationDate',
		'fkuser'
	];
	public static $rules = [
		'text' => ['required', 'max:255'],
	];
	public static $editable = ['text'];


	/* Todas las propiedades de esta clase serán protected, para permitir el reúso futuro y la posibilidad
	de definir otra clase que herede de ésta */
  	/**
   	 * @var int $id - ID de la publicacion
   	 * @var string $text - Texto de la publicacion
   	 * @var string $creationDate - Fecha de alta de la publicacion
   	 * @var int $fkuser - ID del usuario que realizó la publicacion
     */
	protected $id;
	protected $text;
	protected $creationDate;
	protected $fkuser;

	/**
	 * Crea una nueva publicación y la almacena en la base de datos
	 * @param array $data - Los datos de la publicacion a crear
	 * @return static
  	 * @throws Exception - Si hubo algún error en la DB y no se pudo crear el post 
	 */
	public static function create(array $data)
	{
		/* Hago un merge entre los datos que se quieren insertar desde $data y los que agrega esta función,
		que es la fecha actual para el alta (necesarios cuando se crea un post) */
		$data = array_merge($data, ['creationDate' => date('Y-m-d H:i:s')]);

		return parent::create($data);
	}

	//////////////////////////////GETTERS//////////////////////////////

	/**
	 * Devuelve el id del post actual
	 * @return int
	 */
	public function getId() : int
	{
		return $this->id;
	}

	/**
	 * Devuelve la descripción del post actual
	 * @return string
	 */
	public function getText() : string
	{
		return $this->text;
	}

	/**
	 * Devuelve fecha y hora de alta del post actual
	 * @return string
	 */
	public function getCreationDate() : string
	{
		return $this->creationDate;
	}

	/**
	 * Devuelve el ID del usuario que publicó el post actual
	 * @return int
	 */
	public function getFkuser() : int
	{
		return $this->fkuser;
	}

	/**
	 * Devuelve el User que hizo este post
	 * @return User - El usuario cuyo ID es fkuser
  	 * @throws Exception - Si hubo algún error en la DB  
	 */	
	public function getUser() : User
	{
		return User::getByID($this->fkuser);
	}



	//////////////////////////////SETTERS//////////////////////////////
	/* Setter protected, las propiedades son read-only desde afuera de la clase */
	
	/**
	 * Setea el id del post actual
	 * @param int $id
	 * @return void
	 */
	protected function setId(int $id)
	{
		$this->id = $id;
	}

	/**
	 * Setea la descripción del post actual
	 * @param string $description
	 * @return void
	 */
	protected function setText(string $text)
	{
		$this->text = $text;
	}

	/**
	 * Setea la fecha y hora de alta del post actual
	 * @param string $creationDate
	 * @return void
	 */
	protected function setCreationDate(string $creationDate)
	{
		$this->creationDate = $creationDate;
	}

	/**
	 * Setea el ID del usuario que realizó el post actual
	 * @param int $fkuser
	 * @return void
	 */
	protected function setFkuser(int $fkuser)
	{
		$this->fkuser = $fkuser;
	}

}