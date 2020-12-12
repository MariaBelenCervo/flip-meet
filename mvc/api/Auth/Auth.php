<?php
namespace Auth;

use Models\User;
use Security\Hash;
use Exception;

require '../api/vendor/autoload.php';
// Librería de terceros a utilizar
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\ValidationData;
use Lcobucci\JWT\Signer\Hmac\Sha256;

/**
 * Clase que se encargará las de funcionalidades de autenticación
 * (creación y verificación de tokens, y autenticación del email y passowrd del usuario)
 */
class Auth
{
	// Defino la key de encriptación
	const API_JWT_KEY = 'estaesmikeydeencriptación';
	// Defino el issuer
	const ISSUER = 'http://localhost/FlipMeet';

	/**
	 * Genera un token para el usuario con el email indicado
	 * @param string $email
	 * @return string
	 */
	public static function createToken(string $email) : string
	{
		// Instancio el algoritmo de encriptación
		$alg = new Sha256();
		
		// Creo el constructor del token ("Builder")
		$token = (new Builder())
			// Seteo el "issuer" (emisor del token)
			->setIssuer(self::ISSUER)
			// Seteo la fecha de emisión
			->setIssuedAt(time())
			// Configura el tiempo de expiración del token
			// ->setExpiration(time() + 3600)
			// Agrego los datos de esta aplicación. Ej: el email del usuario logueado
			->set('email', $email)
			// Firmo el token (primero paso el algoritmo y luego la key secreta)
			->sign($alg, self::API_JWT_KEY)
			// Obtengo el token propiamente dicho
			->getToken();

		// Retorno el token como string
		return (string) $token;
	}

	/**
	 * Valida el token para un usuario específico
	 * @param string $token
	 * @return array|FALSE
	 */
	public static function validateToken(string $token)
	{
		try {
			$token = (new Parser())->parse($token);

			// Defino los criterios de validación del token
			$validationData = new ValidationData;
			$validationData->setIssuer(self::ISSUER);

			if(!$token->validate($validationData)) {
				throw new Exception;
			}

			// Verifico la firma del token, para saber que no fue adulterado
			$alg = new Sha256;

			if(!$token->verify($alg, self::API_JWT_KEY)) {
				throw new Exception;
			}

			return [
				'email' => $token->getClaim('email')
			];
		} catch(Exception $e) {
			return false;
		}
	}

	/**
	 * Método que valida el email y contraseña del usuario
	 * @param string $email
	 * @param mixed $password
	 * @return User|null
	 */
	public static function validateUser(string $email, $password)
	{
		// Obtengo el usuario a partir de su email
		// Si no existe un usuario con ese email me devolverá null
		$user = User::getByEmail($email);

		if($user !== null) {
			// Verifico que el password coincida con la clave hasheada de la DB
			if(Hash::verify($password, $user->password)) {
				// Si la autenticación fue exitosa, devuelvo el objeto $user obtenido
				return $user;
			}
		}
		// Si la autenticación falló, devuelvo null
		return null;
	}


}