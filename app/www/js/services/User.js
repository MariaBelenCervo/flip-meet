/**
 * Servicio que realiza las peticiones al controller UsersController de la api back end.
 */
 angular.module('FlipMeet.services')
.factory('User', [
	'$http',
	'API_MVC',
	'Storage',
	'Auth',
	function($http, API_MVC, Storage, Auth) {
		/** @var {object} user - Inicializo el object user */
		let user = {}

		return {
			/**
			 * @function create
			 * Realiza la petición de agregado de un nuevo usuario
			 * @param {object} user
			 * @return {{}}
			 */
			create: function(user) {
				return $http.post(API_MVC + '/users', user)
					.then(function(rta) {
						let response = rta.data;
						console.log(response);
						if(response.status == 1) {
							//token = response.data.token;
							/*userData = {
								id: response.data.id
								name: response.data.name,
								lastname: response.data.lastname,
								email: response.data.email,
								password: response.data.password,
								location: response.data.location,
								birthday: response.data.birthday,
								fkinterest: response.data.fkinterest
							};*/
							//Storage.set('token', token);
							//Storage.set('userData', userData);

							return {
								success: 0,
								msg: response.message
							}
						} else if (response.status == 3) {
							return {
								success: 3,
								errorMsg: response.message
							}
						} else {
							let validationErrors = response.validationErrors;
							return JSON.parse(JSON.stringify(validationErrors));
						}
					});
			},

			/**
			 * @function get
			 * @param {int} id
			 * Realiza la petición de un usuario específico
			 * @return {{}}
			 */
			get: function(id) {
					return $http.get(API_MVC + '/users/' + id)
						.then(function(response) {
							return {
								success: true,
								userData: response.data
							}
						},
						function(error) {
							return {
								success: false,
								error: response.msgs.error
							}
						});
			},

			/**
			 * @function update
			 * @param {object} user
			 * Realiza la petición para la edición de un usuario
			 * @return {{}}
			 */
			update: function(user) {
				return $http.put(API_MVC + '/users/' + user.id, user, {
						headers: {
							'X-Token': Auth.getToken()
						}
					}).then(function(rta) {
						let response = rta.data;
						if (response.status == 0) {
							return {
								success: true
							}
						} else if (response.status == -2) {
							return {
								success: false,
								error: response.msgs.error,
								login: true
							}
						} else {
							return {
								success: false,
								error: response.msgs.error
							}
						}
					});
			}

		}
	}
]);