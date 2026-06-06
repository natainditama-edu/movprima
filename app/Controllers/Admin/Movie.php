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
    $movieModel = new MovieModel();
    return view("admin/movies/index", [
      "movies" => $movieModel->getAllAdmin(),
    ]);
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
    $genreModel = new GenreModel();
    return view("admin/movies/form", [
      "genres" => $genreModel->getAllSorted(),
    ]);
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
    $rules = [
      "title" => "required|min_length[2]|max_length[150]",
      "poster" => "permit_empty|valid_url",
      "backdrop" => "permit_empty|valid_url",
    ];

    if (!$this->validate($rules)) {
      return redirect()->back()->withInput()->with("errors", $this->validator->getErrors())->with("error", "Silakan periksa kembali isian formulir Anda.");
    }

    $movieModel = new MovieModel();
    $title = $this->request->getPost("title");

    $data = [
      "title" => $title,
      "slug" => \App\Models\GenreModel::makeSlug($title) . "-" . time(),
      "synopsis" => $this->request->getPost("synopsis"),
      "release_year" => $this->request->getPost("release_year"),
      "duration" => $this->request->getPost("duration"),
      "poster" => $this->request->getPost("poster"),
      "backdrop" => $this->request->getPost("backdrop"),
      "status" => "published",
    ];

    $movieId = $movieModel->insert($data);

    $genres = $this->request->getPost("genres") ?? [];
    $movieModel->syncGenres($movieId, $genres);

    $cache = \Config\Services::cache();
    $cache->delete("home_featured");
    $cache->delete("home_latest");
    $cache->delete("home_topRated");
    $cache->delete("home_recommended");
    $cache->delete("home_classic");
    $cache->delete("admin_dashboard_stats");

    return redirect()->to("/admin/movies")->with("success", "Data film baru berhasil ditambahkan ke sistem.");
  }

  /**
   * GET /admin/movies/{id}/edit
   *
   * Loads the movie with its current genre ids and renders
   * the pre-filled edit form.
   *
   * @param int $id Movie primary key
   *
   * @return \CodeIgniter\HTTP\RedirectResponse|string
   */
  public function edit(int $id)
  {
    $movieModel = new MovieModel();
    $genreModel = new GenreModel();

    $movie = $movieModel->find($id);
    if (!$movie) {
      return redirect()->to("/admin/movies")->with("error", "Data film yang Anda cari tidak ditemukan.");
    }

    $movieGenres = $movieModel->getGenres($id);
    $movieGenreIds = array_column($movieGenres, "genre_id");

    return view("admin/movies/form", [
      "movie" => $movie,
      "genres" => $genreModel->getAllSorted(),
      "movieGenreIds" => $movieGenreIds,
    ]);
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
    $rules = [
      "title" => "required|min_length[2]|max_length[150]",
      "poster" => "permit_empty|valid_url",
      "backdrop" => "permit_empty|valid_url",
    ];

    if (!$this->validate($rules)) {
      return redirect()->back()->withInput()->with("errors", $this->validator->getErrors())->with("error", "Silakan periksa kembali isian formulir Anda.");
    }

    $movieModel = new MovieModel();
    $movie = $movieModel->find($id);

    if (!$movie) {
      return redirect()->to("/admin/movies")->with("error", "Data film yang Anda cari tidak ditemukan.");
    }

    $title = $this->request->getPost("title");
    $data = [
      "title" => $title,
      "synopsis" => $this->request->getPost("synopsis"),
      "release_year" => $this->request->getPost("release_year"),
      "duration" => $this->request->getPost("duration"),
      "poster" => $this->request->getPost("poster"),
      "backdrop" => $this->request->getPost("backdrop"),
    ];

    if ($title !== $movie["title"]) {
      $data["slug"] = \App\Models\GenreModel::makeSlug($title) . "-" . time();
    }

    $movieModel->update($id, $data);

    $genres = $this->request->getPost("genres") ?? [];
    $movieModel->syncGenres($id, $genres);

    $cache = \Config\Services::cache();
    $cache->delete("home_featured");
    $cache->delete("home_latest");
    $cache->delete("home_topRated");
    $cache->delete("home_recommended");
    $cache->delete("home_classic");
    $cache->delete("admin_dashboard_stats");

    return redirect()->to("/admin/movies")->with("success", "Perubahan pada data film berhasil disimpan.");
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
    $movieModel = new MovieModel();
    $movie = $movieModel->find($id);

    if ($movie) {
      $movieModel->delete($id);
    }

    $cache = \Config\Services::cache();
    $cache->delete("home_featured");
    $cache->delete("home_latest");
    $cache->delete("home_topRated");
    $cache->delete("home_recommended");
    $cache->delete("home_classic");
    $cache->delete("admin_dashboard_stats");

    return redirect()->to("/admin/movies")->with("success", "Data film dan file terkait berhasil dihapus permanen.");
  }
}
