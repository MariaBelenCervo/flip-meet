<?php
namespace Controllers;

use Models\Interest;
use Core\View;

/**
 * Clase que se encargará de manejar los métodos de la clase Interest
 * y devolverá las respuestas pertinentes
 */
class InterestsController
{
	/**
	 * Devuelve un array de objetos Interest y los mensajes correspondientes de success o error
	 * @return JSON
	 */
	public function getAll()
	{
		$jsonResp['status'] = 0;
		$jsonResp['msgs'] = [];

		try {
			// Intento obtener todos los posibles intereses de usuario
			// Si todo fue correcto y no se lanzó ninguna excepción, recibiré un array de objetos Interest
			$data = Interest::getAll();

			if($data) {
				// Respuesta en caso de success
				$jsonResp['data'] = $data;
			} else {
				// Respuesta en caso de error
				$jsonResp['status'] = -1;
				$jsonResp['msgs'] = [
					'error' => 'Error al obtener datos de la tabla "intereses".'
				];
			}
		} catch (Exception $e) {
			// Respuesta en caso de Exception
			$jsonResp['status'] = -1;
			$jsonResp['msgs'] = [
				'error' => 'Se han detectado problemas de conexión a la base de datos. Por favor, inténtelo nuevamente.'
			];	
		}

		View::renderJson($jsonResp);
	}


}