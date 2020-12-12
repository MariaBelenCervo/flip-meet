/**
 * Controller que maneja la vista post_add.html y que procesa los datos necesarios para
 * la creación de publicaciones
 */
angular.module('FlipMeet.controllers')
.controller('PostAddCtrl', [
	'$scope',
	'$state',
	'$ionicPopup',
	'Post',
	'Category',
	'Storage',
	function($scope, $state, $ionicPopup, Post, Category, Storage) {
		/** @var {object} post - Inicializo el object post */
		$scope.post = {
			title: null,
			location: null,
			description: null,
			fkuser: null,
			fkcategory: null,
			category: null
		};

		// Obtengo el fkuser del localStorage
		$scope.post.fkuser = Storage.get('userData').id;
		
		/** @var {array} categories - Inicializo el array categories */
		$scope.categories = [];

		/**
		 * @function getAll - Ejecuta la función getAll() del servicio Category
		 * @param {object} data
		 * @return {array} categories
		 */
		Category.getAll().then(function(data) {
			$scope.categories = data;
		});

		/**
		 * @function add - Ejecuta la función add() del servicio Post
		 * @param {object} post
		 */
		$scope.add = function(post) {
			Post.add(post)
				.then(function(response) {
					if(response.success) {
						$ionicPopup.alert({
							title: '¡Éxito!',
							template: 'El ' + response.category + ' ha sido agregado exitosamente',
							okText: 'Aceptar'
						}).then(function() {
							$state.go('app.home');
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