/**
 * Controller que maneja la vista login.html y que procesa los datos necesarios para
 * el logueo de usuarios
 */
angular.module('FlipMeet.controllers')
.controller('LoginCtrl', [
	'$scope',
	'$state',
	'$ionicPopup',
	'Auth',
	'Storage',
	function($scope, $state, $ionicPopup, Auth, Storage) {
		/** @var {object} user - Inicializo el object user */
		$scope.user = {
			email: null,
			password: null
		};
		
		/**
		 * @function login - Ejecuta la función login() del servicio Auth
		 * @param {object} user
		 */
		$scope.login = function(user) {
			Auth.login(user).then(
				function(response) {
					if(response.success) {
						$state.go('app.home');
					} else {
						$ionicPopup.alert({
							'title': 'Error',
							'template': response.error
						});
					}
				}
			);
		};

		/**
		 * @function logout - Ejecuta la función logout() del servicio Auth
		 */
		$scope.logout = function() {
			Auth.logout().then(
				function(response) {
					if(response.success) {
						$state.go('login');
					} else {
						$ionicPopup.alert({
							'title': 'Error',
							'template': response.error
						});
					}
				}
			);
		};
	}
]);