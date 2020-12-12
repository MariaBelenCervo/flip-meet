/**
 * Servicio que realiza las peticiones al controller CommentsController de la api back end.
 */
 angular.module('FlipMeet.services')
.factory('Interest', [
	'$http',
	'API_MVC',
	function($http, API_MVC) {
		
		return {
			/**
			 * @function getAll
			 * Realiza la petición de todos los posibles intereses de los usuarios
			 * @return JSON
			 */
			getAll: function() {
				return $http.get(API_MVC + '/interests')
					.then(function(rta) {
						let response = rta.data;

						if(response.status == 0) {
							let interests = response.data;
							return JSON.parse(JSON.stringify(interests));
						}
					});
			}
		}
	}
]);