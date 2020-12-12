// Ionic Starter App

// angular.module is a global place for creating, registering and retrieving Angular modules
// 'starter' is the name of this angular module example (also set in a <body> attribute in index.html)
// the 2nd parameter is an array of 'requires'
// 'starter.controllers' is found in controllers.js
angular.module('FlipMeet', ['ionic', 'FlipMeet.controllers', 'FlipMeet.services'])

.run(function($ionicPlatform, $ionicPopup, $rootScope, $state, Auth) {

  /**
   * Event Listener que evita que un usuario ingrese a la app si no está autenticado
   */
  $rootScope.$on('$ionicView.beforeEnter', function() {
      if($state.current.name != 'login' && $state.current.name != 'register' && !Auth.isLogged()) {
        $state.go('login');
      }
  });


  $ionicPlatform.ready(function() {
    // Hide the accessory bar by default (remove this to show the accessory bar above the keyboard
    // for form inputs)
    if (window.cordova && window.cordova.plugins && window.cordova.plugins.Keyboard) {
      cordova.plugins.Keyboard.hideKeyboardAccessoryBar(true);
      cordova.plugins.Keyboard.disableScroll(true);

    }
    if (window.StatusBar) {
      // org.apache.cordova.statusbar required
      StatusBar.styleDefault();
    }

    // Detecto el cambio de state.
    // El evento que uso es $stateChangeStart
    // https://github.com/angular-ui/ui-router/wiki#state-change-events
    $rootScope.$on('$stateChangeStart', function(e, toState, toParams, fromState, fromParams) {
      // toState va a contener el objeto con el que defino el state.
      // Eso incluye sus propiedades views, url, data, etc.

      // Usando toState, pregunto si requiere autenticación.
      if(toState.data !== undefined && toState.data.requireAuth === true) {
        // Esta ruta requiere autenticación, así que verifico que el usuario haya iniciado sesión.
        if(!Auth.isLogged()) {
          // Cancelo el cambio de state.
          e.preventDefault();

          $ionicPopup.alert({
            'title': 'Autenticación requerida.',
            'template': 'Para realizar esta acción necesita iniciar sesión.'
          }).then(function() {
            // Redirecciono al login.
            $state.go('login');
          });
        }
      }
    });
  });
})

.config(function($stateProvider, $urlRouterProvider) {
  $stateProvider

  .state('login', {
    url: '/login',
    templateUrl: 'templates/login.html',
    controller: 'LoginCtrl'
  })

  .state('register', {
    url: '/register',
    templateUrl: 'templates/register.html',
    controller: 'RegisterCtrl'
  })

  .state('app', {
    url: '/app',
    abstract: true,
    templateUrl: 'templates/menu.html'
  })

  .state('app.home', {
    url: '/home',
    data: {
        requireAuth: true
    },
    views: {
      'menuContent': {
        templateUrl: 'templates/home.html',
        controller: 'HomeCtrl'
      }
    }
  })

  .state('app.post', {
    url: '/post/:id',
    data: {
        requireAuth: true
    },
    views: {
      'menuContent': {
        templateUrl: 'templates/post.html',
        controller: 'PostCtrl'
      }
    }
  })

  .state('app.post-add', {
    url: '/post-add',
    data: {
        requireAuth: true
    },
    views: {
      'menuContent': {
        templateUrl: 'templates/post_add.html',
        controller: 'PostAddCtrl'
      }
    }
  })

  .state('app.comment-add', {
    url: '/post/:id/comment-add',
    data: {
        requireAuth: true
    },
    views: {
      'menuContent': {
        templateUrl: 'templates/comment_add.html',
        controller: 'CommentAddCtrl'
      }
    }
  })

  .state('app.profile', {
    url: '/profile',
    data: {
        requireAuth: true
    },
    views: {
      'menuContent': {
        templateUrl: 'templates/profile.html',
        controller: 'ProfileCtrl'
      }
    }
  })

  .state('app.profile-edit', {
    url: '/profile/:id/edit',
    views: {
      'menuContent': {
        templateUrl: 'templates/profile_edit.html',
        controller: 'ProfileEditionCtrl'
      }
    }
  });

  // if none of the above states are matched, use this as the fallback
  $urlRouterProvider.otherwise('/app/home');
})
// Defino una constante para contener la ruta de la app front end
.constant('API', 'http://localhost/FlipMeet/app/www')
// Defino una constante para contener la ruta de la api back end
.constant('API_MVC', 'http://localhost/FlipMeet/mvc/public/api');