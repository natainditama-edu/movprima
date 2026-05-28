<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Watchlist Model
 *
 * Manages user specific movie watchlists inside the platform.
 * Handles status switching and filtering lists by categories.
 */
class WatchlistModel extends Model
{
  protected $table = "watchlist";
  protected $primaryKey = "id";
  protected $returnType = "array";
  protected $useSoftDeletes = false;
  protected $useTimestamps = true;
  protected $allowedFields = ["user_id", "movie_id", "status"];

  private const VALID_STATUSES = ["want_to_watch", "watching", "watched"];

  /**
   * Check if movie exists in user's watchlist.
   * Returns boolean true or false from specific search.
   *
   * @param int $userId
   * @param int $movieId
   *
   * @return bool
   */
  public function checkUserWatchlist(int $userId, int $movieId): bool
  {
    return (bool) $this->where("user_id", $userId)->where("movie_id", $movieId)->first();
  }

  /**
   * Fetch specific watchlist entry row from the database.
   * Returns associative array if found or null otherwise.
   *
   * @param int $userId
   * @param int $movieId
   *
   * @return array|null
   */
  public function findByUserAndMovie(int $userId, int $movieId): ?array
  {
    return $this->where("user_id", $userId)->where("movie_id", $movieId)->first();
  }

  /**
   * Toggle movie ownership status in user's watchlist.
   * Returns added for insertion and removed for deletion.
   *
   * @param int $userId
   * @param int $movieId
   *
   * @return string
   */
  public function toggle(int $userId, int $movieId): string
  {
    $existing = $this->findByUserAndMovie($userId, $movieId);

    if ($existing) {
      $this->delete($existing["id"]);
      return "removed";
    } else {
      $this->insert(["user_id" => $userId, "movie_id" => $movieId, "status" => "want_to_watch"]);
      return "added";
    }
  }

  /**
   * Update watch status on valid user entry record.
   * Returns boolean true on success or false otherwise.
   *
   * @param int    $id
   * @param string $status
   *
   * @return bool
   */
  public function updateStatus(int $id, string $status): bool
  {
    if (!in_array($status, self::VALID_STATUSES)) {
      return false;
    }

    return $this->update($id, ["status" => $status]);
  }

  /**
   * Fetch comprehensive user watchlist joined with movie info.
   * Returns latest sorted collection with optional status filter.
   *
   * @param int     $userId
   * @param ?string $status
   *
   * @return array
   */
  public function getUserList(int $userId, ?string $status = null): array
  {
    $builder = $this->select("watchlist.*, movies.title as movie_title, movies.poster as movie_poster, movies.slug as movie_slug, movies.avg_rating, movies.release_year")->join("movies", "movies.id = watchlist.movie_id")->where("watchlist.user_id", $userId);

    if ($status) {
      $builder->where("watchlist.status", $status);
    }

    return $builder->orderBy("watchlist.updated_at", "DESC")->findAll();
  }

  /**
   * Find specific watchlist row by its primary key.
   * Returns standard array if found or null otherwise.
   *
   * @param int $id
   *
   * @return array|null
   */
  public function getEntryById(int $id): ?array
  {
    return $this->find($id);
  }

  /**
   * Permanently destroy specific row from the watchlist table.
   * Returns boolean indicator of database procedure success.
   *
   * @param int $id
   *
   * @return bool
   */
  public function removeEntry(int $id): bool
  {
    return $this->delete($id);
  }
}
