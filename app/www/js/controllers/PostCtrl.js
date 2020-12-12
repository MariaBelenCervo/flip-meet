/**
 * Controller que maneja la vista post.html y que procesa los datos necesarios para
 * la muestra de una publicación específica con sus comentarios
 */
angular.module('FlipMeet.controllers')
.controller('PostCtrl', [
	'$scope',
	'$state',
	'$stateParams',
	'$ionicPopup',
	'$ionicLoading',
	'API',
	'Post',
	function($scope, $state, $stateParams, $ionicPopup, $ionicLoading, API, Post) {
		/** @var {object} post - Inicializo el object post */
		$scope.post = {
			id: null,
			title: null,
			description: null,
			category: null,
			location: null
		};

		// Obtengo el id del servicio $stateParams
		$scope.post.id = $stateParams.id;

		/** @var {string} imgRoute - Contiene la ruta a los recursos de imagen */
		$scope.imgRoute = API + "/img/";

		/**
		 * Event listener que trae la publicación antes de entrar a la vista
		 */
		$scope.$on('$ionicView.beforeEnter', function() {
			Post.get($scope.post.id)
				.then(function(response) {
					$scope.post = response.postData.data;
				});
		});

		/** @var {array} comments - Inicializo el array comments */
		$scope.comments = [];

		/**
		 * Event listener que trae los comentarios de la publicación antes de entrar a la vista
		 */
		$scope.$on('$ionicView.beforeEnter', function() {
			Post.getComments($scope.post.id)
				.then(function(data) {
					$scope.comments = data;
				});
		});

	}
]);