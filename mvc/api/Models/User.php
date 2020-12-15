<?php
namespace Models;

use Models\Interest;
use Security\Hash;
use Exception;

/**
 * Clase que manejará todo lo referente a la tabla usuarios
 */
class User extends Model
{
	// Redefino variables heredadas de Model que son comunes a todas las instancias de esta clase
	/**
   	 * @var string $table - Nombre de la tabla de User
   	 * @var string $primaryKey - Clave primaria de User 'id'
     * @var array $attributes - Campos de los registros de User
     * @var array $rules - Reglas de validación
	 * @var array - Los campos que son editables
	*/
	protected static $table = 'users';
	protected static $primaryKey = 'id';
	protected static $attributes = [
		'id',
		'name', 
		'lastname', 
		'email', 
		'password', 
		'photo',
		'startdate',
		'location',
		'birthday',
		'fkinterest'
	];
	public static $rules = [
		'name' => ['required', 'max:75'],
		'lastname' => ['required', 'max:75'],
		'email' => ['required', 'email'],
		'password' => ['required', 'min:5', 'max:16'],
		'location' => ['min:4'],
		'birthday' => ['required']
	];
	public static $editable = ['name', 'lastname', 'password', 'location', 'birthday', 'photo', 'fkinterest'];


	/* Todas las propiedades de esta clase serán protected, para permitir el reúso futuro y la posibilidad
	de definir otra clase que herede de ésta */
  	/**
   	 * @var int $id - ID del usuario
   	 * @var string|null $name - Nombre del usuario
   	 * @var string|null $lastName - Apellido del usuario
   	 * @var string $email - Email del usuario
   	 * @var string $pass - Password del usuario
   	 * @var string $photo - Foto de perfil del usuario
   	 * @var string $startdate - Fecha de alta del usuario
     * @var int $fkinterest - ID del interés del usuario (qué busca al usar esta red social)
     */
	protected $id;
	protected $name;
	protected $lastname;
	protected $email;
	protected $password;
	protected $photo;
	protected $startdate;
	protected $location;
	protected $birthday;
	protected $fkinterest;

	// Atributo propio de mi clase pero no de mi tabla
	protected $interest;

	/**
	 * Setea las propiedades del objeto instanciado en base al array proporcionado
	 * @param array $data
	 * @return void
	 */
	public function loadDataFromRow(array $data)
	{
		// Heredo de parent
		parent::loadDataFromRow($data);

		// Extiendo su funcionalidad
		$this->setInterest($this->fkinterest);
	}

	/**
	 * Crea un nuevo usuario y lo almacena en la base de datos
	 * @param array $data - Los datos del usuario a crear
	 * @return static
  	 * @throws Exception - Si hubo algún error en la DB y no se pudo crear el usuario  
	 */
	public static function create(array $data)
	{
		// Obtengo la parte útil del input type date del front-end
		$data['birthday'] = substr($data['birthday'], 0, 10);
		// Encripto la contraseña
		if($data['password'] !== null) {
			$data['password'] = Hash::make($data['password']);
		}
		/* Hago un merge entre los datos que se quieren insertar desde $data y los que agrega esta función,
		que son estado ACTIVE y fecha actual para el alta (necesarios cuando se crea un usuario) */
		$data = array_merge($data, ['startdate' => date('Y-m-d H:i:s')]); // FIXME Agregar estado ACTIVE?

		return parent::create($data);
	}

	/**
	 * Actualiza el usuario
	 * @param array $data - Los datos a actualizar
	 * @return bool - TRUE si se actualizó correctamente el usuario, de lo contrario FALSE.
	 * @throws Exception - Si hubo algún error en la DB y no se pudo actualizar el registro
	 */	
	public function update(array $data) : bool
	{
		// Obtengo la parte útil del input type date del front-end
		$data['birthday'] = substr($data['birthday'], 0, 10);

		// Encripto la contraseña
		if($data['password'] !== null) {
			$data['password'] = Hash::make($data['password']);
		}
		/* Hago un merge entre los datos que se quieren insertar desde $data y los que agrega esta función,
		que son estado ACTIVE y fecha actual para el alta (necesarios cuando se crea un usuario) */
		$data = array_merge($data, ['startdate' => date('Y-m-d H:i:s')]); // FIXME Agregar estado ACTIVE?

		return parent::update($data);
	}


	/**
	 * Devuelve un usuario según su email
	 * @param string $email - Email del usuario que quiero recuperar
	 * @return self|null - Si no existe un usuario con ese email devuelve null
  	 * @throws Exception - Si hubo algún error en la DB y no se pudo recuperar el usuario  
	 */	
	public static function getByEmail(string $email) 
	{
		$objs = parent::getByAttribute('email', $email);

		return $objs[0] ?? null;
	}

	/**
	 * Devuelve un usuario según su ID
	 * @param int $id - ID del usuario que quiero recuperar
	 * @return self - El usuario correspondiente a $id
  	 * @throws Exception - Si hubo algún error en la DB o si no existe el usuario con ese ID  
	 */	
	public static function getByID(int $id) 
	{
		$objs = parent::getByAttribute('id', $id);

		if (!isset($objs[0])) {
			throw new Exception("Error al obtener usuario. UserID = $id inexistente en la tabla " . static::$table . ".");
		}
		return $objs[0];
	}

	//////////////////////////////GETTERS//////////////////////////////
	
	/**
	 * Devuelve el id del usuario actual
	 * @return int
	 */
	public function getId() : int
	{
		return $this->id;
	}

	/**
	 * Devuelve el name del usuario actual
	 * @return string
	 */
	public function getName() : string
	{
		return $this->name;
	}

	/**
	 * Devuelve el lastname del usuario actual
	 * @return string
	 */
	public function getLastname()
	{
		return $this->lastname;
	}

	/**
	 * Devuelve el email del usuario actual
	 * @return string
	 */
	public function getEmail() : string
	{
		return $this->email;
	}

	/**
	 * Devuelve el password del usuario actual
	 * @return string
	 */
	public function getPassword() : string
	{
		return $this->password;
	}

	/**
	 * Devuelve la foto de perfil del usuario actual
	 * @return string
	 */
	public function getPhoto() : string
	{
		return $this->photo;
	}

	/**
	 * Devuelve el alta (startdate) del usuario actual
	 * @return string
	 */
	public function getStartdate() : string
	{
		return $this->startdate;
	}

	/**
	 * Devuelve la ubicación del usuario actual
	 * @return string
	 */
	public function getLocation() : string
	{
		return $this->location;
	}

	/**
	 * Devuelve la fecha de nacimiento del usuario actual
	 * @return string
	 */
	public function getBirthday() : string
	{
		return $this->birthday;
	}

	/**
	 * Devuelve el interés del usuario actual
	 * @return int
	 */
	public function getFkinterest() : int
	{
		return $this->fkinterest;
	}

	/**
	 * Devuelve el interés del usuario actual
	 * @return string
	 */
	public function getInterest() : string
	{
		return $this->interest;
	}

	//////////////////////////////SETTERS//////////////////////////////
	/* Setter protected, las propiedades son read-only desde afuera de la clase */

	/**
	 * Setea el id del usuario actual
	 * @param int $id
	 * @return void
	 */
	protected function setId(int $id)
	{
		$this->id = $id;
	}

	/**
	 * Setea el name del usuario actual
	 * @param string $name
	 * @return void
	 */
	protected function setName(string $name)
	{
		$this->name = $name;
	}

	/**
	 * Setea el lastname del usuario actual
	 * @param string $lastname
	 * @return void
	 */
	protected function setLastname(string $lastname)
	{
		$this->lastname = $lastname;
	}

	/**
	 * Setea el email del usuario actual
	 * @param string $id
	 * @return void
	 */
	protected function setEmail(string $email)
	{
		$this->email = $email;
	}

	/**
	 * Setea el password del usuario actual
	 * @param string $password
	 * @return void
	 */
	protected function setPassword(string $password)
	{
		$this->password = $password;
	}

	/**
	 * Setea la foto de perfil del usuario actual
	 * @param string $photo
	 * @return void
	 */
	protected function setPhoto(string $photo)
	{
		$this->photo = $photo;
	}

	/**
	 * Setea el alta (startdate) del usuario actual
	 * @param string $startdate
	 * @return void
	 */
	protected function setstartdate(string $startdate)
	{
		$this->startdate = $startdate;
	}

	/**
	 * Setea la ubicación del usuario actual
	 * @param string $location
	 * @return void
	 */
	protected function setLocation(string $location)
	{
		$this->location = $location;
	}

	/**
	 * Setea la fecha de nacimiento del usuario actual
	 * @param string $birthday
	 * @return void
	 */
	protected function setBirthday(string $birthday)
	{
		$this->birthday = $birthday;
	}

	/**
	 * Setea el interés del usuario actual
	 * @param int $fkinterest
	 * @return void
	 */
	protected function setFkinterest(int $fkinterest)
	{
		$this->fkinterest = $fkinterest;
	}

	/**
	 * Setea el interés del usuario actual
	 * @param int $fkinterest
	 * @return void
	 */
	protected function setInterest(int $fkinterest)
	{
		$this->interest = ucfirst(Interest::getByID($fkinterest)->interest);
	}

	///////////////////SERIALIZACIÓN A FORMATO JSON////////////////////
	/**
	 * Método de serialización a JSON.
	 * @return array
	 */
	public function JsonSerialize()
	{
		$json = parent::JsonSerialize();
		$json['interest'] = $this->interest;
		return $json;
	}	

}