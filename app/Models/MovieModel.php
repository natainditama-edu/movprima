<?php

namespace App\Models;

use CodeIgniter\Model;
use Config\Database;

/**
 * Movie Model
 *
 * Manages complex interactions towards digital movie library collections.
 * Facilitates compound filters and multi-dimensional pivot genre relations.
 */
class MovieModel extends Model
{
  protected $table = "movies";
  protected $primaryKey = "id";
  protected $returnType = "array";
  protected $useSoftDeletes = false;
  protected $useTimestamps = true;

  protected $allowedFields = ["title", "slug", "synopsis", "director", "release_year", "duration", "poster", "backdrop", "trailer_url", "language", "country", "status", "avg_rating", "review_count"];

  /**
   * Fetch movie queue along with top genre label.
   * Returns array with left join for genreless movies.
   *
   * @param string $orderBy
   * @param string $direction
   * @param int    $limit
   *
   * @return array
   */
  public function getMoviesWithTopGenre(string $orderBy = "id", string $direction = "DESC", int $limit = 12): array
  {
    $select = "movies.*, (SELECT genres.name FROM genres JOIN movie_genres ON movie_genres.genre_id = genres.id WHERE movie_genres.movie_id = movies.id LIMIT 1) as top_genre";
    return $this->select($select)->orderBy($orderBy, $direction)->limit($limit)->find();
  }

  /**
   * Embed virtual top genre column to active query.
   * Returns relational model instance to ease function chaining.
   *
   * @return self
   */
  public function withTopGenre(): self
  {
    $select = "movies.*, (SELECT genres.name FROM genres JOIN movie_genres ON movie_genres.genre_id = genres.id WHERE movie_genres.movie_id = movies.id LIMIT 1) as top_genre";
    $this->select($select);
    return $this;
  }

  /**
   * Fetch one movie row with comma separated genres.
   * Returns informative array collection if entity exists.
   *
   * @param int $id
   *
   * @return array|null
   */
  public function getWithGenres(int $id): ?array
  {
    $select = "movies.*, (SELECT GROUP_CONCAT(genres.name SEPARATOR ', ') FROM genres JOIN movie_genres ON movie_genres.genre_id = genres.id WHERE movie_genres.movie_id = movies.id) as all_genres";
    return $this->select($select)->where("id", $id)->first();
  }

  /**
   * Extract full genre reference list tied to movie.
   * Returns dense conventional array of relational matrix data.
   *
   * @param int $movieId
   *
   * @return array
   */
  public function getGenres(int $movieId): array
  {
    $db = Database::connect();
    $builder = $db->table("genres");
    return $builder->join("movie_genres", "movie_genres.genre_id = genres.id")->where("movie_genres.movie_id", $movieId)->get()->getResultArray();
  }

  /**
   * Awaken single movie object based on unique slug.
   * Returns complete movie data or null if missing.
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
   * Copy exact precision of slug object retrieval function.
   * Returns complete array or null if none map.
   *
   * @param string $slug
   *
   * @return array|null
   */
  public function findBySlug(string $slug): ?array
  {
    return $this->where("slug", $slug)->first();
  }

  /**
   * Scan movie identity indexes sharing chosen genre matrix.
   * Returns pure integer array without duplicate primary numbers.
   *
   * @param array $genreIds
   *
   * @return array
   */
  public function getIdsByGenres(array $genreIds): array
  {
    if (empty($genreIds)) {
      return [];
    }

    $db = Database::connect();
    $builder = $db->table("movie_genres");

    $results = $builder->select("movie_id")->whereIn("genre_id", $genreIds)->get()->getResultArray();
    return array_unique(array_column($results, "movie_id"));
  }

  /**
   * Collect alternative recommendations from movies sharing similar categories.
   * Returns limited data excluding the caller movie itself.
   *
   * @param int $movieId
   * @param array $genreIds
   * @param int $limit
   *
   * @return array
   */
  public function getRelatedMovies(int $movieId, array $genreIds, int $limit = 6): array
  {
    if (empty($genreIds)) {
      return [];
    }

    $movieIds = $this->getIdsByGenres($genreIds);
    if (empty($movieIds)) {
      return [];
    }

    return $this->whereIn("id", $movieIds)->where("id !=", $movieId)->limit($limit)->find();
  }

  /**
   * Apply loose textual search on title and synopsis.
   * Returns chainable class instance with bundled query modifications.
   *
   * @param string $q
   *
   * @return self
   */
  public function search(string $q = ""): self
  {
    if (!empty($q)) {
      $this->groupStart()->like("movies.title", $q)->orLike("movies.synopsis", $q)->groupEnd();
    }

    return $this;
  }

  /**
   * Restrict query inspection to movies crossing chosen genre.
   * Returns object instance chain for further filtration clauses.
   *
   * @param int $genreId
   *
   * @return self
   */
  public function byGenre(int $genreId): self
  {
    $this->join("movie_genres", "movie_genres.movie_id = movies.id")->where("movie_genres.genre_id", $genreId);
    return $this;
  }

  /**
   * Arrange distribution queue based on sorting preference string.
   * Returns held query execution before final extraction command.
   *
   * @param string $sort
   *
   * @return self
   */
  public function sortBy(string $sort = "newest"): self
  {
    switch ($sort) {
      case "rating":
        $this->orderBy("movies.avg_rating", "DESC");
        break;
      case "oldest":
        $this->orderBy("movies.release_year", "ASC")->orderBy("movies.id", "ASC");
        break;
      case "newest":
      default:
        $this->orderBy("movies.release_year", "DESC")->orderBy("movies.id", "DESC");
        break;
    }

    return $this;
  }

  /**
   * Extract cinematic masterpieces with highest average star ratings.
   * Returns popular collection matrix optionally filtered by genre.
   *
   * @param int $limit
   * @param ?string $genreSlug
   *
   * @return array
   */
  public function getTopRated(int $limit = 10, ?string $genreSlug = null): array
  {
    $this->withTopGenre()->where("movies.status", "published")->orderBy("movies.avg_rating", "DESC")->limit($limit);

    if ($genreSlug) {
      $this->join("movie_genres", "movie_genres.movie_id = movies.id")->join("genres", "genres.id = movie_genres.genre_id")->where("genres.slug", $genreSlug);
    }

    return $this->find();
  }

  /**
   * Pick random compilation from cinematic library for exhibition.
   * Returns limited random matching records for showcase displays.
   *
   * @param int $limit
   *
   * @return array
   */
  public function getFeatured(int $limit = 6): array
  {
    return $this->withTopGenre()->where("status", "published")->orderBy("RAND()")->limit($limit)->find();
  }

  /**
   * Reset category association foundation for managed movie entity.
   * Destroys massive old links and plants new indices.
   *
   * @param int $movieId
   * @param array $genreIds
   *
   * @return void
   */
  public function syncGenres(int $movieId, array $genreIds): void
  {
    $db = Database::connect();
    $builder = $db->table("movie_genres");
    $builder->where("movie_id", $movieId)->delete();

    if (!empty($genreIds)) {
      $insertData = [];
      foreach ($genreIds as $gId) {
        $insertData[] = ["movie_id" => $movieId, "genre_id" => $gId];
      }

      $builder->insertBatch($insertData);
    }
  }

  /**
   * Fetch all movies with combined genre string for admin table.
   * Returns complete array data of movies.
   *
   * @return array
   */
  public function getAllAdmin(): array
  {
    $movies = $this->orderBy("movies.id", "DESC")->findAll();
    if (empty($movies)) {
      return [];
    }

    $movieIds = array_column($movies, "id");

    $db = Database::connect();
    $builder = $db->table("movie_genres");
    $builder->select("movie_genres.movie_id, genres.name");
    $builder->join("genres", "genres.id = movie_genres.genre_id");
    $builder->whereIn("movie_genres.movie_id", $movieIds);

    $genreResults = $builder->get()->getResultArray();

    $genreMap = [];
    foreach ($genreResults as $row) {
      $genreMap[$row["movie_id"]][] = $row["name"];
    }

    foreach ($movies as &$movie) {
      $movie["genres"] = isset($genreMap[$movie["id"]]) ? implode(", ", $genreMap[$movie["id"]]) : "";
    }

    return $movies;
  }
}
