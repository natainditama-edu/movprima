<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\GenreModel;
use App\Models\MovieModel;
use CodeIgniter\Exceptions\PageNotFoundException;

/**
 * Genre Controller
 *
 * Public genre browsing page displaying filtered movie results.
 * Owner: Shyfa
 */
class Genre extends BaseController
{
  /**
   * GET /genres
   *
   * Menampilkan semua genre beserta jumlah filmnya.
   *
   * @return string
   */
  public function index(): string
  {
    $genreModel = new GenreModel();

    $genres = cache()->remember("all_genres_with_count", 300, function () use ($genreModel) {
      return $genreModel->withMovieCount()->findAll();
    });

    return view("genres/index", [
      "title" => "Semua Kategori Genre",
      "metaDescription" => "Jelajahi puluhan kategori genre film terbaik di MovPrima. Temukan film aksi, drama, komedi, horor, dan genre favorit Anda lainnya.",
      "genres" => $genres,
    ]);
  }

  /**
   * GET /genres/{slug}
   *
   * Finds genre by slug (404 if missing) and renders a filtered,
   * paginated movie grid for that genre.
   *
   * @param string $slug URL slug of the genre
   *
   * @return string
   */
  public function show(string $slug): string
  {
    $genreModel = new GenreModel();
    $movieModel = new MovieModel();

    $genre = $genreModel->where("slug", $slug)->first();
    if (!$genre) {
      throw PageNotFoundException::forPageNotFound("Kategori genre tersebut tidak ditemukan di dalam sistem.");
    }

    $sort = $this->request->getGet("sort") ?? "newest";
    $searchQuery = $this->request->getGet("q") ?? "";
    $currentPage = (int) ($this->request->getGet("page") ?? 1);
    $perPage = 20;

    $movies = $movieModel->withTopGenre()->byGenre($genre["id"])->search($searchQuery)->sortBy($sort)->paginate($perPage, "default", $currentPage);
    $total = $movieModel->pager->getTotal("default");
    $totalPages = $movieModel->pager->getPageCount("default");

    $pageTitle = "Film Genre " . esc($genre["name"]);
    if ($sort === "rating") {
      $pageTitle .= " Terbaik";
    } elseif ($sort === "oldest") {
      $pageTitle .= " Klasik";
    } else {
      $pageTitle .= " Terbaru";
    }
    if ($currentPage > 1) {
      $pageTitle .= " - Halaman " . $currentPage;
    }

    return view("genres/show", [
      "title" => $pageTitle,
      "metaDescription" => "Koleksi lengkap film bergenre " . esc($genre["name"]) . " di MovPrima. Jelajahi film " . esc($genre["name"]) . " dengan rating tertinggi, ulasan terbaik, dan rilis terbaru.",
      "genre" => $genre,
      "movies" => $movies,
      "sort" => $sort,
      "searchQuery" => $searchQuery,
      "total" => $total,
      "totalPages" => $totalPages,
      "currentPage" => $currentPage,
      "pager" => $movieModel->pager,
    ]);
  }
}
