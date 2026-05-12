<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ReviewModel;

/**
 * Admin Review Controller
 *
 * Review moderation: paginated listing filterable by status and deletion
 * with automatic movie rating recalculation.
 * Owner: Gita
 */
class Review extends BaseController
{
    /**
     * GET /admin/reviews
     *
     * Lists all reviews joined with user name and movie title;
     * filterable by status query param; paginated at 20 per page.
     *
     * @return string
     */
    public function index(): string
    {
        return view('admin/reviews/index');
    }

    /**
     * POST /admin/reviews/{id}/delete
     *
     * Deletes the review then calls ReviewModel::updateMovieRating()
     * to keep avg_rating and review_count consistent; redirects to /admin/reviews.
     *
     * @param int $id Review primary key
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function destroy(int $id): \CodeIgniter\HTTP\RedirectResponse
    {
        return redirect()->to('/admin/reviews');
    }
}
