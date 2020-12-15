<?php
namespace Auth;

use Auth\Auth;
/**
 * Clase que logueará o no al usuario según la autenticación realizada en Auth.
 */
class Log
{
	/**
	 * Método que intenta loguear al usuario
	 * @param string $email
	 * @param mixed $pass
	 * @return array
	 */
	public static function login($email, $pass)
	{
		if($user = Auth::validateUser($email, $pass)) {
			//Si pasó la autenticación...
			//Creo el token
			$token = Auth::createToken($user->email);

			//Devuelvo un array con los datos del usuario autenticado: su token y sus propiedades como User
			return ['token' => $token, 'user' => $user];
		}

		return ['token' => null, 'user' => null];
	}


}