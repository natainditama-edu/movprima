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

  /**
   * GET /admin/movies/tmdb
   *
   * Searches TMDB for a movie by title.
   * Returns a view with search results.
   *
   * @return string
   */
  public function tmdbSearch(): string
  {
    $query = (string)$this->request->getGet("q");
    $results = null;

    if (!empty($query)) {
      $apiKey = getenv("TMDB_API_KEY");
      if (empty($apiKey) || $apiKey === "masukkan_key_disini") {
        return view("admin/movies/tmdb", [
          "query" => $query,
          "error" => "TMDB API Key belum diatur di file .env.",
        ]);
      }

      $url = "https://api.themoviedb.org/3/search/movie?api_key=" . urlencode($apiKey) . "&query=" . urlencode($query) . "&language=id-ID&page=1";

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

      $response = curl_exec($ch);
      $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

      if ($httpCode === 200 && $response) {
        $data = json_decode($response, true);
        if (isset($data["results"])) {
          $results = $data["results"];
        }
      }
    }

    return view("admin/movies/tmdb", [
      "query" => $query,
      "results" => $results,
    ]);
  }

  /**
   * POST /admin/movies/tmdb/import
   *
   * Fetches full movie details from TMDB and saves to local database.
   * Also imports the movie's genre tags and fetches trailer links.
   *
   * @return \CodeIgniter\HTTP\RedirectResponse
   */
  public function tmdbImport(): \CodeIgniter\HTTP\RedirectResponse
  {
    $tmdbId = $this->request->getPost("tmdb_id");
    if (empty($tmdbId)) {
      return redirect()->back()->with("error", "ID TMDB tidak valid.");
    }

    $apiKey = getenv("TMDB_API_KEY");
    if (empty($apiKey) || $apiKey === "masukkan_key_disini") {
      return redirect()->back()->with("error", "TMDB API Key belum diatur.");
    }

    $url = "https://api.themoviedb.org/3/movie/" . urlencode($tmdbId) . "?api_key=" . urlencode($apiKey) . "&language=id-ID&append_to_response=credits,videos";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if ($httpCode !== 200 || !$response) {
      return redirect()->back()->with("error", "Gagal mengambil data dari TMDB.");
    }

    $tmdbData = json_decode($response, true);
    if (!isset($tmdbData["title"])) {
      return redirect()->back()->with("error", "Data TMDB tidak lengkap.");
    }

    $movieModel = new MovieModel();

    // Check if already imported
    $slug = \App\Models\GenreModel::makeSlug($tmdbData["title"]) . "-" . time();

    // Extract Director
    $director = null;
    if (isset($tmdbData["credits"]["crew"]) && is_array($tmdbData["credits"]["crew"])) {
      foreach ($tmdbData["credits"]["crew"] as $crew) {
        if (isset($crew["job"]) && $crew["job"] === "Director") {
          $director = $crew["name"];
          break;
        }
      }
    }

    // Extract Trailer
    $trailerUrl = null;
    if (isset($tmdbData["videos"]["results"]) && is_array($tmdbData["videos"]["results"])) {
      foreach ($tmdbData["videos"]["results"] as $video) {
        if (isset($video["type"], $video["site"]) && $video["type"] === "Trailer" && $video["site"] === "YouTube") {
          $trailerUrl = "https://www.youtube.com/watch?v=" . $video["key"];
          break;
        }
      }
    }

    $synopsis = $tmdbData["overview"] ?? "";

    // Fallback to default language (usually English) if synopsis or trailer is empty
    if (empty($synopsis) || empty($trailerUrl)) {
      $fallbackUrl = "https://api.themoviedb.org/3/movie/" . urlencode($tmdbId) . "?api_key=" . urlencode($apiKey) . "&append_to_response=videos";

      $ch2 = curl_init();
      curl_setopt($ch2, CURLOPT_URL, $fallbackUrl);
      curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, false);
      $response2 = curl_exec($ch2);

      if ($response2) {
        $fallbackData = json_decode($response2, true);
        if (empty($synopsis) && !empty($fallbackData["overview"])) {
          $synopsis = $fallbackData["overview"];
        }
        if (empty($trailerUrl) && isset($fallbackData["videos"]["results"]) && is_array($fallbackData["videos"]["results"])) {
          foreach ($fallbackData["videos"]["results"] as $video) {
            if (isset($video["type"], $video["site"]) && $video["type"] === "Trailer" && $video["site"] === "YouTube") {
              $trailerUrl = "https://www.youtube.com/watch?v=" . $video["key"];
              break;
            }
          }
        }
      }
    }

    if (empty($synopsis)) {
      $synopsis = "Belum ada sinopsis.";
    }

    // Extract Country
    $country = null;
    if (!empty($tmdbData["production_countries"]) && is_array($tmdbData["production_countries"])) {
      $country = $tmdbData["production_countries"][0]["name"] ?? null;
    }

    // Extract Language
    $language = "English";
    if (!empty($tmdbData["spoken_languages"]) && is_array($tmdbData["spoken_languages"])) {
      $language = $tmdbData["spoken_languages"][0]["english_name"] ?? ($tmdbData["spoken_languages"][0]["name"] ?? "English");
    } elseif (!empty($tmdbData["original_language"])) {
      $language = strtoupper($tmdbData["original_language"]);
    }

    $posterUrl = !empty($tmdbData["poster_path"]) ? "https://image.tmdb.org/t/p/w500" . $tmdbData["poster_path"] : null;
    $backdropUrl = !empty($tmdbData["backdrop_path"]) ? "https://image.tmdb.org/t/p/w1280" . $tmdbData["backdrop_path"] : null;
    $releaseYear = !empty($tmdbData["release_date"]) ? substr($tmdbData["release_date"], 0, 4) : null;

    $data = [
      "title" => $tmdbData["title"],
      "slug" => $slug,
      "synopsis" => $synopsis,
      "director" => $director,
      "release_year" => $releaseYear,
      "duration" => $tmdbData["runtime"] ?? null,
      "poster" => $posterUrl,
      "backdrop" => $backdropUrl,
      "trailer_url" => $trailerUrl,
      "language" => $language,
      "country" => $country,
      "status" => "published",
    ];

    $movieId = $movieModel->insert($data);

    if ($movieId) {
      // Handle genres
      if (isset($tmdbData["genres"]) && is_array($tmdbData["genres"])) {
        $genreModel = new GenreModel();
        $genreIds = $genreModel->syncTmdbGenres($tmdbData["genres"]);
        $movieModel->syncGenres($movieId, $genreIds);
      }

      $cache = \Config\Services::cache();
      $cache->delete("home_featured");
      $cache->delete("home_latest");
      $cache->delete("home_topRated");
      $cache->delete("home_recommended");
      $cache->delete("home_classic");
      $cache->delete("admin_dashboard_stats");

      return redirect()->to("/admin/movies")->with("success", "Film berhasil diimpor dari TMDB.");
    }

    return redirect()->back()->with("error", "Gagal menyimpan film ke database.");
  }
}
