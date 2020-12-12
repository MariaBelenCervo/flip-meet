/**
 * Controller que maneja la vista register.html y que procesa los datos necesarios para
 * al alta de un nuevo usuario
 */
angular.module('FlipMeet.controllers')
.controller('RegisterCtrl', [
	'$scope',
	'$state',
	'$ionicPopup',
	'User',
	'Interest',
	function($scope, $state, $ionicPopup, User, Interest) {
		/** @var {object} user - Inicializo el object user */
		$scope.user = {
			name: null,
			lastname: null,
			email: null,
			password: null,
			location: null,
			birthday: null,
			fkinterest: null
		};
		
		/** @var {array} interests - Inicializo el array interests */
		$scope.interests = [];

		/**
		 * @function getAll - Ejecuta la función getAll() del servicio Interest
		 * @param {object} data
		 * @return {object} interests
		 */
		Interest.getAll().then(function(data) {
			$scope.interests = data;
		});

		/** @var {object} validationErrors - Inicializo el object validationErrors */
		$scope.validationErrors = {
			name: false,
			lastname: false,
			email: false,
			password: false,
			location: false,
			birthday: false,
			fkinterest: false
		}

		/**
		 * @function add - Ejecuta la función create() del servicio User
		 * @param {object} user
		 */
		$scope.add = function(user) {
			User.create(user)
				.then(function(response) {
					if(response.success == 0) {
						// Seteo los errores de validación en false
						$scope.validationErrors = false;

						// Seteo los oldInputs en false
						$scope.oldInputs = false;

						// Muestro el mensaje de éxito
						$ionicPopup.alert({
							title: '¡Éxito!',
							template: 'El usuario se ha registrado exitosamente',
							okText: 'Aceptar'
						}).then(function() {
							$state.go('login');
						});
					} else if(response.success == 3) {
						$ionicPopup.alert({
								title: 'Error',
								template: response.errorMsg,
								okText: 'Aceptar'
						});
					} else {
						// Rescato los errores de validación
						for (let key in $scope.validationErrors) {
							if(response[key] !== undefined) {
								$scope.validationErrors[key] = response[key][0];
							}
						}
					}
				});
		};
	}
]);