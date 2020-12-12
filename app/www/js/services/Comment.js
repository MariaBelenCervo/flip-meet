/**
 * Servicio que realiza las peticiones al controller CommentsController de la api back end.
 */
 angular.module('FlipMeet.services')
.factory('Comment', [
	'$http',
	'API_MVC',
	'Auth',
	function($http, API_MVC, Auth) {
		
		return {
			/**
			 * @function add
			 * Realiza la petición de agregado de un nuevo comentario
			 * @param {object} comment
			 * @return {{}}
			 */
			add: function(comment) {
					return $http.post(API_MVC + '/comments', comment, {
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