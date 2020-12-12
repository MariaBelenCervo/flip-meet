<?php
namespace Models;

use Exception;

/**
 * Clase que maneja todo lo referente al los intereses de los usuarios (amigos, una relación)
 */
class Interest extends Model
{
	// Redefino variables heredadas de Model que son comunes a todas las instancias de esta clase
	/**
   	 * @var string $table - Nombre de la tabla de Interest
   	 * @var string $primaryKey - Clave primaria de Interest 'id'
     * @var array $attributes - Campos de los registros de Interest
     */
	protected static $table = 'interests';
	protected static $primaryKey = 'id';
	protected static $attributes = [
		'id',
		'interest' 
	];

	/* Todas las propiedades de esta clase serán protected, para permitir el reúso futuro y la posibilidad
	de definir otra clase que herede de ésta */
  	/**
   	 * @var int $id - ID del interés
     * @var string $interest - 1 = 'amigos', 2 = 'una relación'
     */
	protected $id;
	protected $interest;

	/**
	 * Devuelve un interés según su ID
	 * @param int $id - ID del interés que quiero recuperar
	 * @return self - El interés correspondiente a $id
  	 * @throws Exception - Si hubo algún error en la DB o si no existe el interés con ese ID  
	 */	
	public static function getByID(int $id) 
	{
		$objs = parent::getByAttribute('id', $id);

		if (is_array($objs)) {
			if (!isset($objs[0])) {
				throw new Exception("Error al obtener interés. InterestID = $id inexistente en la tabla " . static::$table . ".");
			}
			return $objs[0];
		}
	}

	/**
	 * Actualiza el interés de un usuario (fkinterest)
	 * @param User $user - El usuario a modificar
	 * @param string $fkinterest - El ID del nuevo interés
	 * @return bool - TRUE si se modificó correctamente el usuario. FALSE de lo contrario. 
  	 * @throws Exception - Si hubo error de algún tipo en la Base de Datos 
	 */
	protected static function changeInterest(User $user, int $fkinterest)
	{
		return $user->update(['fkinterest' => $fkinterest]);
	}

	//////////////////////////////GETTERS//////////////////////////////

	/**
	 * Devuelve el id del interés actual
	 * @return int
	 */
	public function getId() : int
	{
		return $this->id;
	}

	/**
	 * Devuelve el interés actual
	 * @return string
	 */
	public function getInterest() : string
	{
		return $this->interest;
	}

	//////////////////////////////SETTERS//////////////////////////////
	/* Setter protected, las propiedades son read-only desde afuera de la clase */

	/**
	 * Setea el id del interés actual
	 * @param int $id
	 * @return void
	 */
	protected function setId(int $id)
	{
		$this->id = $id;
	}

	/**
	 * Setea el interés del usuario actual
	 * @param string $interest
	 * @return void
	 */
	protected function setInterest(string $interest)
	{
		$this->interest = $interest;
	}

	
}