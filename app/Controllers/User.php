<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ReviewModel;
use App\Models\UserModel;
use App\Models\WatchlistModel;

/**
 * User Controller
 *
 * Manages the public user profile view and the profile editing form,
 * including avatar upload handling.
 * Owner: Gita
 */
class User extends BaseController
{
    /**
     * GET /profile
     *
     * Loads the session user's data, their reviews, and their watchlist
     * then renders the profile page.
     *
     * @return string
     */
    public function profile(): string
    {
        return view('user/profile');
    }

    /**
     * GET /profile/edit
     *
     * Loads the current user's data from the database
     * and renders the profile edit form.
     *
     * @return string
     */
    public function editForm(): string
    {
        return view('user/edit');
    }

    /**
     * POST /profile/edit
     *
     * Validates name and bio; handles avatar upload (max 1 MB, jpg/png/webp)
     * to public/uploads/avatars/; calls UserModel::updateProfile(); redirects to /profile.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function update(): \CodeIgniter\HTTP\RedirectResponse
    {
        return redirect()->to('/profile');
    }
}
