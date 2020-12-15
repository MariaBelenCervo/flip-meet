<?php
namespace Controllers;

use Models\User;
use Models\Interest;
use Auth\Auth;
use Exception;
use Exceptions\ConflictHttpException;
use Exceptions\UnprocessableEntityHttpException;
use Exceptions\NotFoundHttpException;
use Exceptions\ForbiddenHttpException;
use Exceptions\HttpException;
use Responses\HttpAckResponse;
use Responses\HttpDataResponse;
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
	 */
	public function edit($userData, $data)
	{
		// Email autenticado via token
		$authEmail = $data['email'];

		// No se puede editar el email, así que lo elimino de userData en caso de que esté
		unset($userData->email);

		$data = (array) $userData;
		$validator = new Validator($data, User::editableRules());

		if (!$validator->passes()) {
			throw new UnprocessableEntityHttpException($validator->getErrors());
		}

		// Intento obtener el usuario que se quiere editar
		// Si todo fue correcto y no se lanzó ninguna excepción, recibiré un objeto User
		$user = User::getById($userData->id);

		// Verifico que el usuario autenticado se corresponda con el usuario que se quiere editar
		if ($authEmail != $user->email) {
			throw new ForbiddenHttpException("No tiene permisos para editar este usuario");
		}

		/* El método $user->update($userData) espera recibir un array, por lo que tengo que castear
		el objeto recibido del front-end */
		$userData = (array) $userData;
		// Intento actualizar el usuario pedido
		// Si todo fue correcto y no se lanzó ninguna excepción, recibiré TRUE
		if (!$user->update($userData)) {
			throw new HttpException(500, "Los datos no han sido modificados. Por favor, verifique los campos ingresados.");
		} 

		(new HttpAckResponse("Usuario modificado exitosamente"))->send();
	}

	/**
	 * Devuelve un array de atributos de User y los mensajes correspondientes de success o error
	 * @param int $user - ID del user a retornar
	 * @return JSON
	 */
	public function get($user)
	{
		// Intento obtener el usuario pedido
		// Si todo fue correcto y no se lanzó ninguna excepción, recibiré un objeto User
		$user = User::getById($user->id);

		if($user === null) {
			throw new NotFoundHttpException("Usuario inexistente.");
		}

		// Respuesta en caso de success
		$data = (object) [
			'id' => $user->id,
			'name' => $user->name,
			'lastname' => $user->lastname,
			'location' => $user->location,
			'birthday' => $user->birthday,
			'fkinterest' => $user->fkinterest,
			'interest' => $user->interest
		];

		(new HttpDataResponse($data))->send();
	}

	/**
	 * Intenta crear un nuevo usuario
	 * @param object $userData
	 */
	public function add($userData)
	{
		$data = (array) $userData;
		$validator = new Validator($data, User::$rules);
	
		if (!$validator->passes()) {
			throw new UnprocessableEntityHttpException($validator->getErrors());
		}

		// Verifico que no exista ese email
		if (User::getByEmail($data['email'])) {
			throw new ConflictHttpException("Ya existe un usuario con ese email", "Usuario existente");
		} 

		User::create($data);
		
		(new HttpAckResponse("Usuario creado exitosamente"))->send();
	}
	
}



