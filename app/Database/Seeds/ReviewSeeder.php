<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class ReviewSeeder extends Seeder
{
  public function run()
  {
    $now = Time::now("Asia/Jakarta", "en_US")->format("Y-m-d H:i:s");

    $users = $this->db->table("users")->select("id")->get()->getResultArray();
    $userIds = array_column($users, "id");

    $movies = $this->db->table("movies")->select("id")->get()->getResultArray();
    $movieIds = array_column($movies, "id");

    if (empty($userIds) || empty($movieIds)) {
      echo "  No users or movies found for ReviewSeeder.\n";
      return;
    }

    $reviewsData = [];
    $watchlistData = [];
    $likesData = [];
    $movieStats = []; // movie_id => ['total_rating' => X, 'count' => Y]

    // Review templates
    $goodReviews = [
      ["Luar biasa!", "Film ini sangat mengesankan dari awal hingga akhir. Aktingnya memukau."],
      ["Sangat merekomendasikan", "Jalan cerita yang kuat dan visual yang sangat memanjakan mata."],
      ["Masterpiece", "Sutradara berhasil menyampaikan emosi yang sangat dalam. Saya menangis."],
      ["Seru banget", "Tidak ada momen membosankan. Adegannya penuh aksi dan ketegangan."],
      ["Menarik", "Konsep cerita yang segar dan belum pernah ada sebelumnya."],
    ];

    $badReviews = [
      ["Mengecewakan", "Ekspektasi saya terlalu tinggi, ternyata ceritanya membosankan."],
      ["Kurang greget", "Banyak lubang plot dan aktingnya terasa kaku."],
      ["Buang-buang waktu", "Durasi terlalu lama untuk cerita yang sebenarnya bisa dipersingkat."],
      ["Biasa saja", "Tidak ada yang spesial dari film ini, sangat mudah ditebak."],
    ];

    foreach ($movieIds as $mId) {
      $movieStats[$mId] = ["total_rating" => 0, "count" => 0];

      // Randomly select how many reviews for this movie (0 to 8)
      $numReviews = rand(1, 8);

      // Shuffle users to get unique reviewers
      shuffle($userIds);
      $reviewers = array_slice($userIds, 0, $numReviews);

      foreach ($reviewers as $uId) {
        // Determine if it's a good or bad review (80% good)
        $isGood = rand(1, 100) <= 80;
        $rating = $isGood ? rand(7, 10) : rand(1, 6);
        $revText = $isGood ? $goodReviews[array_rand($goodReviews)] : $badReviews[array_rand($badReviews)];

        $reviewsData[] = [
          "user_id" => $uId,
          "movie_id" => $mId,
          "rating" => $rating,
          "title" => $revText[0],
          "body" => $revText[1],
          "is_spoiler" => rand(0, 100) > 90 ? 1 : 0, // 10% chance of spoiler
          "likes_count" => 0, // Will be updated later if likes added
          "status" => "published",
          "created_at" => $now,
          "updated_at" => $now,
        ];

        $movieStats[$mId]["total_rating"] += $rating;
        $movieStats[$mId]["count"]++;
      }

      // Random watchlist entries (people who want to watch or have watched)
      $numWatchlist = rand(2, 10);
      shuffle($userIds);
      $wlUsers = array_slice($userIds, 0, $numWatchlist);
      $wlStatuses = ["want_to_watch", "watching", "watched"];
      foreach ($wlUsers as $wId) {
        $watchlistData[] = [
          "user_id" => $wId,
          "movie_id" => $mId,
          "status" => $wlStatuses[array_rand($wlStatuses)],
          "created_at" => $now,
          "updated_at" => $now,
        ];
      }
    }

    // Insert reviews
    if (!empty($reviewsData)) {
      $this->db->table("reviews")->insertBatch($reviewsData);
    }

    // Fetch inserted reviews to assign likes
    $insertedReviews = $this->db->table("reviews")->select("id, user_id")->get()->getResultArray();
    foreach ($insertedReviews as $r) {
      // Random likes for each review (0 to 5 likes)
      $numLikes = rand(0, 5);
      if ($numLikes == 0) {
        continue;
      }

      shuffle($userIds);
      $likers = array_slice($userIds, 0, $numLikes);
      $actualLikes = 0;
      foreach ($likers as $lId) {
        if ($lId == $r["user_id"]) {
          continue;
        } // Can't like own review
        $likesData[] = [
          "user_id" => $lId,
          "review_id" => $r["id"],
          "created_at" => $now,
        ];
        $actualLikes++;
      }

      // Update review likes count
      if ($actualLikes > 0) {
        $this->db
          ->table("reviews")
          ->where("id", $r["id"])
          ->update(["likes_count" => $actualLikes]);
      }
    }

    if (!empty($likesData)) {
      $this->db->table("review_likes")->insertBatch($likesData);
    }

    if (!empty($watchlistData)) {
      $this->db->table("watchlist")->insertBatch($watchlistData);
    }

    // Synchronize movies table avg_rating and review_count
    foreach ($movieStats as $mId => $stats) {
      $count = $stats["count"];
      $avg = $count > 0 ? round($stats["total_rating"] / $count, 2) : 0;
      $this->db
        ->table("movies")
        ->where("id", $mId)
        ->update([
          "avg_rating" => $avg,
          "review_count" => $count,
        ]);
    }

    echo "  Seeded: " . count($reviewsData) . " reviews, " . count($likesData) . " likes, " . count($watchlistData) . " watchlist items. Movies fully synchronized.\n";
  }
}
