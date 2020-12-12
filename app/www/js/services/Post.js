/**
 * Servicio que realiza las peticiones al controller PostsController de la api back end.
 */
 angular.module('FlipMeet.services')
.factory('Post', [
	'$http',
	'API_MVC',
	'Auth',
	function($http, API_MVC, Auth) {
		return {
			/**
			 * @function getAll
			 * Realiza la petición de todas las publicaciones de los usuarios
			 * @return JSON
			 */
			getAll: function() {
				return $http.get(API_MVC + '/posts')
					.then(function(rta) {
						let response = rta.data;
						if(response.status == 0) {
							let posts = response.data;
							return JSON.parse(JSON.stringify(posts));
						}
					});
			},

			/**
			 * @function get
			 * @param {int} id
			 * Realiza la petición de una publicación específica
			 * @return {{}}
			 */
			get: function(id) {
					return $http.get(API_MVC + '/posts/' + id)
						.then(function(response) {
							return {
								success: true,
								postData: response.data
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
			 * @function add
			 * Realiza la petición de agregado de una nueva publicación
			 * @param {object} post
			 * @return {{}}
			 */
			add: function(post) {
					return $http.post(API_MVC + '/posts', post, {
						headers: {
							'X-Token': Auth.getToken()
						}
					}).then(function(rta) {
							let response = rta.data;
							if (response.status == 0) {
								return {
									success: true,
									category: response.data.category
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
			},

			/**
			 * @function getComments
			 * @param {int} id
			 * Realiza la petición de todos los comentarios para una publicación específica
			 * @return JSON
			 */
			getComments: function(id) {
					return $http.get(API_MVC + '/posts/' + id + '/comments')
						.then(function(rta) {
							let response = rta.data;
							if(response.status == 0) {
								let comments = response.data;
								return JSON.parse(JSON.stringify(comments));
							}
						});
			}

		}
	}
]);