/**
 * Controller que maneja la vista profile_edit.html y que procesa los datos necesarios para
 * la edición de los datos del usuario
 */
angular.module('FlipMeet.controllers')
.controller('ProfileEditionCtrl', [
	'$scope',
	'$state',
	'$stateParams',
	'$ionicPopup',
	'API',
	'Storage',
	'Interest',
	'User',
	function($scope, $state, $stateParams, $ionicPopup, API, Storage, Interest, User) {
		/** @var {object} user - Inicializo el object user */
		$scope.user = {
			id: null,
			name: null,
			lastname: null,
			location: null,
			birthday: null,
			fkinterest: null
		};

		/** @var {string} imgRoute - Contiene la ruta a los recursos de imagen */
		$scope.imgRoute = API + "/img/";

		// Obtengo el user ID del servicio $stateParams
		$scope.user.id = $stateParams.id;

		/**
		 * @function get - Ejecuta la función get() del servicio User
		 * @param {int} id
		 * @return {object} user
		 */
		User.get($scope.user.id)
			.then(function(response) {
				$scope.user = response.userData.data;
			});

		/** @var {array} interests - Inicializo el array interests */
		$scope.interests = [];

		/**
		 * @function getAll - Ejecuta la función getAll() del servicio Interest
		 * @param {object} data
		 * @return {object} user
		 */
		Interest.getAll().then(function(data) {
			$scope.interests = data;
		});

		/**
		 * @function edit - Ejecuta la función update() del servicio User
		 * @param {object} user
		 */
		$scope.edit = function(user) {
			User.update(user)
				.then(function(response) {
					if(response.success) {
						$ionicPopup.alert({
							title: '¡Éxito!',
							template: 'Tu perfil ha sido editado exitosamente.',
							okText: 'Aceptar'
						}).then(function() {
							$state.go('app.profile');
						});
					} else {
						$ionicPopup.alert({
							title: 'Error',
							template: response.error,
							okText: 'Aceptar'
						}).then(function() {
							if(response.login) {
								$state.go('login');
							}
						});
					}
				});
		};

	}
]);