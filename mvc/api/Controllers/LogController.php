<?php
namespace Controllers;

use Auth\Log;
use Exceptions\UnprocessableEntityHttpException;
use Exceptions\UnauthorizedHttpException;
use Responses\HttpDataResponse;
use Validation\Validator;

/**
 * Clase que se encargará de manejar los métodos de clases correspondientes al logueo de usuarios
 * y devolverá las respuestas pertinentes
 */
class LogController
{
	/**
	 * Devuelve la respuesta y los datos correspondientes luego del intento de login
	 * @param object $inputs
	 */
	public function login($inputs)
	{
		$data = (array) $inputs;
		$validator = new Validator($data, [
			'email' => ['required', 'email'],
			'password' => ['required']
		]);
	
		if (!$validator->passes()) {
			throw new UnprocessableEntityHttpException($validator->getErrors());
		}

		// Intento realizar el login
		$data = Log::login($inputs->email, $inputs->password);

		// Si todo fue correcto y no se lanzó ninguna excepción, recibiré un token y un objeto usuario
		$token = $data['token'];
		$user = $data['user'];

		if ($user === null) {
			throw new UnauthorizedHttpException("Email y/o password incorrectos.", "Falló login");
		} 

		// Respuesta en caso de success
		$resp = (object) [
			'token' => $token,
			'id' => $user->id,
			'email' => $user->email,
			'nombre' => $user->name
		];
		
		(new HttpDataResponse($resp))->send();

	}

}