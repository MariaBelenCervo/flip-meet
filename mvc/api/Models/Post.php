<?php
namespace Models;

use Models\Category;
use Exception;

/**
 * Clase que manejará todo lo referente a la tabla posts (publicaciones)
 */
class Post extends Model
{
	// Redefino variables heredadas de Model que son comunes a todas las instancias de esta clase
	/**
   	 * @var string $table - Nombre de la tabla de Post
   	 * @var string $primaryKey - Clave primaria de Post
     * @var array $attributes - Campos de los registros de Post
     */
	protected static $table = 'posts';
	protected static $primaryKey = 'id';
	protected static $attributes = [
		'id',
		'photo', 
		'title',
		'location',
		'description', 
		'startdate',
		'fkuser',
		'fkcategory'
	];

	/* Todas las propiedades de esta clase serán protected, para permitir el reúso futuro y la posibilidad
	de definir otra clase que herede de ésta */
  	/**
   	 * @var int $id - ID del post
   	 * @var string $photo - Foto principal del post
   	 * @var string $title - Título del post
   	 * @var string $location - Dirección y ciudad del skatepark o spot publicado
   	 * @var string $startdate - Fecha de alta del post
   	 * @var int $fkcategory - ID de la categoría de este post (skatepark o spot)
     */
	protected $id;
	protected $photo;
	protected $title;
	protected $location;
	protected $description;
	protected $startdate;
	protected $fkuser;
	protected $fkcategory;

	// Atributo propio de mi clase pero no de mi tabla
	protected $category;

	/**
	 * Setea las propiedades del objeto instanciado en base al array proporcionado
	 * @param array $data
	 * @return void
	 */
	public function loadDataFromRow(array $data)
	{
		// Heredo del parent
		parent::loadDataFromRow($data);

		// Extiendo su funcionalidad
		$this->setCategory($this->fkcategory);
	}

	/**
	 * Crea un nuevo post y lo almacena en la base de datos
	 * @param array $data - Los datos del post a crear
	 * @return static
  	 * @throws Exception - Si hubo algún error en la DB y no se pudo crear el post 
	 */
	public static function create(array $data)
	{
		/* Hago un merge entre los datos que se quieren insertar desde $data y los que agrega esta función,
		que es la fecha actual para el alta (necesarios cuando se crea un post) */
		$data = array_merge($data, ['startdate' => date('Y-m-d H:i:s')]);

		return parent::create($data);
	}

	/**
	 * Devuelve un Post según su ID
	 * @param int $id - ID del post que quiero recuperar
	 * @return self - El post correspondiente a $id
  	 * @throws Exception - Si hubo algún error en la DB o si no existe un post con ese ID  
	 */	
	public static function getByID(int $id) 
	{
		$objs = parent::getByAttribute('id', $id);

		if (is_array($objs)) {
			if (!isset($objs[0])) {
				throw new Exception("Error al obtener publicación. ID = $id inexistente en la tabla " . static::$table . ".");
			}

			return $objs[0];
		}
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
	public function getStartdate() : string
	{
		return $this->startdate;
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
	 * Devuelve el ID de la categoría a la que pertenece el post actual
	 * @return int
	 */
	public function getFkcategory() : int
	{
		return $this->fkcategory;
	}

	/**
	 * Devuelve la categoría a la que pertenece el post actual (skatepark o spot)
	 * @return string
	 */
	public function getCategory() : string
	{
		return $this->category;
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
	 * @param string $startdate
	 * @return void
	 */
	protected function setStartdate(string $startdate)
	{
		$this->startdate = $startdate;
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

	/**
	 * Setea el ID de la categoría del post actual
	 * @param int $fkcategory
	 * @return void
	 */
	protected function setFkcategory(int $fkcategory)
	{
		$this->fkcategory = $fkcategory;
	}

	/**
	 * Setea la categoría del post actual
	 * @param int $fkcategory
	 * @return void
	 */
	protected function setCategory(int $fkcategory)
	{
		$this->category = ucfirst(Category::getByID($fkcategory)->category);
	}

	///////////////////SERIALIZACIÓN A FORMATO JSON////////////////////
	/**
	 * Método de serialización a JSON.
	 * @return array
	 */
	public function JsonSerialize()
	{
		$json = parent::JsonSerialize();
		$json['category'] = $this->category;
		return $json;
	}


}