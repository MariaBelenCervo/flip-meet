<?php
namespace Controllers;

use Auth\Log;
use Core\View;

/**
 * Clase que se encargará de manejar los métodos de clases correspondientes al logueo de usuarios
 * y devolverá las respuestas pertinentes
 */
class LogController
{
	/**
	 * Devuelve la respuesta y los datos correspondientes luego del intento de login
	 * @param object $inputs
	 * @return JSON
	 */
	public function login($inputs)
	{
		$jsonResp['status'] = 0;
		$jsonResp['msgs'] = [];

		try {
			if(isset($inputs->email) && isset($inputs->password)) {
				// Intento realizar el login
				$data = Log::login($inputs->email, $inputs->password);

				// Si todo fue correcto y no se lanzó ninguna excepción, recibiré un token y un objeto usuario
				$token = $data['token'];
				$user = $data['user'];

				if($user) {
					// Respuesta en caso de success
					$jsonResp['data'] = [
						'token' 	=> $token,
						'id' 		=> $user->id,
						'email' 	=> $user->email,
						'nombre' 	=> $user->name
					];
				} else {
					// Respuesta en caso de error
					$jsonResp['status'] = -1;
					$jsonResp['msgs'] = [
						'error' => 'Email y/o password incorrectos.'
					];
				}

			} else {
				$jsonResp['status'] = -1;
				$jsonResp['msgs'] = [
					'error' => 'Ambos campos son obligatorios.'
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