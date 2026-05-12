<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MovieModel;
use App\Models\ReviewModel;

/**
 * Home Controller
 *
 * Landing page featuring curated movie selections and latest reviews.
 * Owner: Gita
 */
class Home extends BaseController
{
    /**
     * GET /
     *
     * Loads featured movies, top-rated grid, and latest reviews strip
     * then renders the landing page.
     *
     * @return string
     */
    public function index(): string
    {
        return view('home/index');
    }

    /**
     * Fallback for unmatched routes registered via $routes->set404Override().
     * Renders the custom 404 error page with a 404 HTTP status code.
     *
     * @return string
     */
    public function error404(): string
    {
        return view('errors/custom_404');
    }
}
