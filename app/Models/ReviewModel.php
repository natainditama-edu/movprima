<?php

namespace App\Models;

use CodeIgniter\Model;
use Config\Database;

/**
 * Review Model
 *
 * Manages structures and storage of user submitted movie reviews.
 * Handles identity relationships and aggregates rating matrix synchronization.
 */
class ReviewModel extends Model
{
  protected $table = "reviews";
  protected $primaryKey = "id";
  protected $returnType = "array";
  protected $useSoftDeletes = false;
  protected $useTimestamps = true;

  protected $allowedFields = ["user_id", "movie_id", "rating", "title", "body", "is_spoiler", "likes_count", "status"];

  protected $validationRules = [
    "rating" => "required|integer|greater_than_equal_to[1]|less_than_equal_to[10]",
    "title" => "required|min_length[3]|max_length[200]",
    "body" => "required|min_length[10]",
  ];

  /**
   * Fetch published review rows for a specific movie.
   * Returns paginated matrix array ordered by descending time.
   *
   * @param int $movieId
   * @param int|null $userId
   * @param int $perPage
   *
   * @return array
   */
  public function getByMovie(int $movieId, ?int $userId = null, int $perPage = 5): array
  {
    $builder = $this->select("reviews.*, users.name as user_name")->join("users", "users.id = reviews.user_id")->where("reviews.movie_id", $movieId)->where("reviews.status", "published")->orderBy("reviews.created_at", "DESC");

    if ($userId) {
      $builder->select("IF(review_likes.user_id IS NOT NULL, 1, 0) as is_liked")->join("review_likes", "review_likes.review_id = reviews.id AND review_likes.user_id = " . $userId, "left");
    } else {
      $builder->select("0 as is_liked");
    }

    return $builder->findAll($perPage);
  }

  /**
   * Extract full connected review records submitted by user.
   * Matches relational information to titles and movie slugs.
   *
   * @param int $userId
   *
   * @return array
   */
  public function getByUser(int $userId): array
  {
    return $this->select("reviews.*, movies.title as movie_title, movies.poster as movie_poster, movies.slug as movie_slug")->join("movies", "movies.id = reviews.movie_id")->where("reviews.user_id", $userId)->orderBy("reviews.created_at", "DESC")->findAll();
  }

  /**
   * Fetch sample group of latest published reviews globally.
   * Returns merged data with user and movie info.
   *
   * @param int $limit
   *
   * @return array
   */
  public function getLatest(int $limit = 5): array
  {
    return $this->select("reviews.*, users.name as user_name, movies.title as movie_title, movies.slug as movie_slug")->join("users", "users.id = reviews.user_id")->join("movies", "movies.id = reviews.movie_id")->where("reviews.status", "published")->orderBy("reviews.created_at", "DESC")->limit($limit)->find();
  }

  /**
   * Verify if user published a review for movie.
   * Returns solid boolean logic proposition from database hit.
   *
   * @param int $movieId
   * @param int $userId
   *
   * @return bool
   */
  public function hasUserReviewed(int $movieId, int $userId): bool
  {
    return (bool) $this->where("movie_id", $movieId)->where("user_id", $userId)->first();
  }

  /**
   * Recalculate cumulative average stars and save to movie.
   * Executes high level query to prevent race conditions.
   *
   * @param int $movieId
   *
   * @return void
   */
  public function updateMovieRating(int $movieId): void
  {
    $db = Database::connect();

    $db->query(
      "UPDATE movies SET
            avg_rating = (SELECT COALESCE(AVG(rating), 0) FROM reviews WHERE movie_id = ? AND status = 'published'),
            review_count = (SELECT COUNT(*) FROM reviews WHERE movie_id = ? AND status = 'published')
            WHERE id = ?",
      [$movieId, $movieId, $movieId],
    );
  }

  /**
   * Find specific single review record by primary key.
   * Returns informative array collection if cross key matches.
   *
   * @param int $id
   *
   * @return array|null
   */
  public function getReviewById(int $id): ?array
  {
    return $this->find($id);
  }

  /**
   * Insert new user review data into secondary table.
   * Returns success status of row injection into database.
   *
   * @param array $data
   *
   * @return bool
   */
  public function addReview(array $data): bool
  {
    return (bool) $this->insert($data);
  }

  /**
   * Revise partial attributes of older review from author.
   * Returns boolean success light if write is clean.
   *
   * @param int $id
   * @param array $data
   *
   * @return bool
   */
  public function updateReview(int $id, array $data): bool
  {
    return $this->update($id, $data);
  }

  /**
   * Permanently destroy absolute single review from physical memory.
   * Returns boolean indication of successful information asset destruction.
   *
   * @param int $id
   *
   * @return bool
   */
  public function deleteReview(int $id): bool
  {
    return $this->delete($id);
  }

  /**
   * Fetch all reviews with related user and movie data for admin panel.
   * Returns merged complete array matrix.
   *
   * @return array
   */
  public function getAllAdmin(): array
  {
    return $this->select("reviews.*, users.name as user_name, movies.title as movie_title, movies.slug as movie_slug")->join("users", "users.id = reviews.user_id", "left")->join("movies", "movies.id = reviews.movie_id", "left")->orderBy("reviews.created_at", "DESC")->findAll();
  }
}
