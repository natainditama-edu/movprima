<?php

namespace App\Libraries;

/**
 * OmdbLibrary
 *
 * Custom library for fetching external movie ratings from the OMDb API.
 * Follows CI4 Libraries convention under App\Libraries namespace.
 */
class OmdbLibrary
{
  /**
   * Fetch ratings (Rotten Tomatoes & Metacritic) for a given movie title and year.
   * Uses 24-hour caching to prevent excessive API calls and rate-limiting.
   *
   * @param string $title
   * @param int|null $year
   * @return array
   */
  public function getRatings(string $title, ?int $year = null): array
  {
    $apiKey = getenv("OMDB_API_KEY");
    if (empty($apiKey)) {
      log_message("info", "[OmdbLibrary] API key not found, skipping fetch for: " . $title);
      return [];
    }

    // Build a unique cache key based on title and year
    $cacheKey = "omdb_" . md5(strtolower(trim($title)) . ($year ?? ""));
    $cache = \Config\Services::cache();

    if ($cached = $cache->get($cacheKey)) {
      log_message("info", "[OmdbLibrary] Cache hit for: " . $title);
      return $cached;
    }

    $url = "http://www.omdbapi.com/?apikey=" . urlencode($apiKey) . "&t=" . urlencode($title);
    if ($year) {
      $url .= "&y=" . urlencode((string) $year);
    }

    $ch = curl_init();
    curl_setopt_array($ch, [
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_TIMEOUT => 5,
      CURLOPT_FAILONERROR => true,
    ]);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    if ($curlError) {
      log_message("error", '[OmdbLibrary] cURL error while fetching "' . $title . '": ' . $curlError);
      return [];
    }

    $result = ["imdb" => null, "rotten_tomatoes" => null, "metacritic" => null];

    if ($httpCode === 200 && $response) {
      $data = json_decode($response, true);

      if (!isset($data["Response"]) || $data["Response"] !== "True") {
        log_message("info", "[OmdbLibrary] Movie not found in OMDb: " . $title . " (" . ($data["Error"] ?? "no error msg") . ")");
        return [];
      }

      foreach ($data["Ratings"] ?? [] as $rating) {
        if ($rating["Source"] === "Internet Movie Database") {
          $result["imdb"] = $rating["Value"];
        }
        if ($rating["Source"] === "Rotten Tomatoes") {
          $result["rotten_tomatoes"] = $rating["Value"];
        }
        if ($rating["Source"] === "Metacritic") {
          $result["metacritic"] = $rating["Value"];
        }
      }

      // Fallback: use top-level Metascore if not found in Ratings array
      if (empty($result["metacritic"]) && !empty($data["Metascore"]) && $data["Metascore"] !== "N/A") {
        $result["metacritic"] = $data["Metascore"] . "/100";
      }

      // Fallback: use top-level imdbRating if not found in Ratings array
      if (empty($result["imdb"]) && !empty($data["imdbRating"]) && $data["imdbRating"] !== "N/A") {
        $result["imdb"] = $data["imdbRating"] . "/10";
      }

      if (empty($result["imdb"]) && empty($result["rotten_tomatoes"]) && empty($result["metacritic"])) {
        log_message("info", '[OmdbLibrary] Successfully fetched "' . $title . '" but no ratings available.');
      } else {
        log_message("info", '[OmdbLibrary] Successfully fetched ratings for "' . $title . '": IMDb=' . ($result["imdb"] ?? "-") . ", RT=" . ($result["rotten_tomatoes"] ?? "-") . ", MC=" . ($result["metacritic"] ?? "-"));
        $cache->save($cacheKey, $result, 86400);
      }
    } else {
      log_message("error", "[OmdbLibrary] HTTP " . $httpCode . ' while fetching "' . $title . '"');
    }

    return $result;
  }
}
