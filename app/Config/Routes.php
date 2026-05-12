<?php

use App\Controllers\Home;
use App\Controllers\Auth;
use App\Controllers\Movie;
use App\Controllers\Genre;
use App\Controllers\Review;
use App\Controllers\Watchlist;
use App\Controllers\User;
use App\Controllers\Admin\Dashboard;
use App\Controllers\Admin\Movie as AdminMovie;
use App\Controllers\Admin\Genre as AdminGenre;
use App\Controllers\Admin\Review as AdminReview;
use App\Controllers\Admin\User as AdminUser;
use CodeIgniter\Router\RouteCollection;

/**
 * Public routes
 *
 * Accessible by any visitor without authentication.
 * Covers the landing page, movie listing, movie detail, and genre browsing.
 *
 * @var RouteCollection $routes
 */
$routes->get('/',                 [Home::class, 'index']);
$routes->get('movies',            [Movie::class, 'index']);
$routes->get('movies/(:segment)', [Movie::class, 'show']);
$routes->get('genres/(:segment)', [Genre::class, 'show']);

/**
 * Auth routes
 *
 * Guest-facing pages for login, registration, and logout.
 * Grouped under the /auth prefix.
 * 
 * @var RouteCollection $routes
 */
$routes->group('auth', static function (RouteCollection $routes): void {
  $routes->get('login',     [Auth::class, 'loginForm']);
  $routes->post('login',    [Auth::class, 'login']);
  $routes->get('register',  [Auth::class, 'registerForm']);
  $routes->post('register', [Auth::class, 'register']);
  $routes->get('logout',    [Auth::class, 'logout']);
});

/**
 * Protected routes
 *
 * Requires an active user session (AuthFilter).
 * Covers profile management, review CRUD, and watchlist actions.
 * 
 * @var RouteCollection $routes
 */
$routes->group('', ['filter' => 'auth'], static function (RouteCollection $routes): void {
  $routes->get('profile',       [User::class, 'profile']);
  $routes->get('profile/edit',  [User::class, 'editForm']);
  $routes->post('profile/edit', [User::class, 'update']);

  $routes->get('reviews/create',         [Review::class, 'createForm']);
  $routes->post('reviews',               [Review::class, 'store']);
  $routes->get('reviews/(:num)/edit',    [Review::class, 'editForm']);
  $routes->post('reviews/(:num)/update', [Review::class, 'update']);
  $routes->post('reviews/(:num)/delete', [Review::class, 'destroy']);
  $routes->post('reviews/(:num)/like',   [Review::class, 'like']);

  $routes->post('watchlist',               [Watchlist::class, 'store']);
  $routes->post('watchlist/(:num)/status', [Watchlist::class, 'update']);
  $routes->post('watchlist/(:num)/delete', [Watchlist::class, 'destroy']);
});

/**
 * Admin routes
 *
 * Requires an active session with role = admin (AdminFilter).
 * Covers the dashboard, movie/genre/review/user management.
 * 
 * @var RouteCollection $routes
 */
$routes->group('admin', ['filter' => 'admin'], static function (RouteCollection $routes): void {
  $routes->get('', [Dashboard::class, 'index']);

  $routes->get('movies',                [AdminMovie::class, 'index']);
  $routes->get('movies/create',         [AdminMovie::class, 'create']);
  $routes->post('movies',               [AdminMovie::class, 'store']);
  $routes->get('movies/(:num)/edit',    [AdminMovie::class, 'edit']);
  $routes->post('movies/(:num)',        [AdminMovie::class, 'update']);
  $routes->post('movies/(:num)/delete', [AdminMovie::class, 'destroy']);

  $routes->get('genres',                [AdminGenre::class, 'index']);
  $routes->post('genres',               [AdminGenre::class, 'store']);
  $routes->post('genres/(:num)',        [AdminGenre::class, 'update']);
  $routes->post('genres/(:num)/delete', [AdminGenre::class, 'destroy']);

  $routes->get('reviews',                [AdminReview::class, 'index']);
  $routes->post('reviews/(:num)/delete', [AdminReview::class, 'destroy']);

  $routes->get('users',                [AdminUser::class, 'index']);
  $routes->post('users/(:num)/delete', [AdminUser::class, 'destroy']);
});

/**
 * 404 override
 * 
 * @var RouteCollection $routes
 */
$routes->set404Override([Home::class, 'error404']);
