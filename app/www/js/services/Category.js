/**
 * Servicio que realiza las peticiones al controller CategoriesController de la api back end.
 */
 angular.module('FlipMeet.services')
.factory('Category', [
	'$http',
	'API_MVC',
	function($http, API_MVC) {
		
		return {
			/**
			 * @function getAll
			 * Realiza la petición de todas las categorías de publicaciones
			 * @return JSON
			 */
			getAll: function() {
				return $http.get(API_MVC + '/categories')
					.then(function(rta) {
						let response = rta.data;

						if(response.status == 0) {
							let categories = response.data;
							return JSON.parse(JSON.stringify(categories));
						}
					});
			}

		}
	}
]);