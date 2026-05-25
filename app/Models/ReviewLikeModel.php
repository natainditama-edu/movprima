<?php

namespace App\Models;

use CodeIgniter\Model;
use Config\Database;

/**
 * ReviewLike Model
 *
 * Manages user like interactions on movie review items.
 * Responsible for syncing total likes on reviews table.
 */
class ReviewLikeModel extends Model
{
  protected $table = "review_likes";
  protected $primaryKey = "id";
  protected $returnType = "array";
  protected $useSoftDeletes = false;
  protected $useTimestamps = false;
  protected $allowedFields = ["user_id", "review_id", "created_at"];

  /**
   * Process adding or removing a like on review.
   * Returns true if liked and false if removed.
   *
   * @param int $userId
   * @param int $reviewId
   *
   * @return bool
   */
  public function toggle(int $userId, int $reviewId): bool
  {
    $existing = $this->where("user_id", $userId)->where("review_id", $reviewId)->first();

    if ($existing) {
      $this->delete($existing["id"]);
      return false;
    } else {
      $this->insert(["user_id" => $userId, "review_id" => $reviewId]);
      return true;
    }
  }

  /**
   * Check if specific user liked a specific review.
   * Returns boolean true if found or false otherwise.
   *
   * @param int $userId
   * @param int $reviewId
   *
   * @return bool
   */
  public function hasLiked(int $userId, int $reviewId): bool
  {
    return (bool) $this->where("user_id", $userId)->where("review_id", $reviewId)->first();
  }

  /**
   * Sync total likes directly into the reviews table.
   * Executes an update query using valid aggregated rows.
   *
   * @param int $reviewId
   *
   * @return void
   */
  public function syncCounter(int $reviewId): void
  {
    $count = $this->where("review_id", $reviewId)->countAllResults();
    $db = Database::connect();

    $db
      ->table("reviews")
      ->where("id", $reviewId)
      ->update(["likes_count" => $count]);
  }
}
