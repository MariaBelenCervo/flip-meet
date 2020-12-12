/**
 * Servicio que realiza las peticiones al controller LogController de la api back end.
 */
 angular.module('FlipMeet.services')
.factory('Auth', [
	'$http',
	'API_MVC',
	'Storage',
	function($http, API_MVC, Storage) {
		/** @var {string/null} token - Inicializo la variable token */
		let token = null;
		/** @var {object} userData - Inicializo el object userData */
		let userData = {
			id: null
			//name: null,
			//lastname: null,
			//email: null,
			//password: null,
			
			//birthday: null,
			//fkinterest: null
		};

		//Primero que nada, verifico los datos del localStorage
		/*Eso me servirá para, cuando el usuario entre a la app, loguearlo sin necesidad
		de que llene nuevamente el formulario de login*/
		/*Lo hago con una IIFE ya que como esta función sólo necesita ser invocada una vez, en este servicio e
		inmediatamente luego de su creación, no se necesita asignarle un nombre a la función (sólo estaría ocupando
		el global namespace e incrementando la posibilidad de colisiones entre identificadores sin necesidad)*/
		/**
		 * @function - IIFE
		 * Trata de loguear al usuario con datos almacenados en el localStorage
		 */
		(function () {
		    if(Storage.has('token')) {
				token = Storage.get('token');
				userData = Storage.get('userData');
			}
		})();

		/**
		 * @function isLogged
		 * Informa si el usuario está logueado
		 * @return {boolean}
		 */
		let isLogged = function() {
			return token !== null;
		};

		/**
		 * @function getToken
		 * Retorna el token de autenticación
		 * @return string|null
		 */
		let getToken = function() {
			return token;
		};

		/**
		 * @function getUserData
		 * Retorna la info del usuario autenticado
		 * @return {{}}
		 */
		let getUserData = function() {
			return {
				id: userData.id
				//name: userData.name,
				//lastname: userData.lastname,
				//email: userData.email,
				//password: userData.password,
				//location: userData.location,
				//birthday: userData.birthday,
				//fkinterest: userData.fkinterest
			};
		};

		/**
		 * @function login
		 * Intenta loguear al usuario
		 * @param {{}} data
		 * @return {Promise}
		 */
		let login = function(data) {
			return $http.post(API_MVC + "/login", data).then(
				function(rta) {
					let response = rta.data;
					if(response.status == 0) {
						token = response.data.token;
						userData = {
							id: response.data.id
							//name: response.data.name,
							//lastname: response.data.lastname,
							//email: response.data.email,
							//password: response.data.password,
							//location: response.data.location,
							//birthday: response.data.birthday,
							//fkinterest: response.data.fkinterest
						};
						Storage.set('token', token);
						Storage.set('userData', userData);

						return {
							success: true
						};
					} else {
						return {
							success: false,
							error: response.msgs.error
						};
					}
				}
			);
		};

		/**
		 * @function logout
		 * Cierra la sesión.
		 */
		let logout = function() {
			Storage.remove('token');
			Storage.remove('userData');
			token = null;
			userData = {
				id: null
				//name: null,
				//lastname: null,
				//email: null,
				//password: null,
				//location: null,
				//birthday: null,
				//fkinterest: null
			};
		};

		//Retorno los valores obtenidos por las funciones del servicio para que tengan visibilidad
		return {
			login		: login,
			logout		: logout,
			isLogged	: isLogged,
			getToken	: getToken,
			getUserData	: getUserData
		};
	}
]);