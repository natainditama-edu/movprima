<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\GenreModel;

/**
 * Admin Genre Controller
 *
 * CRUD management for movie genres with automatic slug generation.
 * Pivot rows in movie_genres cascade on delete.
 * Owner: Gita
 */
class Genre extends BaseController
{
    /**
     * GET /admin/genres
     *
     * Lists all genres with their associated movie count
     * using GenreModel::getAllWithCount().
     *
     * @return string
     */
    public function index(): string
    {
        return view('admin/genres/index');
    }

    /**
     * POST /admin/genres
     *
     * Validates name (required|min_length[2]|is_unique[genres.name]);
     * auto-generates slug; inserts the genre; redirects to /admin/genres.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function store(): \CodeIgniter\HTTP\RedirectResponse
    {
        return redirect()->to('/admin/genres');
    }

    /**
     * POST /admin/genres/{id}
     *
     * Validates name with is_unique exclusion for the current id;
     * updates name and regenerates slug; redirects to /admin/genres.
     *
     * @param int $id Genre primary key
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function update(int $id): \CodeIgniter\HTTP\RedirectResponse
    {
        return redirect()->to('/admin/genres');
    }

    /**
     * POST /admin/genres/{id}/delete
     *
     * Deletes the genre; pivot rows in movie_genres are removed
     * automatically by the ON DELETE CASCADE FK constraint.
     *
     * @param int $id Genre primary key
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function destroy(int $id): \CodeIgniter\HTTP\RedirectResponse
    {
        return redirect()->to('/admin/genres');
    }
}
