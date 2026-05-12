<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\GenreModel;
use App\Models\MovieModel;
use App\Models\WatchlistModel;
use App\Models\ReviewModel;

/**
 * Movie Controller
 *
 * Public movie listing with search/filter and movie detail page.
 * Owner: Shyfa
 */
class Movie extends BaseController
{
    /**
     * GET /movies
     *
     * Reads q, genre, year, sort from query string, calls MovieModel::search(),
     * and paginates results at 12 per page.
     *
     * @return string
     */
    public function index(): string
    {
        return view('movies/index');
    }

    /**
     * GET /movies/{slug}
     *
     * Loads a single published movie by slug (404 if missing), fetches paginated
     * reviews, and checks watchlist status for the logged-in user.
     *
     * @param string $slug URL slug of the movie
     *
     * @return string
     */
    public function show(string $slug): string
    {
        return view('movies/detail');
    }
}
