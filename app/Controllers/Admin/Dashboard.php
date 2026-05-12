<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MovieModel;
use App\Models\ReviewModel;
use App\Models\UserModel;

/**
 * Admin Dashboard Controller
 *
 * Renders the admin panel overview with aggregate statistics
 * and the latest submitted reviews.
 * Owner: Gita
 */
class Dashboard extends BaseController
{
    /**
     * GET /admin
     *
     * Queries COUNT(*) for movies, reviews, and users; fetches the latest
     * 5 reviews; and renders the admin dashboard view.
     *
     * @return string
     */
    public function index(): string
    {
        return view('admin/dashboard');
    }
}
