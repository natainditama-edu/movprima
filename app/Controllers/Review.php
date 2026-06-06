<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ReviewModel;
use App\Models\ReviewLikeModel;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\Response;

/**
 * Review Controller
 *
 * Full CRUD for user reviews including AJAX like toggling.
 * Ownership is enforced on edit, update, and destroy.
 * Owner: Shyfa
 */
class Review extends BaseController
{
  /**
   * POST /reviews
   *
   * Validates rating (1–10), title, and body; checks for duplicate user+movie
   * review; inserts the review; calls updateMovieRating(); redirects to movie detail.
   *
   * @return RedirectResponse
   */
  public function store(): RedirectResponse
  {
    $userId = session()->get("user_id");
    if (!$userId && empty($userId)) {
      return redirect()->to("/auth/login")->with("error", "Anda harus masuk terlebih dahulu untuk melanjutkan akses.");
    }

    $reviewModel = new ReviewModel();
    $movieId = $this->request->getPost("movie_id");

    $existing = $reviewModel->hasUserReviewed($movieId, $userId);
    if ($existing) {
      return redirect()->back()->with("error", "Anda sudah memberikan ulasan untuk film ini.");
    }

    $data = [
      "user_id" => $userId,
      "movie_id" => $movieId,
      "rating" => $this->request->getPost("rating"),
      "title" => $this->request->getPost("title"),
      "body" => $this->request->getPost("body"),
      "is_spoiler" => 0,
      "status" => "published",
    ];

    if (!$reviewModel->addReview($data)) {
      return redirect()->back()->withInput()->with("error", implode(", ", $reviewModel->errors()));
    }

    $reviewModel->updateMovieRating($movieId);
    return redirect()->back()->with("success", "Ulasan Anda telah berhasil disimpan ke dalam sistem.");
  }

  /**
   * POST /reviews/{id}/update
   *
   * Validates the same rules as store(); updates the review record;
   * calls updateMovieRating(); redirects to the movie detail page.
   *
   * @param int $id Review primary key
   *
   * @return RedirectResponse
   */
  public function update(int $id): RedirectResponse
  {
    $userId = session()->get("user_id");
    if (!$userId) {
      return redirect()->to("/auth/login")->with("error", "Anda harus masuk terlebih dahulu untuk melanjutkan akses.");
    }

    $reviewModel = new ReviewModel();
    $review = $reviewModel->getReviewById($id);

    if (!$review) {
      return redirect()->back()->with("error", "Data ulasan tersebut tidak ditemukan.");
    }

    if ($review["user_id"] != $userId) {
      return redirect()->back()->with("error", "Anda tidak memiliki hak akses untuk memodifikasi ulasan ini.");
    }

    $data = [
      "rating" => $this->request->getPost("rating"),
      "title" => $this->request->getPost("title"),
      "body" => $this->request->getPost("body"),
      "is_spoiler" => 0,
    ];

    if (!$reviewModel->updateReview($id, $data)) {
      return redirect()->back()->withInput()->with("error", implode(", ", $reviewModel->errors()));
    }

    $reviewModel->updateMovieRating($review["movie_id"]);
    return redirect()->back()->with("success", "Ulasan Anda telah berhasil diperbarui di dalam sistem.");
  }

  /**
   * POST /reviews/{id}/delete
   *
   * Asserts ownership or admin role; deletes the review;
   * calls updateMovieRating(); redirects to the movie detail page.
   *
   * @param int $id Review primary key
   *
   * @return RedirectResponse
   */
  public function destroy(int $id): RedirectResponse
  {
    $userId = session()->get("user_id");
    if (!$userId) {
      return redirect()->to("/auth/login")->with("error", "Anda harus masuk terlebih dahulu untuk melanjutkan akses.");
    }

    $reviewModel = new ReviewModel();
    $review = $reviewModel->getReviewById($id);

    if (!$review) {
      return redirect()->back()->with("error", "Data ulasan tersebut tidak ditemukan.");
    }

    if ($review["user_id"] != $userId) {
      return redirect()->back()->with("error", "Anda tidak memiliki hak akses untuk memodifikasi ulasan ini.");
    }

    $reviewModel->deleteReview($id);
    $reviewModel->updateMovieRating($review["movie_id"]);

    return redirect()->back()->with("success", "Ulasan Anda berhasil dihapus dari sistem.");
  }

  /**
   * POST /reviews/{id}/like  [AJAX only]
   *
   * Toggles the like for the session user; syncs the denormalized counter;
   * returns JSON with keys 'liked' (bool) and 'count' (int).
   *
   * @param int $id Review primary key
   *
   * @return Response
   */
  public function like(int $id): Response
  {
    $userId = session()->get("user_id");
    if (!$userId) {
      return $this->response->setJSON([
        "success" => false,
        "message" => "Anda harus masuk terlebih dahulu untuk melanjutkan akses.",
      ]);
    }

    $likeModel = new ReviewLikeModel();
    $liked = $likeModel->toggle($userId, $id);
    $likeModel->syncCounter($id);

    $reviewModel = new ReviewModel();
    $review = $reviewModel->getReviewById($id);

    return $this->response->setJSON([
      "success" => true,
      "message" => "Operasi yang Anda minta telah berhasil dilakukan secara sempurna.",
      "liked" => $liked,
      "count" => (int) ($review["likes_count"] ?? 0),
    ]);
  }
}
