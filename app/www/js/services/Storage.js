/**
 * Servicio que intercambia datos con el localStorage
 */
 angular.module('FlipMeet.services')
.factory('Storage', function() {
	return {
		/**
		 * @function set
		 * Agrega un item al Storage
		 * @param {string} key
		 * @param {*} value
		 */
		set: function(key, value) {
			localStorage.setItem(key, JSON.stringify(value));
		},

		/**
		 * @function get
		 * Obtiene un valor del Storage
		 * @param {string} key
		 * @return {*}
		 */
		get: function(key) {
			return JSON.parse(localStorage.getItem(key));
		},

		/**
		 * @function has
		 * Verifica si tiene la key en el Storage
		 * @param {string} key
		 * @return {boolean}
		 */ 
		has: function(key) {
			return localStorage.getItem(key) !== null;
		},

		/**
		 * @function remove
		 * Elimina un item del Storage
		 * @param {string} key
		 */
		remove: function(key) {
			localStorage.removeItem(key);
		}
	}
});