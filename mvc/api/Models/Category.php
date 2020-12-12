<?php
namespace Models;

use Exception;

/**
 * Clase que maneja todo lo referente a las categorías de las publicaciones realizadas por los usuarios
 * (skatepark, spot)
 */
class Category extends Model
{
	// Redefino variables heredadas de Model que son comunes a todas las instancias de esta clase
	/**
   	 * @var string $table - Nombre de la tabla de Category
   	 * @var string $primaryKey - Clave primaria de Category 'id'
     * @var array $attributes - Campos de los registros de Category
     */
	protected static $table = 'categories';
	protected static $primaryKey = 'id';
	protected static $attributes = [
		'id',
		'category' 
	];

	/* Todas las propiedades de esta clase serán protected, para permitir el reúso futuro y la posibilidad
	de definir otra clase que herede de ésta */
  	/**
   	 * @var int $id - ID de la categoría
     * @var string $category
     */
	protected $id;
	protected $category;

	/**
	 * Devuelve una categoría según su ID
	 * @param int $id - ID de la categoría que quiero recuperar
	 * @return self - La categoría correspondiente a $id
  	 * @throws Exception - Si hubo algún error en la DB o si no existe categoría con ese ID  
	 */	
	public static function getByID(int $id) 
	{
		$objs = parent::getByAttribute('id', $id);

		if (is_array($objs)) {
			if (!isset($objs[0])) {
				throw new Exception("Error al obtener categoría. InterestID = $id inexistente en la tabla " . static::$table . ".");
			}
			return $objs[0];
		}
	}

	/**
	 * Actualiza la categoría de una publicación (fkcategory)
	 * @param Post $post - La publicación a modificar
	 * @param string $fkcategory - El ID de la nueva categoría
	 * @return bool - TRUE si se modificó correctamente la publicación. FALSE de lo contrario. 
  	 * @throws Exception - Si hubo error de algún tipo en la Base de Datos 
	 */
	protected static function changeCategory(Post $post, int $fkcategory)
	{
		return $post->update(['fkcategory'=> $fkcategory]);
	}

	//////////////////////////////GETTERS//////////////////////////////

	/**
	 * Devuelve el id de la categoría actual
	 * @return int
	 */
	public function getId() : int
	{
		return $this->id;
	}

	/**
	 * Devuelve la categoría actual
	 * @return string
	 */
	public function getCategory() : string
	{
		return $this->category;
	}

	//////////////////////////////SETTERS//////////////////////////////
	
	/**
	 * Setea el id de la categoría actual
	 * @param int $id
	 * @return void
	 */
	protected function setId(int $id)
	{
		$this->id = $id;
	}

	/**
	 * Setea la categoría de la publicación actual
	 * @param string $category
	 * @return void
	 */
	protected function setCategory(string $category)
	{
		$this->category = $category;
	}


}