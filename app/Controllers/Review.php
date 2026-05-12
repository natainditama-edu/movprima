<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MovieModel;
use App\Models\ReviewModel;
use App\Models\ReviewLikeModel;

/**
 * Review Controller
 *
 * Full CRUD for user reviews including AJAX like toggling.
 * Ownership is enforced on edit, update, and destroy.
 * Owner: Shyfa
 */
class Review extends BaseController
{
    /**
     * GET /reviews/create?movie_id={id}
     *
     * Reads movie_id from query string, validates the movie exists,
     * and renders the review creation form.
     *
     * @return string
     */
    public function createForm(): string
    {
        return view('reviews/create');
    }

    /**
     * POST /reviews
     *
     * Validates rating (1–10), title, and body; checks for duplicate user+movie
     * review; inserts the review; calls updateMovieRating(); redirects to movie detail.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function store(): \CodeIgniter\HTTP\RedirectResponse
    {
        return redirect()->to('/movies');
    }

    /**
     * GET /reviews/{id}/edit
     *
     * Loads the review by id, asserts the session user is the owner (or admin),
     * and renders the pre-filled edit form.
     *
     * @param int $id Review primary key
     *
     * @return string
     */
    public function editForm(int $id): string
    {
        return view('reviews/edit');
    }

    /**
     * POST /reviews/{id}/update
     *
     * Validates the same rules as store(); updates the review record;
     * calls updateMovieRating(); redirects to the movie detail page.
     *
     * @param int $id Review primary key
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function update(int $id): \CodeIgniter\HTTP\RedirectResponse
    {
        return redirect()->to('/movies');
    }

    /**
     * POST /reviews/{id}/delete
     *
     * Asserts ownership or admin role; deletes the review;
     * calls updateMovieRating(); redirects to the movie detail page.
     *
     * @param int $id Review primary key
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function destroy(int $id): \CodeIgniter\HTTP\RedirectResponse
    {
        return redirect()->to('/movies');
    }

    /**
     * POST /reviews/{id}/like  [AJAX only]
     *
     * Toggles the like for the session user; syncs the denormalized counter;
     * returns JSON with keys 'liked' (bool) and 'count' (int).
     *
     * @param int $id Review primary key
     *
     * @return \CodeIgniter\HTTP\Response
     */
    public function like(int $id): \CodeIgniter\HTTP\Response
    {
        return $this->response->setJSON(['liked' => false, 'count' => 0]);
    }
}
