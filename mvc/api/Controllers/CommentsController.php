<?php
namespace Controllers;

use Auth\Auth;
use Models\Comment;
use Core\View;
use Exception;

/**
 * Clase que se encargará de manejar los métodos de la clase Comment
 * y devolverá las respuestas pertinentes
 */
class CommentsController
{
	/*public function getAll($data)
	{
		var_dump($data);	
	}*/

	/**
	 * Intenta crear un nuevo comentario
	 * @param object $data
	 * @return JSON
	 */
	public function add($data)
	{
		$jsonResp['status'] = 0;
		$jsonResp['msgs'] = [];

		// Capturo el token
		$token = $_SERVER['HTTP_X_TOKEN'] ?? null;

		// Verifico que no sea nulo y que sea válido.
		if($token === null || !Auth::validateToken($token)) {
			// Respuesta en caso de no autenticación
			$jsonResp['status'] = -2;
			$jsonResp['msgs'] = [
				'error' => 'Debe iniciar sesión para realizar esta acción.'
			];
			
		} else {
			try {
				/* El método User::create($data) espera recibir un array, por lo que tengo que castear el objeto recibido del front-end */
				$data = (array) $data;

				// Intento crear un nuevo usuario
				// Si todo fue correcto y no se lanzó ninguna excepción, recibiré un objeto Comment
				$comment = Comment::create($data);

				if(!$comment) {
					// Respuesta en caso de error
					$jsonResp['status'] = -1;
					$jsonResp['msgs'] = [
						'error' => 'Error al crear el comentario. Por favor, inténtelo nuevamente.'
					];
				}
			} catch (Exception $e) {
				// Respuesta en caso de Exception
				$jsonResp['status'] = -1;
				$jsonResp['msgs'] = [
					'error' => 'Se han detectado problemas de conexión a la base de datos. Por favor, inténtelo nuevamente.'
				];
			}
		}

		View::renderJson($jsonResp);
	}

	/*public function delete($data)
	{
		var_dump($data);	
	}*/

	/*public function edit($data)
	{
		var_dump($data);	
	}*/
}



