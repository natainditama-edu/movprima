<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Review Model
 *
 * Manages movie reviews including listing by movie or user,
 * and atomic denormalized rating updates on the movies table.
 */
class ReviewModel extends Model
{
    protected $table          = 'reviews';

    protected $primaryKey     = 'id';

    protected $returnType     = 'array';

    protected $useSoftDeletes = false;

    protected $useTimestamps  = true;

    protected $allowedFields  = [
        'user_id',
        'movie_id',
        'rating',
        'title',
        'body',
        'is_spoiler',
        'likes_count',
        'status',
    ];

    protected $validationRules = [
        'rating' => 'required|integer|greater_than_equal_to[1]|less_than_equal_to[10]',
        'title'  => 'required|min_length[3]|max_length[200]',
        'body'   => 'required|min_length[10]',
    ];

    /**
     * Return paginated reviews for a movie joined with reviewer name and avatar.
     * Only published reviews are returned, ordered newest-first.
     *
     * @param int $movieId
     * @param int $perPage Maximum rows to return
     *
     * @return array
     */
    public function getByMovie(int $movieId, int $perPage = 5): array
    {
        return [];
    }

    /**
     * Return all reviews written by a user joined with movie title, poster, and slug.
     * Ordered newest-first.
     *
     * @param int $userId
     *
     * @return array
     */
    public function getByUser(int $userId): array
    {
        return [];
    }

    /**
     * Return the N most recent published reviews joined with movie and user info.
     * Used on the homepage latest-reviews strip.
     *
     * @param int $limit Maximum number of reviews to return
     *
     * @return array
     */
    public function getLatest(int $limit = 5): array
    {
        return [];
    }

    /**
     * Recalculate and persist avg_rating and review_count on the movies table.
     * Uses a single atomic UPDATE with subqueries to avoid race conditions.
     *
     * @param int $movieId
     *
     * @return void
     */
    public function updateMovieRating(int $movieId): void {}
}
