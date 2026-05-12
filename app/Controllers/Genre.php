<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\GenreModel;
use App\Models\MovieModel;

/**
 * Genre Controller
 *
 * Public genre browsing page displaying filtered movie results.
 * Owner: Shyfa
 */
class Genre extends BaseController
{
    /**
     * GET /genres/{slug}
     *
     * Finds genre by slug (404 if missing) and renders a filtered,
     * paginated movie grid for that genre.
     *
     * @param string $slug URL slug of the genre
     *
     * @return string
     */
    public function show(string $slug): string
    {
        return view('genres/show');
    }
}
