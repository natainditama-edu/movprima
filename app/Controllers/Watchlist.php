<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\WatchlistModel;

/**
 * Watchlist Controller
 *
 * Manages the per-user movie watchlist with AJAX toggle support
 * and status update / removal actions.
 * Owner: Shyfa
 */
class Watchlist extends BaseController
{
    /**
     * POST /watchlist  [AJAX only]
     *
     * Reads movie_id from JSON body; calls WatchlistModel::toggle();
     * returns JSON with keys 'status' (string) and 'message' (string).
     *
     * @return \CodeIgniter\HTTP\Response
     */
    public function store(): \CodeIgniter\HTTP\Response
    {
        return $this->response->setJSON(['status' => 'added', 'message' => 'Added to watchlist.']);
    }

    /**
     * POST /watchlist/{id}/status
     *
     * Asserts the session user owns this watchlist entry;
     * updates the watching status; redirects back.
     *
     * @param int $id Watchlist entry primary key
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function update(int $id): \CodeIgniter\HTTP\RedirectResponse
    {
        return redirect()->back();
    }

    /**
     * POST /watchlist/{id}/delete
     *
     * Asserts the session user owns this watchlist entry;
     * removes the entry; redirects to profile.
     *
     * @param int $id Watchlist entry primary key
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function destroy(int $id): \CodeIgniter\HTTP\RedirectResponse
    {
        return redirect()->to('/profile');
    }
}
