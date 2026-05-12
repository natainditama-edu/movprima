<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * ReviewLike Model
 *
 * Manages likes on reviews with toggle logic and
 * keeps the denormalized likes_count column in sync.
 */
class ReviewLikeModel extends Model
{
    protected $table          = 'review_likes';

    protected $primaryKey     = 'id';

    protected $returnType     = 'array';

    protected $useSoftDeletes = false;

    protected $useTimestamps  = false;

    protected $allowedFields  = ['user_id', 'review_id', 'created_at'];

    /**
     * Toggle a like for a user on a given review.
     * Returns true when the user now likes the review, false when unliked.
     *
     * @param int $userId
     * @param int $reviewId
     *
     * @return bool
     */
    public function toggle(int $userId, int $reviewId): bool
    {
        return false;
    }

    /**
     * Check whether a user has already liked a specific review.
     * Returns true if a like record exists, false otherwise.
     *
     * @param int $userId
     * @param int $reviewId
     *
     * @return bool
     */
    public function hasLiked(int $userId, int $reviewId): bool
    {
        return false;
    }

    /**
     * Sync the denormalized likes_count column on the reviews table.
     * Counts all rows in review_likes for the given review id.
     *
     * @param int $reviewId
     *
     * @return void
     */
    public function syncCounter(int $reviewId): void {}
}
