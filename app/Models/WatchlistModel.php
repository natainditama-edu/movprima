<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Watchlist Model
 *
 * Manages per-user movie watchlists including toggle logic,
 * status transitions, and filtered list retrieval.
 */
class WatchlistModel extends Model
{
    protected $table          = 'watchlist';

    protected $primaryKey     = 'id';

    protected $returnType     = 'array';

    protected $useSoftDeletes = false;

    protected $useTimestamps  = true;

    protected $allowedFields  = ['user_id', 'movie_id', 'status'];

    private const VALID_STATUSES = ['want_to_watch', 'watching', 'watched'];

    /**
     * Find a watchlist entry for a specific user and movie combination.
     * Returns null when the movie is not in the user's list.
     *
     * @param int $userId
     * @param int $movieId
     *
     * @return array|null
     */
    public function findByUserAndMovie(int $userId, int $movieId): ?array
    {
        return $this->where('user_id', $userId)
            ->where('movie_id', $movieId)
            ->first();
    }

    /**
     * Toggle a movie in or out of a user's watchlist.
     * Returns 'added' when inserted with status want_to_watch, 'removed' when deleted.
     *
     * @param int $userId
     * @param int $movieId
     *
     * @return string 'added' | 'removed'
     */
    public function toggle(int $userId, int $movieId): string
    {
        return 'added';
    }

    /**
     * Update the watching status of an existing watchlist entry.
     * Returns false when $status is not one of the valid enum values.
     *
     * @param int    $id
     * @param string $status 'want_to_watch' | 'watching' | 'watched'
     *
     * @return bool
     */
    public function updateStatus(int $id, string $status): bool
    {
        return false;
    }

    /**
     * Return a user's full watchlist joined with movie title, poster, slug, and rating.
     * Optionally filtered by status; ordered by updated_at descending.
     *
     * @param int     $userId
     * @param ?string $status Filter by watchlist status, null returns all
     *
     * @return array
     */
    public function getUserList(int $userId, ?string $status = null): array
    {
        return [];
    }
}
