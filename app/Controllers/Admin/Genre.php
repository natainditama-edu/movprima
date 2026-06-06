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
    $genreModel = new GenreModel();
    return view("admin/genres/index", [
      "genres" => $genreModel->withMovieCount()->findAll(),
    ]);
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
    $rules = [
      "name" => "required|min_length[2]|max_length[60]|is_unique[genres.name]",
    ];

    if (!$this->validate($rules)) {
      return redirect()->back()->withInput()->with("errors", $this->validator->getErrors())->with("error", "Silakan periksa kembali isian formulir Anda.");
    }

    $genreModel = new GenreModel();
    $name = $this->request->getPost("name");

    $genreModel->insert([
      "name" => $name,
      "slug" => $genreModel::makeSlug($name),
    ]);

    $cache = \Config\Services::cache();
    $cache->delete("all_genres_sorted");
    $cache->delete("all_genres_with_count");
    $cache->delete("admin_dashboard_stats");

    return redirect()->to("/admin/genres")->with("success", "Genre baru berhasil ditambahkan ke sistem.");
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
    $rules = [
      "name" => "required|min_length[2]|max_length[60]|is_unique[genres.name,id,{$id}]",
    ];

    if (!$this->validate($rules)) {
      return redirect()->back()->withInput()->with("errors", $this->validator->getErrors())->with("error", "Silakan periksa kembali isian formulir Anda.");
    }

    $genreModel = new GenreModel();
    $name = $this->request->getPost("name");

    $genreModel->update($id, [
      "name" => $name,
      "slug" => $genreModel::makeSlug($name),
    ]);

    $cache = \Config\Services::cache();
    $cache->delete("all_genres_sorted");
    $cache->delete("all_genres_with_count");
    $cache->delete("admin_dashboard_stats");

    return redirect()->to("/admin/genres")->with("success", "Perubahan pada genre berhasil disimpan.");
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
    $genreModel = new GenreModel();
    $genreModel->delete($id);

    $cache = \Config\Services::cache();
    $cache->delete("all_genres_sorted");
    $cache->delete("all_genres_with_count");
    $cache->delete("admin_dashboard_stats");

    return redirect()->to("/admin/genres")->with("success", "Data genre berhasil dihapus dari sistem secara permanen.");
  }
}
