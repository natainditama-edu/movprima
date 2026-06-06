<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ReviewModel;
use App\Models\UserModel;
use App\Models\WatchlistModel;
use CodeIgniter\HTTP\RedirectResponse;

/**
 * User Controller
 *
 * Manages the public user profile view and the profile editing form,
 * including avatar upload handling.
 * Owner: Gita
 */
class User extends BaseController
{
  /**
   * GET /profile
   *
   * Loads the session user's data, their reviews, and their watchlist
   * then renders the profile page.
   *
   * @return string|RedirectResponse
   */
  public function profile(): string|RedirectResponse
  {
    $userId = session()->get("user_id");
    if (!$userId) {
      return redirect()->to("/auth/login")->with("error", "Anda harus masuk terlebih dahulu.");
    }

    $userModel = new UserModel();
    $user = $userModel->findById($userId);

    if (!$user) {
      return redirect()->to("/auth/login")->with("error", "Sesi pengguna Anda tidak valid.");
    }

    $reviewModel = new ReviewModel();
    $reviews = $reviewModel->getByUser($userId);

    $watchlistModel = new WatchlistModel();
    $watchlist = $watchlistModel->getUserList($userId);

    return view("user/profile", [
      "user" => $user,
      "reviews" => $reviews,
      "watchlist" => $watchlist,
      "reviewCount" => count($reviews),
    ]);
  }

  /**
   * GET /profile/edit
   *
   * Loads the current user's data from the database
   * and renders the profile edit form.
   *
   * @return string|RedirectResponse
   */
  public function editForm(): string|RedirectResponse
  {
    $userId = session()->get("user_id");
    if (!$userId) {
      return redirect()->to("/auth/login")->with("error", "Anda harus masuk terlebih dahulu.");
    }

    $userModel = new UserModel();
    $user = $userModel->findById($userId);

    if (!$user) {
      return redirect()->to("/auth/login")->with("error", "Sesi pengguna Anda tidak valid.");
    }

    return view("user/edit", ["user" => $user]);
  }

  /**
   * POST /profile/edit
   *
   * Validates name and bio; handles avatar upload (max 1 MB, jpg/png/webp)
   * to public/uploads/avatars/; calls UserModel::updateProfile(); redirects to /profile.
   *
   * @return RedirectResponse
   */
  public function update(): RedirectResponse
  {
    $userId = session()->get("user_id");
    if (!$userId) {
      return redirect()->to("/auth/login")->with("error", "Anda harus masuk terlebih dahulu.");
    }

    $userModel = new UserModel();
    $rules = [
      "name" => "required|min_length[3]|max_length[100]",
      "email" => "required|valid_email|is_unique[users.email,id,$userId]",
    ];

    if ($this->request->getPost("password")) {
      $rules["password"] = "min_length[6]";
    }

    $messages = [
      "name" => [
        "required" => "Nama lengkap wajib diisi pada kolom yang tersedia.",
        "min_length" => "Nama lengkap harus memiliki minimal 3 karakter.",
        "max_length" => "Nama lengkap maksimal 100 karakter.",
      ],
      "email" => [
        "required" => "Alamat email wajib diisi pada kolom formulir.",
        "valid_email" => "Format alamat email tidak valid.",
        "is_unique" => "Alamat email tersebut sudah digunakan.",
      ],
      "password" => [
        "min_length" => "Kata sandi harus memiliki minimal 6 karakter.",
      ],
    ];

    if (!$this->validate($rules, $messages)) {
      return redirect()->back()->withInput()->with("errors", $this->validator->getErrors());
    }

    $data = [
      "name" => $this->request->getPost("name"),
      "email" => $this->request->getPost("email"),
    ];

    if ($this->request->getPost("password")) {
      $data["password"] = password_hash($this->request->getPost("password"), PASSWORD_DEFAULT);
    }

    if (!$userModel->updateProfile($userId, $data)) {
      return redirect()->back()->withInput()->with("error", "Sistem gagal memperbarui data profil Anda.");
    }

    session()->set([
      "user_name" => $data["name"],
      "user_email" => $data["email"],
    ]);

    return redirect()->to("/profile")->with("success", "Data profil Anda berhasil diperbarui.");
  }
}
