<?php
namespace Models;

use Exception;
use Models\User;


/**
 * Clase que manejará todo lo referente a la tabla spots (publicaciones de spots de skate)
 */
class Spot extends Model
{
	// Redefino variables heredadas de Model que son comunes a todas las instancias de esta clase
	/**
   	 * @var string $table - Nombre de la tabla de Spot
   	 * @var string $primaryKey - Clave primaria de Spot
     * @var array $attributes - Campos de los registros de Spot
     * @var array $rules - Reglas de validación
	 * @var array $editable - Los campos que son editables
     */
	protected static $table = 'spots';
	protected static $primaryKey = 'id';
	protected static $attributes = [
		'id',
		'photo',
		'title',
		'location',
		'description',
		'creationDate',
		'fkuser'
	];
	public static $rules = [
		'photo' => ['required'],
		'title' => ['required', 'max:100'],
		'location' => ['required', 'min:4']
	];
	public static $editable = ['photo', 'title', 'location', 'description'];

  	/**
   	 * @var int $id - ID del post
   	 * @var string $photo - Foto principal del lugar
   	 * @var string $title - Título
   	 * @var string $location - Dirección y ciudad del spot publicado
   	 * @var string $description - Descripción del spot
   	 * @var string $creationDate - Fecha de alta del posteo
   	 * @var int $fkuser - ID del usuario que realizó el posteo
     */
	protected $id;
	protected $photo;
	protected $title;
	protected $location;
	protected $description;
	protected $creationDate;
	protected $fkuser;

	// Propiedades propias de esta clase pero que no forman parte de los atributos del modelo
	/**
   	 * @var User $user - El objeto Usuario que hizo el comentario
   	 */
	protected $user;
	
	/**
	 * Crea un nuevo posteo de un Spot y lo almacena en la base de datos
	 * @param array $data - Los datos del post a crear
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
	 * Devuelve la foto del post actual
	 * @return string
	 */
	public function getPhoto() : string
	{
		return $this->photo;
	}

	/**
	 * Devuelve el título del post actual
	 * @return string
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * Devuelve la dirección y ciudad del post actual
	 * @return string
	 */
	public function getLocation() : string
	{
		return $this->location;
	}

	/**
	 * Devuelve la descripción del post actual
	 * @return string
	 */
	public function getDescription() : string
	{
		return $this->description;
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
	 * Setea la foto del post actual
	 * @param string $photo
	 * @return void
	 */
	protected function setPhoto(string $photo)
	{
		$this->photo = $photo;
	}

	/**
	 * Setea el título del post actual
	 * @param string $title
	 * @return void
	 */
	protected function setTitle(string $title)
	{
		$this->title = $title;
	}

	/**
	 * Setea la ubicación del post actual
	 * @param string $location
	 * @return void
	 */
	protected function setLocation(string $location)
	{
		$this->location = $location;
	}

	/**
	 * Setea la descripción del post actual
	 * @param string $description
	 * @return void
	 */
	protected function setDescription(string $description)
	{
		$this->description = $description;
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