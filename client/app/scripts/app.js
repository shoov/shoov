'use strict';

/**
 * @ngdoc overview
 * @name clientApp
 * @description
 * # clientApp
 *
 * Main module of the application.
 */
angular
  .module('clientApp', [
    'ngAnimate',
    'ngCookies',
    'ngSanitize',
    'config',
    'frapontillo.bootstrap-switch',
    'LocalStorageModule',
    'ui.bootstrap',
    'ui.checkbox',
    'ui.router',
    'angular-loading-bar'
  ])
  .config(function($stateProvider, $urlRouterProvider, $httpProvider, cfpLoadingBarProvider) {

    /**
     * Redirect a user to a 403 error page.
     *
     * @param $state
     *   The ui-router state.
     * @param Auth
     *   The Auth service.
     * @param $timeout
     *   The timeout service.
     */
    var page403 = function($state, Auth,$timeout) {
      if (!Auth.isAuthenticated()) {
        // We need to use $timeout to make sure $state is ready to
        // transition.
        $timeout(function() {
          $state.go('403');
        });
      }
    };

    // Now set up the states.
    $stateProvider
      .state('homepage', {
        url: '',
        controller: 'HomepageCtrl',
        resolve: {
          account: function(Account) {
            return Account.get();
          }
        }
      })
      .state('homepageWithSlash', {
        url: '/',
        controller: 'HomepageCtrl',
        resolve: {
          account: function(Account) {
            return Account.get();
          }
        }
      })
      .state('logout', {
        url: '/logout',
        controller: function($scope, $state, Auth) {
          Auth.logout();
          $state.go('homepage');
        }
      })
      .state('login', {
        url: '/login',
        templateUrl: 'views/login.html',
        controller: 'LoginCtrl'
      })
      .state('github', {
        url: '/auth/github',
        templateUrl: 'views/github-auth.html',
        controller: 'GithubAuthCtrl'
      })
      .state('dashboard', {
        abstract: true,
        url: '',
        templateUrl: 'views/dashboard/main.html',
        controller: 'DashboardCtrl',
        onEnter: page403,
        resolve: {
          account: function(Account) {
            return Account.get();
          },
          selectedCompany: function($stateParams) {
            return $stateParams.companyId;
          }
        }
      })
      .state('dashboard.homepage', {
        url: 'homepage',
        templateUrl: 'views/dashboard/homepage.html',
        onEnter: page403,
        resolve: {
          account: function(Account) {
            return Account.get();
          }
        }
      })
      .state('dashboard.repos', {
        url: '/repos',
        templateUrl: 'views/dashboard/repos/repos.html',
        controller: 'ReposCtrl',
        resolve: {
          repos: function(Repos) {
            return Repos.get();
          }
        }
      })
      .state('dashboard.ciBuild', {
        url: '/repos/{buildId:int}',
        templateUrl: 'views/dashboard/repos/repo.html',
        controller: 'RepoCtrl',
        resolve: {
          build: function(Builds, $stateParams) {
            return Builds.get($stateParams.buildId, 'ci_build');
          },
          ciBuildItems: function(CiBuildItems, $stateParams) {
            return CiBuildItems.get($stateParams.buildId, 'ci_build');
          }
        }
      })
      .state('dashboard.ciBuildItems', {
        url: '/ci-build-items',
        templateUrl: 'views/dashboard/ci_build_items/ci_build_items.html',
        controller: 'CiBuildItemsCtrl',
        resolve: {
          ciBuildItems: function(CiBuildItems) {
            return CiBuildItems.get();
          }
        }
      })
      .state('dashboard.ciBuildItem', {
        url: '/ci-build-items/{ciBuildItemId:int}',
        templateUrl: 'views/dashboard/ci_build_items/ci_build_item.html',
        controller: 'CiBuildItemCtrl',
        resolve: {
          ciBuildItem: function(CiBuildItems, $stateParams) {
            return CiBuildItems.get($stateParams.ciBuildItemId);
          }
        }
      })
      .state('dashboard.ciIncidents', {
        url: '/ci-incidents',
        templateUrl: 'views/dashboard/ci_incidents/ci_incidents.html',
        controller: 'CiIncidentsCtrl',
        resolve: {
          incidents: function(CiIncidents) {
            return CiIncidents.get();
          }
        }
      })
      .state('dashboard.ciIncident', {
        url: '/ci-incidents/{incidentId:int}',
        templateUrl: 'views/dashboard/ci_incidents/ci_incident.html',
        controller: 'CiIncidentCtrl',
        resolve: {
          incident: function(CiIncidents, $stateParams) {
            return CiIncidents.get($stateParams.incidentId);
          }
        }
      })
      .state('dashboard.builds', {
        url: '/builds',
        templateUrl: 'views/dashboard/builds/builds.html',
        controller: 'BuildsCtrl',
        resolve: {
          builds: function(Builds) {
            return Builds.get();
          }
        }
      })
      .state('dashboard.screenshots', {
        url: '/screenshots/{buildId:int}',
        templateUrl: 'views/dashboard/screenshots/screenshots.html',
        controller: 'ScreenshotsCtrl',
        resolve: {
          screenshots: function(Screenshots, $stateParams) {
            return Screenshots.get($stateParams.buildId);
          },
          build: function(Builds, $stateParams) {
            return Builds.get($stateParams.buildId);
          }
        }
      })
      .state('dashboard.account', {
        url: '/my-account',
        templateUrl: 'views/dashboard/account/account.html',
        controller: 'AccountCtrl',
        onEnter: page403,
        resolve: {
          account: function(Account) {
            return Account.get();
          }
        }
      })
      .state('403', {
        url: '/403',
        templateUrl: 'views/403.html'
      });

    // For any unmatched url, redirect to '/'.
    // $urlRouterProvider.otherwise('/');

    // Define interceptors.
    $httpProvider.interceptors.push(function ($q, Auth, localStorageService) {
      return {
        'request': function (config) {
          if (!config.url.match(/login-token/)) {
            config.headers = {
              'access-token': localStorageService.get('access_token')
            };
          }
          return config;
        },

        'response': function(result) {
          if (result.data.access_token) {
            localStorageService.set('access_token', result.data.access_token);
          }
          return result;
        },

        'responseError': function (response) {
          if (response.status === 401) {
            Auth.authFailed();
          }

          return $q.reject(response);
        }
      };
    });

    // Configuration of the loading bar.
    cfpLoadingBarProvider.includeSpinner = false;
    cfpLoadingBarProvider.latencyThreshold = 1000;
  })
  .run(function ($rootScope, $state, $stateParams, $log, Config) {
    // It's very handy to add references to $state and $stateParams to the
    // $rootScope so that you can access them from any scope within your
    // applications.For example:
    // <li ng-class="{ active: $state.includes('contacts.list') }"> will set the <li>
    // to active whenever 'contacts.list' or one of its decendents is active.
    $rootScope.$state = $state;
    $rootScope.$stateParams = $stateParams;

    if (!!Config.debugUiRouter) {
      $rootScope.$on('$stateChangeStart',function(event, toState, toParams, fromState, fromParams){
        $log.log('$stateChangeStart to ' + toState.to + '- fired when the transition begins. toState,toParams : \n', toState, toParams);
      });

      $rootScope.$on('$stateChangeError',function(event, toState, toParams, fromState, fromParams){
        $log.log('$stateChangeError - fired when an error occurs during transition.');
        $log.log(arguments);
      });

      $rootScope.$on('$stateChangeSuccess',function(event, toState, toParams, fromState, fromParams){
        $log.log('$stateChangeSuccess to ' + toState.name + '- fired once the state transition is complete.');
      });

      $rootScope.$on('$viewContentLoaded',function(event){
        $log.log('$viewContentLoaded - fired after dom rendered',event);
      });

      $rootScope.$on('$stateNotFound',function(event, unfoundState, fromState, fromParams){
        $log.log('$stateNotFound '+unfoundState.to+'  - fired when a state cannot be found by its name.');
        $log.log(unfoundState, fromState, fromParams);
      });
    }
  });
