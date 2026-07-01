<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\GenreModel;
use App\Models\MovieModel;

class MovieSeeder extends Seeder
{
  /**
   * Perform a cURL GET request and return the decoded JSON body.
   * Returns null if the HTTP status is not 200 or the response is invalid.
   *
   * @param string $url Target API URL.
   * @return array|null Decoded JSON as array, or null on failure.
   */
  private function tmdbGet(string $url): ?array
  {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if ($httpCode !== 200 || !$response) {
      return null;
    }

    $data = json_decode($response, true);
    return is_array($data) ? $data : null;
  }

  /**
   * Extract the director name from the TMDB crew array.
   * Iterates through crew members and returns the first person with job = 'Director'.
   *
   * @param array $crew Crew members from TMDB credits response.
   * @return string|null Director name, or null if not found.
   */
  private function extractDirector(array $crew): ?string
  {
    foreach ($crew as $member) {
      if (($member["job"] ?? "") === "Director") {
        return $member["name"] ?? null;
      }
    }
    return null;
  }

  /**
   * Extract the first YouTube trailer URL from TMDB video results.
   * Returns a full watch URL or null if no YouTube trailer is found.
   *
   * @param array $videos Video results from TMDB response.
   * @return string|null Full YouTube watch URL, or null if not found.
   */
  private function extractTrailer(array $videos): ?string
  {
    foreach ($videos as $video) {
      if (($video["type"] ?? "") === "Trailer" && ($video["site"] ?? "") === "YouTube") {
        return "https://www.youtube.com/watch?v=" . $video["key"];
      }
    }
    return null;
  }

  /**
   * Import a single movie by its TMDB ID into the database.
   * Fetches detail, credits, and videos in Indonesian with English fallback.
   *
   * @param int        $tmdbId     TMDB movie ID to import.
   * @param string     $apiKey     TMDB API key.
   * @param MovieModel $movieModel Active MovieModel instance.
   * @param GenreModel $genreModel Active GenreModel instance.
   * @return bool True on success, false on failure.
   */
  private function importMovie(int $tmdbId, string $apiKey, MovieModel $movieModel, GenreModel $genreModel): bool
  {
    $url = "https://api.themoviedb.org/3/movie/{$tmdbId}?api_key={$apiKey}&language=id-ID&append_to_response=credits,videos";
    $data = $this->tmdbGet($url);

    if (!$data || !isset($data["title"])) {
      return false;
    }

    // Director
    $director = null;
    if (!empty($data["credits"]["crew"]) && is_array($data["credits"]["crew"])) {
      $director = $this->extractDirector($data["credits"]["crew"]);
    }

    // Trailer
    $trailerUrl = null;
    if (!empty($data["videos"]["results"]) && is_array($data["videos"]["results"])) {
      $trailerUrl = $this->extractTrailer($data["videos"]["results"]);
    }

    // Synopsis
    $synopsis = $data["overview"] ?? "";

    // Fallback to English if synopsis or trailer is missing
    if (empty($synopsis) || empty($trailerUrl)) {
      $fallback = $this->tmdbGet("https://api.themoviedb.org/3/movie/{$tmdbId}?api_key={$apiKey}&append_to_response=videos");
      if ($fallback) {
        if (empty($synopsis) && !empty($fallback["overview"])) {
          $synopsis = $fallback["overview"];
        }
        if (empty($trailerUrl) && !empty($fallback["videos"]["results"])) {
          $trailerUrl = $this->extractTrailer($fallback["videos"]["results"]);
        }
      }
    }

    if (empty($synopsis)) {
      $synopsis = "Belum ada sinopsis.";
    }

    // Country
    $country = null;
    if (!empty($data["production_countries"]) && is_array($data["production_countries"])) {
      $country = $data["production_countries"][0]["name"] ?? null;
    }

    // Language
    $language = "English";
    if (!empty($data["spoken_languages"]) && is_array($data["spoken_languages"])) {
      $language = $data["spoken_languages"][0]["english_name"] ?? ($data["spoken_languages"][0]["name"] ?? "English");
    } elseif (!empty($data["original_language"])) {
      $language = strtoupper($data["original_language"]);
    }

    // Images & year
    $posterUrl = !empty($data["poster_path"]) ? "https://image.tmdb.org/t/p/w500" . $data["poster_path"] : null;
    $backdropUrl = !empty($data["backdrop_path"]) ? "https://image.tmdb.org/t/p/w1280" . $data["backdrop_path"] : null;
    $releaseYear = !empty($data["release_date"]) ? (int) substr($data["release_date"], 0, 4) : null;

    if ($movieModel->movieExists($data["title"], $releaseYear)) {
      return false;
    }

    $slug = $movieModel->generateUniqueSlug($data["title"]);

    $row = [
      "title" => $data["title"],
      "slug" => $slug,
      "synopsis" => $synopsis,
      "director" => $director,
      "release_year" => $releaseYear,
      "duration" => $data["runtime"] ?? null,
      "poster" => $posterUrl,
      "backdrop" => $backdropUrl,
      "trailer_url" => $trailerUrl,
      "language" => $language,
      "country" => $country,
      "status" => "published",
    ];

    $movieId = $movieModel->insert($row);
    if (!$movieId) {
      return false;
    }

    // Genres
    if (!empty($data["genres"]) && is_array($data["genres"])) {
      $genreIds = $genreModel->syncTmdbGenres($data["genres"]);
      $movieModel->syncGenres($movieId, $genreIds);
    }

    return true;
  }

  /**
   * Seed 111 movies by collecting TMDB IDs from discovery endpoint lists.
   * Uses deduplication and rate-limit pauses to import each movie cleanly.
   *
   * @return void
   */
  public function run(): void
  {
    $apiKey = getenv("TMDB_API_KEY");

    if (empty($apiKey) || $apiKey === "masukkan_key_disini") {
      echo "  [ERROR] TMDB_API_KEY belum diatur di .env. Seeder dibatalkan.\n";
      return;
    }

    $movieModel = new MovieModel();
    $genreModel = new GenreModel();

    // Collect 111 unique TMDB IDs from discovery endpoints
    $tmdbIds = [];
    $endpoints = ["popular", "top_rated", "now_playing", "upcoming"];
    $maxPages = 6;
    $target = 111;

    foreach ($endpoints as $endpoint) {
      if (count($tmdbIds) >= $target) {
        break;
      }
      for ($page = 1; $page <= $maxPages; $page++) {
        if (count($tmdbIds) >= $target) {
          break;
        }
        $url = "https://api.themoviedb.org/3/movie/{$endpoint}?api_key={$apiKey}&language=id-ID&page={$page}";
        $data = $this->tmdbGet($url);
        if (!$data || empty($data["results"])) {
          break;
        }
        foreach ($data["results"] as $movie) {
          if (!empty($movie["id"])) {
            $tmdbIds[$movie["id"]] = true;
          }
          if (count($tmdbIds) >= $target) {
            break;
          }
        }
        usleep(100000); // 100 ms pause between list requests
      }
    }

    $tmdbIds = array_keys($tmdbIds);
    $total = min(count($tmdbIds), $target);

    echo "  Mengimpor {$total} film dari TMDB...\n";

    $success = 0;
    $fail = 0;

    for ($i = 0; $i < $total; $i++) {
      $id = (int) $tmdbIds[$i];
      $ok = $this->importMovie($id, $apiKey, $movieModel, $genreModel);
      if ($ok) {
        $success++;
        echo "  [{$success}/{$total}] OK  TMDB #{$id}\n";
      } else {
        $fail++;
        echo "  [SKIP] TMDB #{$id} gagal.\n";
      }
      usleep(200000); // 200 ms pause between detail requests
    }

    echo "  Selesai: {$success} berhasil, {$fail} gagal.\n";
  }
}
