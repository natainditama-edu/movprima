<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\GenreModel;
use App\Models\MovieModel;
use App\Models\WatchlistModel;
use App\Models\ReviewModel;
use CodeIgniter\Exceptions\PageNotFoundException;

/**
 * Movie Controller
 *
 * Public movie listing with search/filter and movie detail page.
 * Owner: Shyfa
 */
class Movie extends BaseController
{
  /**
   * GET /movies
   *
   * Reads q, genre, year, sort from query string, calls MovieModel::search(),
   * and paginates results at 12 per page.
   *
   * @return string
   */
  public function index(): string
  {
    $movieModel = new MovieModel();
    $genreModel = new GenreModel();

    $searchQuery = $this->request->getGet("q") ?? "";
    $selectedGenre = $this->request->getGet("genre") ?? "";
    $selectedSort = $this->request->getGet("sort") ?? "newest";
    $currentPage = (int) ($this->request->getGet("page") ?? 1);
    $perPage = 20;

    $builder = $movieModel->withTopGenre()->search($searchQuery);
    if ($selectedGenre && !empty($selectedGenre)) {
      $genre = $genreModel->getBySlug($selectedGenre);
      if ($genre && !empty($genre["id"])) {
        $builder->byGenre($genre["id"]);
      }
    }

    $builder->sortBy($selectedSort);
    $movies = $builder->paginate($perPage, "default", $currentPage);
    $totalMovies = $movieModel->pager->getTotal("default");
    $totalPages = $movieModel->pager->getPageCount("default");

    $genres = cache()->remember("all_genres_sorted", 3600, function () use ($genreModel) {
      return $genreModel->getAllSorted();
    });

    return view("movies/index", [
      "movies" => $movies,
      "genres" => $genres,
      "searchQuery" => $searchQuery,
      "selectedGenre" => $selectedGenre,
      "selectedSort" => $selectedSort,
      "totalMovies" => $totalMovies,
      "totalPages" => $totalPages,
      "currentPage" => $currentPage,
      "pager" => $movieModel->pager,
    ]);
  }

  /**
   * GET /movies/{slug}
   *
   * Loads a single published movie by slug (404 if missing), fetches paginated
   * reviews, and checks watchlist status for the logged-in user.
   *
   * @param string $slug URL slug of the movie
   *
   * @return string
   */
  public function show(string $slug): string
  {
    helper("text");

    $movieModel = new MovieModel();
    $reviewModel = new ReviewModel();

    $movie = $movieModel->getBySlug($slug);
    if (!$movie || $movie["status"] !== "published") {
      throw PageNotFoundException::forPageNotFound("Data film tersebut tidak ditemukan di dalam sistem.");
    }

    $userId = session()->get("user_id") ? (int) session()->get("user_id") : null;
    $inWatchlist = false;

    if ($userId && !empty($movie)) {
      $watchlistModel = new WatchlistModel();
      $inWatchlist = $watchlistModel->checkUserWatchlist($userId, $movie["id"]);
    }

    $reviews = $reviewModel->getByMovie($movie["id"], $userId);
    $userReview = null;
    if ($userId) {
      $userReview = $reviewModel->where("movie_id", $movie["id"])->where("user_id", $userId)->first();
    }

    $related = [];
    $genres = $movieModel->getGenres($movie["id"]);
    if (!empty($genres) && count($genres) > 0) {
      $genreIds = array_column($genres, "id");
      $related = cache()->remember("movie_related_" . $movie["id"], 300, function () use ($movieModel, $movie, $genreIds) {
        return $movieModel->getRelatedMovies($movie["id"], $genreIds, 6);
      });
    }

    return view("movies/detail", [
      "movie" => $movie,
      "movieGenres" => $genres,
      "movieReviews" => $reviews,
      "relatedMovies" => $related,
      "isMovieInWatchlist" => $inWatchlist,
      "avgRating" => $movie["avg_rating"] ?? null,
      "userReview" => $userReview,
    ]);
  }
}
