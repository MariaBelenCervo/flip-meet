<?php
namespace Controllers;

use Models\User;
use Models\Interest;
use Auth\Auth;
use Core\View;
use Exception;

use Utils\ResponseFormatter;
use Validation\Validator;

/**
 * Clase que se encargará de manejar los métodos de clases correspondientes al usuario
 * y devolverá las respuestas pertinentes
 */
class UsersController
{
	/**
	 * Intenta editar un usuario
	 * @param object $userData
	 * @return JSON
	 */
	public function edit($userData)
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
				// Intento obtener el usuario que se quiere editar
				// Si todo fue correcto y no se lanzó ninguna excepción, recibiré un objeto User
				$user = User::getById($userData->id);
				/* El método $user->update($userData) espera recibir un array, por lo que tengo que castear
				el objeto recibido del front-end */
				$userData = (array) $userData;
				// Intento obtener el usuario pedido
				// Si todo fue correcto y no se lanzó ninguna excepción, recibiré TRUE
				$resp = $user->update($userData);

				if(!$resp) {
					// Respuesta en caso de error
					$jsonResp['status'] = -1;
					$jsonResp['msgs'] = [
						'error' => 'Los datos no han sido modificados. Por favor, verifique los campos ingresados.'
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

	/**
	 * Devuelve un array de atributos de User y los mensajes correspondientes de success o error
	 * @param int $user - ID del user a retornar
	 * @return JSON
	 */
	public function get($user)
	{
		$jsonResp['status'] = 0;
		$jsonResp['msgs'] = [];

		try {
			// Intento obtener el usuario pedido
			// Si todo fue correcto y no se lanzó ninguna excepción, recibiré un objeto User
			$user = User::getById($user->id);

			if($user) {
				// Respuesta en caso de success
				$jsonResp['data'] = [
					'id' => $user->id,
					'name' => $user->name,
					'lastname' => $user->lastname,
					'location' => $user->location,
					'birthday' => $user->birthday,
					'fkinterest' => $user->fkinterest,
					'interest' => $user->interest
				];
			} else {
				// Respuesta en caso de error
				$jsonResp['status'] = -1;
				$jsonResp['msgs'] = [
					'error' => 'Error al crear el registro. Por favor, inténtelo nuevamente.'
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

	/**
	 * Intenta crear un nuevo usuario
	 * @param object $userData
	 * @return JSON
	 */
	public function add($userData)
	{
		// Creo respuesta con status OK inicialmente
		$resp = new ResponseFormatter();

		try {
	        $data = (array) $userData;

	        $validator = new Validator($data, [
	            'name' => ['max:75'],
	            'lastname' => ['max:75'],
	            'email' => ['required', 'email'],
	            'password' => ['required', 'min:5', 'max:16'],
	            'location' => ['min:4'],
	            'birthday' => ['required']
	        ]);

	        if ($validator->passes()) {
	       		// Verifico que no exista ese email
	        	if (User::getByEmail($data['email'])) {
					$resp->status = 3;
					$resp->message = 'Ya existe un usuario con ese email';
					$resp->oldInputs = $data;
	        	} else {
		            $user = User::create($data);
		            //$token = Auth::createToken($user->email);
		            //$resp->token = $token;
		            $resp->message = 'Usuario creado exitosamente';
	        	}
	        } else {
				$resp->status = ResponseFormatter::ERROR;
				$resp->validationErrors = $validator->getErrors();
				
				// Reenvío los datos al form pero si la contraseña
				unset($data['password']);
				$resp->oldInputs = $data;
	        }
	    
		} catch (Exception $e) {
		    $resp->message = $e->getMessage();
		    $resp->status = ResponseFormatter::ERROR;
		}

		// Envío el paquete formateado a View para que lo renderice
		View::renderJson($resp);
	}





//////////////////////VIEJO//////////////////////

/**
 * Intenta crear un nuevo usuario
 * @param object $userData
 * @return JSON
 */
/*public function add($userData)
{
	$jsonResp['status'] = 0;
	$jsonResp['msgs'] = [];

	try {
		/* El método User::create($data) espera recibir un array, por lo que tengo que castear el objeto
		recibido del front-end */
/*			$userData = (array) $userData;

		// Intento crear un nuevo usuario
		// Si todo fue correcto y no se lanzó ninguna excepción, recibiré un objeto User
		$user = User::create($userData);

		// Una vez creado el nuevo usuario, creo un token con el que podrá entrar a la app directamente
		// (esto evita que deba pasar por el login y reingresar sus datos antes de entrar a la app)
		$token = Auth::createToken($user->email);

		if($user) {
			// Respuesta en caso de success
			$jsonResp['data'] = [
				'token' => $token,
				'id' => $user->id,
				'email' => $user->email
			];
		} else {
			// Respuesta en caso de error
			$jsonResp['status'] = -1;
			$jsonResp['msgs'] = [
				'error' => 'Error al crear el registro. Por favor, inténtelo nuevamente.'
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
}*/
	
}



