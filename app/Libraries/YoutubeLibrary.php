<?php

namespace App\Libraries;

/**
 * YoutubeLibrary
 *
 * Custom library for fetching YouTube video reviews via YouTube Data API v3.
 * Follows CI4 Libraries convention under App\Libraries namespace.
 */
class YoutubeLibrary
{
  /**
   * Fetch the top 3 YouTube video reviews for a given movie title.
   * Uses 24-hour caching to prevent excessive API calls and rate-limiting.
   *
   * @param string $movieTitle
   * @return array
   */
  public function getVideoReviews(string $movieTitle): array
  {
    $apiKey = getenv("YOUTUBE_API_KEY");
    if (empty($apiKey)) {
      log_message("info", "[YoutubeLibrary] API key not found, skipping fetch for: " . $movieTitle);
      return [];
    }

    $query = $movieTitle . " review indonesia";
    $cacheKey = "youtube_reviews_" . md5(strtolower(trim($query)));
    $cache = \Config\Services::cache();

    if ($cached = $cache->get($cacheKey)) {
      log_message("info", "[YoutubeLibrary] Cache hit for: " . $movieTitle);
      return $cached;
    }

    $url =
      "https://www.googleapis.com/youtube/v3/search?" .
      http_build_query([
        "part" => "snippet",
        "q" => $query,
        "type" => "video",
        "key" => $apiKey,
        "maxResults" => 3,
        "relevanceLanguage" => "id",
      ]);

    $ch = curl_init();
    curl_setopt_array($ch, [
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_TIMEOUT => 5,
      CURLOPT_FAILONERROR => false,
    ]);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    if ($curlError) {
      log_message("error", '[YoutubeLibrary] cURL error while fetching "' . $movieTitle . '": ' . $curlError);
      return [];
    }

    $videos = [];

    if ($httpCode === 200 && $response) {
      $data = json_decode($response, true);

      // Handle API-level errors (e.g. invalid key, quota exceeded)
      if (isset($data["error"])) {
        log_message("error", '[YoutubeLibrary] API error for "' . $movieTitle . '": ' . ($data["error"]["message"] ?? "unknown"));
        return [];
      }

      if (isset($data["items"]) && is_array($data["items"])) {
        $channelIds = [];
        foreach ($data["items"] as $item) {
          if (isset($item["id"]["videoId"])) {
            $channelId = $item["snippet"]["channelId"] ?? "";
            if ($channelId) {
              $channelIds[] = $channelId;
            }
            $videos[] = [
              "video_id" => $item["id"]["videoId"],
              "title" => $item["snippet"]["title"] ?? "",
              "thumbnail" => $item["snippet"]["thumbnails"]["high"]["url"] ?? ($item["snippet"]["thumbnails"]["medium"]["url"] ?? ""),
              "channel_id" => $channelId,
              "channel_title" => $item["snippet"]["channelTitle"] ?? "",
              "channel_avatar" => "",
              "publish_date" => $item["snippet"]["publishedAt"] ?? "",
            ];
          }
        }

        // Fetch channel avatars
        if (!empty($channelIds)) {
            $channelUrl = "https://www.googleapis.com/youtube/v3/channels?" . http_build_query([
                "part" => "snippet",
                "id" => implode(",", array_unique($channelIds)),
                "key" => $apiKey
            ]);
            $chChannels = curl_init();
            curl_setopt_array($chChannels, [
                CURLOPT_URL => $channelUrl,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 5,
                CURLOPT_FAILONERROR => false,
            ]);
            $channelRes = curl_exec($chChannels);
            $chCode = curl_getinfo($chChannels, CURLINFO_HTTP_CODE);
            curl_close($chChannels);

            if ($chCode === 200 && $channelRes) {
                $channelData = json_decode($channelRes, true);
                if (isset($channelData["items"])) {
                    $avatars = [];
                    foreach ($channelData["items"] as $chItem) {
                        $avatars[$chItem["id"]] = $chItem["snippet"]["thumbnails"]["default"]["url"] ?? "";
                    }
                    // Map avatars back to videos
                    foreach ($videos as &$v) {
                        if (isset($avatars[$v["channel_id"]])) {
                            $v["channel_avatar"] = $avatars[$v["channel_id"]];
                        }
                    }
                }
            }
        }
      }

      if (empty($videos)) {
        log_message("info", "[YoutubeLibrary] Successfully fetched but no videos found for: " . $movieTitle);
      } else {
        log_message("info", "[YoutubeLibrary] Successfully fetched " . count($videos) . " videos for: " . $movieTitle);
        $cache->save($cacheKey, $videos, 86400);
      }
    } else {
      log_message("error", "[YoutubeLibrary] HTTP " . $httpCode . ' while fetching "' . $movieTitle . '"');
    }

    return $videos;
  }
}
