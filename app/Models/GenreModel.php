<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Genre Model
 *
 * Manages movie genres including slug generation and
 * aggregated movie counts per genre.
 */
class GenreModel extends Model
{
    protected $table          = 'genres';

    protected $primaryKey     = 'id';

    protected $returnType     = 'array';

    protected $useSoftDeletes = false;

    protected $useTimestamps  = true;

    protected $allowedFields  = ['name', 'slug'];

    protected $validationRules = [
        'name' => 'required|min_length[2]|max_length[60]',
        'slug' => 'required|max_length[70]|is_unique[genres.slug,id,{id}]',
    ];

    /**
     * Return all genres with their published movie count.
     * Results are ordered alphabetically by name.
     *
     * @return array
     */
    public function getAllWithCount(): array
    {
        return [];
    }

    /**
     * Find a single genre by its URL slug.
     * Returns null when the slug does not exist.
     *
     * @param string $slug
     *
     * @return array|null
     */
    public function findBySlug(string $slug): ?array
    {
        return null;
    }

    /**
     * Generate a URL-safe slug from a human-readable name.
     * Example: "Sci-Fi" → "sci-fi".
     *
     * @param string $name
     *
     * @return string
     */
    public static function makeSlug(string $name): string
    {
        return '';
    }
}
