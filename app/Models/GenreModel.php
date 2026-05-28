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
   * Returns a lowercased and hyphenated string.
   *
   * @param string $name
   *
   * @return string
   */
  public static function makeSlug(string $name): string
  {
    return strtolower(url_title($name, "-", true));
  }
}
