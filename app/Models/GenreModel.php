<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Genre Model
 *
 * Manages movie genre data and safe URL slugs.
 * Handles aggregation functions to count movies per genre.
 */
class GenreModel extends Model
{
  protected $table = "genres";
  protected $primaryKey = "id";
  protected $returnType = "array";
  protected $useSoftDeletes = false;
  protected $useTimestamps = true;
  protected $allowedFields = ["name", "slug"];

  protected $validationRules = [
    "name" => "required|min_length[2]|max_length[60]",
    "slug" => "required|max_length[70]|is_unique[genres.slug,id,{id}]",
  ];

  /**
   * Retrieve a specific genre row by URL slug.
   * Returns the array data or null if missing.
   *
   * @param string $slug
   *
   * @return array|null
   */
  public function getBySlug(string $slug): ?array
  {
    return $this->where("slug", $slug)->first();
  }

  /**
   * Fetch all available genres from the database.
   * Returns genres ordered alphabetically by their name.
   *
   * @return array
   */
  public function getAllSorted(): array
  {
    return $this->orderBy("name")->findAll();
  }

  /**
   * Modify query to include movie count per genre.
   * Returns the current model instance for method chaining.
   *
   * @return self
   */
  public function withMovieCount(): self
  {
    $this->select("genres.*, COUNT(movie_genres.movie_id) as movie_count")->join("movie_genres", "movie_genres.genre_id = genres.id", "left")->groupBy("genres.id");
    return $this;
  }

  /**
   * Convert standard string into a safe URL slug.
   * Transliterates non-Latin characters and strips invalid ones.
   *
   * @param string $name
   *
   * @return string
   */
  public static function makeSlug(string $name): string
  {
    if (function_exists("transliterator_transliterate")) {
      $transliterated = transliterator_transliterate("Any-Latin; Latin-ASCII; Lower()", $name);
      if ($transliterated !== false) {
        $name = $transliterated;
      }
    }

    $slug = strtolower(url_title($name, "-", true));
    $slug = preg_replace("/[^a-z0-9\-]/", "", $slug);
    $slug = preg_replace("/-+/", "-", $slug);
    $slug = trim($slug, "-");

    if (empty($slug)) {
      $slug = "item-" . substr(md5($name . time()), 0, 8);
    }

    return $slug;
  }

  /**
   * Syncs TMDB genres to the local database.
   * Returns an array of local genre IDs.
   *
   * @param array $tmdbGenres Array of genres from TMDB API
   * @return array Array of local genre IDs
   */
  public function syncTmdbGenres(array $tmdbGenres): array
  {
    $genreIds = [];
    foreach ($tmdbGenres as $g) {
      $name = $g["name"] ?? "";
      if (empty($name)) {
        continue;
      }

      $slug = self::makeSlug($name);

      // Check if genre exists
      $existing = $this->where("slug", $slug)->orWhere("name", $name)->first();

      if ($existing) {
        $genreIds[] = $existing["id"];
      } else {
        // Create new genre
        $newId = $this->insert([
          "name" => $name,
          "slug" => $slug,
        ]);
        if ($newId) {
          $genreIds[] = $newId;
        }
      }
    }
    return $genreIds;
  }
}
