<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MovieModel;

/**
 * Home Controller
 *
 * Landing page featuring curated movie selections and latest reviews.
 * Owner: Gita
 */
class Home extends BaseController
{
  /**
   * GET /
   *
   * Loads featured movies, top-rated grid, and latest reviews strip
   * then renders the landing page.
   *
   * @return string
   */
  public function index(): string
  {
    $movieModel = new MovieModel();

    $cache = \Config\Services::cache();

    $featured = $cache->remember("home_featured", 300, function () use ($movieModel) {
      return $movieModel->getMoviesWithTopGenre("release_year", "DESC", 4);
    });

    $latest = $cache->remember("home_latest", 300, function () use ($movieModel) {
      return $movieModel->getMoviesWithTopGenre("release_year", "DESC", 12);
    });

    $topRated = $cache->remember("home_topRated", 300, function () use ($movieModel) {
      return $movieModel->getMoviesWithTopGenre("avg_rating", "DESC", 12);
    });

    $recommended = $cache->remember("home_recommended", 300, function () use ($movieModel) {
      return $movieModel->getMoviesWithTopGenre("id", "RANDOM", 12);
    });

    $classic = $cache->remember("home_classic", 300, function () use ($movieModel) {
      return $movieModel->getMoviesWithTopGenre("release_year", "ASC", 12);
    });

    return view("home/index", [
      "featured" => $featured,
      "latest" => $latest,
      "topRated" => $topRated,
      "recommended" => $recommended,
      "classic" => $classic,
    ]);
  }
}
