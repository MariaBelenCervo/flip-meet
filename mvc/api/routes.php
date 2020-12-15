<?php
/*Este archivo incluirá todas las rutas con las que voy a trabajar a lo largo de la aplicación.
Cualquier ruta que no se encuentre definida en este documento lanzará un error que será manejado
como corresponda por el front-end*/

use Core\Route;
use Middlewares\AuthMiddleware;

//Agrego las rutas con las que voy a trabajar a lo largo de toda la aplicación
/*
1. Método (GET, POST, PUT, DELETE)
2. URL (indicará el modelo con el que se quiere trabajar)
3. El método y el Controller que se va a encargar de manejar esa petición.
	Para indicar esto último, voy a seguir la nomenclatura de Laravel:
	NombreController@nombreMétodo
*/

Route::add('POST', 'api/login', 'LogController@login');

Route::add('GET', 'api/users/{id}', 'UsersController@get', AuthMiddleware::class);
Route::add('POST', 'api/users', 'UsersController@add');
Route::add('PUT', 'api/users/{id}', 'UsersController@edit', AuthMiddleware::class);

Route::add('GET', 'api/posts', 'PostsController@getAll');
Route::add('GET', 'api/posts/{id}', 'PostsController@get');
Route::add('GET', 'api/posts/{id}/comments', 'PostsController@getComments');
Route::add('POST', 'api/posts', 'PostsController@add');
//Route::add('DELETE', 'api/posts/{id}', 'PostsController@delete');

//Route::add('GET', 'api/comments', 'CommentsController@getAll');
Route::add('POST', 'api/comments', 'CommentsController@add');
//Route::add('DELETE', 'api/comments/{id}', 'CommentsController@delete');
//Route::add('PUT', 'api/comments/{id}', 'CommentsController@edit');

Route::add('GET', 'api/interests', 'InterestsController@getAll');

Route::add('GET', 'api/categories', 'CategoriesController@getAll');