<?php
namespace Models;

use Models\User;
use Models\Post;
use Exception;

/**
 * Clase que manejará todo lo referente a la tabla user_comments_post (comentarios)
 */
class Comment extends Model
{
	// Redefino variables heredadas de Model que son comunes a todas las instancias de esta clase
	/**
   	 * @var string $table - Nombre de la tabla de Comment
   	 * @var string $primaryKey - Clave primaria de Comment
     * @var array $rules - Reglas de validación
	 * @var array $editable - Los campos que son editables
     * @var array $attributes - Campos de los registros de Comment
     */
	protected static $table = 'comments';
	protected static $primaryKey = 'id';
	protected static $attributes = [
		'id',
		'fkuser', 
		'fkpost', 
		'creationDate',
		'text'
	];
	public static $rules = [
		'text' => ['required', 'max:255'],
	];
	public static $editable = ['text'];

	/* Todas las propiedades de esta clase serán protected, para permitir el reúso futuro y la posibilidad de definir otra clase que herede de ésta */
  	/**
   	 * @var int $id - ID del comentario
   	 * @var int $fkuser - ID del usuario que hizo ese comentario
   	 * @var int $fkpost - ID de la publicación en la que se hizo ese comentario
   	 * @var string $startdate - Fecha de alta del comentario
   	 * @var string $text - Contenido del comentario
     */
	protected $id;
	protected $fkuser;
	protected $fkpost;
	protected $creationDate;
	protected $text;

	// Propiedades propias de esta clase pero que no forman parte de los atributos del modelo
	/**
   	 * @var int $user - Nombre del usuario que hizo el comentario
   	 * @var int $post - Título de la publicación donde se hizo el comentario
   	 */
	protected $user;
	protected $post;

	/**
	 * Setea las propiedades del objeto instanciado en base al array proporcionado
	 * @param array $data
	 * @return void
	 */
	public function loadDataFromRow(array $data)
	{
		// Heredo el parent
		parent::loadDataFromRow($data);

		// Extiendo su funcionalidad
		$this->setUser($this->fkuser);
		$this->setPost($this->fkpost);
	}

	/**
	 * Crea un nuevo comentario y lo almacena en la base de datos
	 * @param array $data - Los datos del comentario a crear
	 * @return static
  	 * @throws Exception - Si hubo algún error en la DB y no se pudo crear el comentario  
	 */
	public static function create(array $data)
	{
		/* Hago un merge entre los datos que se quieren insertar desde $data y los que agrega esta función,
		que es la fecha actual para el alta (necesarios cuando se crea un comentario) */
		$data = array_merge($data, ['startdate' => date('Y-m-d H:i:s', time())]);

		return parent::create($data);
	}

	/**
	 * Devuelve un comentario según su ID
	 * @param int $id - ID del comentario que quiero recuperar
	 * @return self - El comentario correspondiente a $id
  	 * @throws Exception - Si hubo algún error en la DB o si no existe el comentario con ese ID  
	 */	
	public static function getByID(int $id) 
	{
		$objs = parent::getByAttribute('id', $id);

		if (!isset($objs[0])) {
			throw new Exception("Error al obtener comentario. ID = $id inexistente en la tabla " . static::$table . ".");
		}
		return $objs[0];
	}

	/**
	 * Devuelve el User correspondiente a este comentario
	 * @return User - El usuario cuyo ID es fkuser
  	 * @throws Exception - Si hubo algún error en la DB  
	 */	
	public function user() : User
	{
		return User::getByID($this->fkuser);
	}

	/**
	 * Devuelve el Post correspondiente a este comentario
	 * @return Post - El Post cuyo ID es fkpost
  	 * @throws Exception - Si hubo algún error en la DB  
	 */	
	public function post() : Post
	{
		return Post::getByID($this->fkpost);
	}

	//////////////////////////////GETTERS//////////////////////////////

	/**
	 * Devuelve el id del comentario actual
	 * @return int
	 */
	public function getId() : int
	{
		return $this->id;
	}

	/**
	 * Devuelve el ID del usuario que hizo este comentario
	 * @return int
	 */
	public function getFkuser() : int
	{
		return $this->fkuser;
	}

	/**
	 * Devuelve el usuario que hizo el comentario actual
	 * @return string
	 */
	public function getUser() : string
	{
		return $this->user;
	}

	/**
	 * Devuelve el ID de la publicación en la que se hizo este comentario
	 * @return int
	 */
	public function getFkpost()
	{
		return $this->fkpost;
	}

	/**
	 * Devuelve el título de la publicación donde se hizo el comentario actual
	 * @return string
	 */
	public function getPost() : string
	{
		return $this->post;
	}

	/**
	 * Devuelve fecha y hora de alta del comentario actual
	 * @return string
	 */
	public function getStartdate() : string
	{
		return $this->startdate;
	}

	/**
	 * Devuelve el contenido del comentario actual
	 * @return string
	 */
	public function getComment() : string
	{
		return $this->comment;
	}

	//////////////////////////////SETTERS//////////////////////////////
	/* Setter protected, las propiedades son read-only desde afuera de la clase */

	/**
	 * Setea el id del comentario actual
	 * @param int $id
	 * @return void
	 */
	protected function setId(int $id)
	{
		$this->id = $id;
	}

	/**
	 * Setea el ID del usuario correspondiente a este comentario
	 * @param int $fkuser
	 * @return void
	 */
	protected function setFkuser(int $fkuser)
	{
		$this->fkuser = $fkuser;
	}

	/**
	 * Setea el ID correspondiente a la publicación donde se hizo este comentario
	 * @param int $fkpost
	 * @return void
	 */
	protected function setFkpost(int $fkpost)
	{
		$this->fkpost = $fkpost;
	}

	/**
	 * Setea la fecha y hora de alta de este comentario
	 * @param string $startdate
	 * @return void
	 */
	protected function setStartdate(string $startdate)
	{
		$this->startdate = $startdate;
	}

	/**
	 * Setea el contenido de este comentario
	 * @param string $comment
	 * @return void
	 */
	protected function setComment(string $comment)
	{
		$this->comment = $comment;
	}

	/**
	 * Setea el usuario que hizo el comentario actual
	 * @param int $fkuser
	 * @return void
	 */
	protected function setUser(int $fkuser)
	{
		$email = User::getByID($fkuser)->email;
		$this->user = ucfirst(explode('@', $email)[0]);
	}

	/**
	 * Setea el post en el que se hizo el comentario actual
	 * @param int $fkpost
	 * @return void
	 */
	protected function setPost(int $fkpost)
	{
		$this->post = ucfirst(Post::getByID($fkpost)->title);
	}

	///////////////////SERIALIZACIÓN A FORMATO JSON////////////////////
	/**
	 * Método de serialización a JSON.
	 * @return array
	 */
	public function JsonSerialize()
	{
		// Heredo del parent
		$json = parent::JsonSerialize();

		// Extiendo su funcionalidad
		$json['user'] = $this->user;
		$json['post'] = $this->post;
		
		return $json;
	}


}