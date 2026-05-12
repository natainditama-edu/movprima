<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Movie Model
 *
 * Handles movie querying, full-text search with filters,
 * and many-to-many genre synchronisation via the movie_genres pivot.
 */
class MovieModel extends Model
{
    protected $table          = 'movies';

    protected $primaryKey     = 'id';

    protected $returnType     = 'array';

    protected $useSoftDeletes = false;

    protected $useTimestamps  = true;

    protected $allowedFields  = [
        'title',
        'slug',
        'synopsis',
        'director',
        'release_year',
        'duration',
        'poster',
        'backdrop',
        'trailer_url',
        'language',
        'country',
        'status',
        'avg_rating',
        'review_count',
    ];

    /**
     * Return one movie row joined with its genres as a comma-separated string.
     * Uses a LEFT JOIN so movies with no genres are still returned.
     *
     * @param int $id Movie primary key
     *
     * @return array|null
     */
    public function getWithGenres(int $id): ?array
    {
        return null;
    }

    /**
     * Find a single published movie by its URL slug.
     * Returns null when not found.
     *
     * @param string $slug URL slug
     *
     * @return array|null
     */
    public function findBySlug(string $slug): ?array
    {
        return null;
    }

    /**
     * Search and filter movies with optional criteria.
     * Returns a flat array of all matching rows (no pagination applied here).
     *
     * @param string  $q         Full-text search on title
     * @param ?string $genreSlug Filter by genre slug
     * @param ?int    $year      Filter by release_year
     * @param string  $sort      'newest' | 'oldest' | 'rating' | 'title'
     *
     * @return array
     */
    public function search(
        string $q = '',
        ?string $genreSlug = null,
        ?int $year = null,
        string $sort = 'newest'
    ): array {
        return [];
    }

    /**
     * Return the top-rated published movies, optionally filtered by genre.
     * Results are ordered by avg_rating descending.
     *
     * @param int     $limit     Maximum number of rows to return
     * @param ?string $genreSlug Optional genre slug filter
     *
     * @return array
     */
    public function getTopRated(int $limit = 10, ?string $genreSlug = null): array
    {
        return [];
    }

    /**
     * Return a random selection of published movies for featured sections.
     * Uses ORDER BY RAND() — suitable for small catalogs only.
     *
     * @param int $limit Number of movies to return
     *
     * @return array
     */
    public function getFeatured(int $limit = 6): array
    {
        return [];
    }

    /**
     * Replace all genre associations for a movie.
     * Deletes existing pivot rows then inserts fresh ones.
     *
     * @param int   $movieId
     * @param int[] $genreIds
     *
     * @return void
     */
    public function syncGenres(int $movieId, array $genreIds): void {}
}
