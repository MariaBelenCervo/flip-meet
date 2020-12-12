<?php
namespace Security;
/**
 * Utilizo una clase específica para manipular todo lo referido a la encriptación
 * En este caso utilizo las funciones de password_hash() y password_verify() por ser más seguras
 * que MD5 o SHA1
 */
class Hash
{
	/**
	 * Realiza la encriptación de la contraseña
	 * @param string $pass
	 * @return string
	 */
	public static function make(string $pass) : string
	{
		return password_hash($pass, PASSWORD_DEFAULT);
	}

	/**
	 * Compara el hash y el password para ver si se corresponden
	 * @param string $pass
	 * @param string $DBpass
	 * @return bool
	 */
	public static function verify(string $pass, string $DBpass) : bool
	{
		return password_verify($pass, $DBpass);
	}

	
}