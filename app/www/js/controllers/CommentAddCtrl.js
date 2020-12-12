/**
 * Controller que maneja la vista comment_add.html y que procesa los datos necesarios para el agregado
 * de comentarios a la publicación correspondiente
 */
angular.module('FlipMeet.controllers')
.controller('CommentAddCtrl', [
	'$scope',
	'$state',
	'$stateParams',
	'$ionicPopup',
	'Comment',
	'Storage',
	function($scope, $state, $stateParams, $ionicPopup, Comment, Storage) {
		/** @var {object} comment - Inicializo el objeto comment */
		$scope.comment = {
			fkuser: null,
			fkpost: null,
			comment: null
		};

		// Obtengo el fkuser del localStorage
		$scope.comment.fkuser = Storage.get('userData').id;

		// Obtengo el fkpost del servicio $stateParams
		$scope.comment.fkpost = $stateParams.id;
		
		/**
		 * @function add - Ejecuta la función add() del servicio Comment
		 * @param {object} comment
		 */
		$scope.add = function(comment) {
			Comment.add(comment)
				.then(function(response) {
					if(response.success) {
						$ionicPopup.alert({
							title: '¡Éxito!',
							template: 'El comentario ha sido agregado exitosamente',
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