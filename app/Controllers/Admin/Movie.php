<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\GenreModel;
use App\Models\MovieModel;

/**
 * Admin Movie Controller
 *
 * Full CRUD management for movies including poster/backdrop upload
 * and many-to-many genre synchronisation.
 * Owner: Gita
 */
class Movie extends BaseController
{
    /**
     * GET /admin/movies
     *
     * Lists all movies with genre string, searchable by title,
     * paginated at 15 per page.
     *
     * @return string
     */
    public function index(): string
    {
        return view('admin/movies/index');
    }

    /**
     * GET /admin/movies/create
     *
     * Renders the blank movie creation form with all genres available
     * for checkbox selection.
     *
     * @return string
     */
    public function create(): string
    {
        return view('admin/movies/form');
    }

    /**
     * POST /admin/movies
     *
     * Validates all fields; handles poster and backdrop uploads;
     * inserts the movie; calls syncGenres(); redirects to /admin/movies.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function store(): \CodeIgniter\HTTP\RedirectResponse
    {
        return redirect()->to('/admin/movies');
    }

    /**
     * GET /admin/movies/{id}/edit
     *
     * Loads the movie with its current genre ids and renders
     * the pre-filled edit form.
     *
     * @param int $id Movie primary key
     *
     * @return string
     */
    public function edit(int $id): string
    {
        return view('admin/movies/form');
    }

    /**
     * POST /admin/movies/{id}
     *
     * Validates; updates the record; re-syncs genres; replaces image files
     * if new uploads are provided; redirects to /admin/movies.
     *
     * @param int $id Movie primary key
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function update(int $id): \CodeIgniter\HTTP\RedirectResponse
    {
        return redirect()->to('/admin/movies');
    }

    /**
     * POST /admin/movies/{id}/delete
     *
     * Deletes the movie (cascade removes reviews, watchlist, genre pivot via FK);
     * removes poster and backdrop files from disk; redirects to /admin/movies.
     *
     * @param int $id Movie primary key
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function destroy(int $id): \CodeIgniter\HTTP\RedirectResponse
    {
        return redirect()->to('/admin/movies');
    }
}
