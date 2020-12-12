<?php
namespace Controllers;

use Auth\Auth;
use Models\Post;
use Models\Comment;
use Models\Category;
use Core\View;
use Exception;

/**
 * Clase que se encargará de manejar los métodos de la clase Post
 * y devolverá las respuestas pertinentes
 */
class PostsController
{
	/**
	 * Devuelve un array de objetos Post y los mensajes correspondientes de success o error
	 * @return JSON
	 */
	public function getAll()
	{
		$jsonResp['status'] = 0;
		$jsonResp['msgs'] = [];

		try {
			// Intento obtener todos los posts
			// Si todo fue correcto y no se lanzó ninguna excepción, recibiré un array de objetos Post
			$posts = Post::getAll();

			if($posts) {
				// Respuesta en caso de success
				$jsonResp['data'] = $posts;
			} else {
				// Respuesta en caso de error
				$jsonResp['status'] = -1;
				$jsonResp['msgs'] = [
					'error' => 'Error al obtener datos de la tabla "publicaciones".'
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
	 * Devuelve un array de atributos de Post y los mensajes correspondientes de success o error
	 * @param int $post - ID del post a retornar
	 * @return JSON
	 */
	public function get($post)
	{
		$jsonResp['status'] = 0;
		$jsonResp['msgs'] = [];
		
		try {
			// Intento obtener el usuario pedido
			// Si todo fue correcto y no se lanzó ninguna excepción, recibiré un objeto Post
			$post = Post::getById($post->id);

			if($post) {
				// Respuesta en caso de success
				$jsonResp['data'] = [
					'id' => $post->id,
					'title' => $post->title,
					'category' => $post->category,
					'location' => $post->location,
					'description' => $post->description
				];
			} else {
				// Respuesta en caso de error
				$jsonResp['status'] = -1;
				$jsonResp['msgs'] = [
					'error' => 'Error al obtener el registro. Por favor, inténtelo nuevamente.'
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
	 * Devuelve un array de comentarios del Post actual y los mensajes correspondientes de success o error
	 * @param int $post - ID del post cuyos comentarios de quieren retornar
	 * @return JSON
	 */
	public function getComments($post)
	{
		$jsonResp['status'] = 0;
		$jsonResp['msgs'] = [];
		
		try {
			// Como el id del post está dado por $stateParams llega en forma de string
			// Lo parseo a int
			$postID = (int) $post->id;
			// Intento obtener el usuario pedido
			// Si todo fue correcto y no se lanzó ninguna excepción, recibiré al menos un objeto Comment
			$comments = Comment::getByAttribute('fkpost', $postID);

			if($comments) {
				// Respuesta en caso de success
				$jsonResp['data'] = $comments;
			} else {
				// Respuesta en caso de error
				$jsonResp['status'] = -1;
				$jsonResp['msgs'] = [
					'error' => 'Error al obtener el registro. Por favor, inténtelo nuevamente.'
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
	 * Intenta crear un nuevo post
	 * @param object $postData
	 * @return JSON
	 */
	public function add($postData)
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
				/* El método User::create($data) espera recibir un array, por lo que tengo que castear el
				objeto recibido del front-end*/
				$postData = (array) $postData;

				// Intento crear un nuevo usuario
				// Si todo fue correcto y no se lanzó ninguna excepción, recibiré un objeto Post
				$post = Post::create($postData);

				if($post) {
					// Respuesta en caso de success
					$jsonResp['data'] = [
						'category' => $post->category
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
		}

		View::renderJson($jsonResp);
	}

	/*public function delete($data)
	{
		var_dump($data);	
	}*/
}



