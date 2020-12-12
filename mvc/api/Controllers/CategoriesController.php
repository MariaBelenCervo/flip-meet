<?php
namespace Controllers;

use Models\Category;
use Core\View;
use Exception;

/**
 * Clase que se encargará de manejar los métodos de la clase Category
 * y devolverá las respuestas pertinentes
 */
class CategoriesController
{
	/**
	 * Devuelve un array de objetos Category y los mensajes correspondientes de success o error
	 * @return JSON
	 */
	public function getAll()
	{
		$jsonResp['status'] = 0;
		$jsonResp['msgs'] = [];

		try {
			//Intento obtener todos los posibles intereses de usuario
			//Si todo fue correcto y no se lanzó ninguna excepción, recibiré un array de objetos
			$data = Category::getAll();

			if($data) {
				//Respuesta en caso de success
				$jsonResp['data'] = $data;
			} else {
				//Respuesta en caso de error
				$jsonResp['status'] = -1;
				$jsonResp['msgs'] = [
					'error' => 'Error al obtener datos de la tabla "categorías".'
				];
			}
		} catch (Exception $e) {
			//Respuesta en caso de Exception
			$jsonResp['status'] = -1;
			$jsonResp['msgs'] = [
				'error' => 'Se han detectado problemas de conexión a la base de datos. Por favor, inténtelo nuevamente.'
			];	
		}

		View::renderJson($jsonResp);
	}


}