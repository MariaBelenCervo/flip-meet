/**
 * Controller que maneja la vista profile.html y que procesa los datos necesarios para
 * la visualización del perfil del usuario
 */
angular.module('FlipMeet.controllers')
.controller('ProfileCtrl', [
	'$scope',
	'$state',
	'$ionicPopup',
	'API',
	'Storage',
	'Interest',
	'User',
	function($scope, $state, $ionicPopup, API, Storage, Interest, User) {
		/** @var {object} user - Inicializo el object user */
		$scope.user = {
			id: null,
			name: null,
			lastname: null,
			location: null,
			birthday: null,
			fkinterest: null,
			interest: null
		};

		/** @var {string} imgRoute - Contiene la ruta a los recursos de imagen */
		$scope.imgRoute = API + "/img/";

		// Obtengo el user ID del localStorage
		$scope.user.id = Storage.get('userData').id;

		/**
		 * Event listener que trae los datos del usuario antes de entrar a la vista
		 */
		$scope.$on('$ionicView.beforeEnter', function() {
			User.get($scope.user.id)
				.then(function(response) {
					$scope.user = response.userData.data;
				});
		});
	}
]);