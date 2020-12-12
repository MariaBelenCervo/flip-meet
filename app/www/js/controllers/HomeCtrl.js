/**
 * Controller que maneja la vista home.html y que procesa los datos necesarios para la muestra
 * de publicaciones
 */
angular.module('FlipMeet.controllers')
.controller('HomeCtrl', [
	'$scope',
	'$state',
	'$ionicPopup',
	'$ionicLoading',
	'API',
	'Post',
	function($scope, $state, $ionicPopup, $ionicLoading, API, Post) {
		/** @var {array} posts - Inicializo el array posts */
		$scope.posts = [];

		/** @var {string} imgRoute - Contiene la ruta a los recursos de imagen */
		$scope.imgRoute = API + "/img/";

		/**
		 * Event listener que trae todas las publicaciones antes de entrar a la vista
		 */
		$scope.$on('$ionicView.beforeEnter', function() {
			$ionicLoading.show();
			Post.getAll()
				.then(function(data) {
						$scope.posts = data;
						$ionicLoading.hide();
				});
		});
		
	}
]);