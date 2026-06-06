<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\HTTP\RedirectResponse;

/**
 * Auth Controller
 *
 * Handles user authentication: login form, login processing,
 * registration form, registration processing, and logout.
 * Owner: Riski
 */
class Auth extends BaseController
{
  /**
   * GET /auth/login
   *
   * Renders the login form.
   * Redirects to homepage if the user already has an active session.
   *
   * @return string|RedirectResponse
   */
  public function loginForm(): string|RedirectResponse
  {
    if (session()->get("user_id")) {
      return redirect()->to("/")->with("error", "");
    }

    return view("auth/login");
  }

  /**
   * POST /auth/login
   *
   * Validates credentials, verifies password hash, sets session data,
   * and redirects admin to /admin or regular users to /.
   *
   * @return RedirectResponse
   */
  public function login(): RedirectResponse
  {
    $rules = [
      "email" => "required|valid_email",
      "password" => "required|min_length[6]",
    ];

    $messages = [
      "email" => [
        "required" => "Alamat email wajib diisi pada kolom formulir.",
        "valid_email" => "Format alamat email tidak valid.",
      ],
      "password" => [
        "required" => "Kolom kata sandi wajib diisi oleh pengguna.",
        "min_length" => "Kata sandi harus memiliki minimal 6 karakter.",
      ],
    ];

    if (!$this->validate($rules, $messages)) {
      return redirect()->back()->withInput()->with("errors", $this->validator->getErrors());
    }

    $userModel = new UserModel();
    $user = $userModel->findByEmail($this->request->getPost("email"));
    if (!$user || !password_verify($this->request->getPost("password"), $user["password"])) {
      return redirect()->back()->withInput()->with("error", "Kredensial login yang Anda masukkan tidak valid.");
    }

    session()->set([
      "user_id" => $user["id"],
      "user_name" => $user["name"],
      "user_email" => $user["email"],
      "user_role" => $user["role"],
    ]);

    return $user["role"] === "admin" ? redirect()->to("/admin")->with("success", "Anda telah masuk ke dalam sistem sebagai admin.") : redirect()->to("/")->with("success", "Anda telah masuk ke dalam sistem sebagai pengguna.");
  }

  /**
   * GET /auth/register
   *
   * Renders the registration form.
   * Redirects to homepage if the user already has an active session.
   *
   * @return string|RedirectResponse
   */
  public function registerForm(): string|RedirectResponse
  {
    if (session()->get("user_id")) {
      return redirect()->to("/")->with("error", "Anda sudah masuk ke dalam sistem.");
    }

    return view("auth/register");
  }

  /**
   * POST /auth/register
   *
   * Validates input, hashes password, inserts user with role=user,
   * sets session data, and redirects to homepage.
   *
   * @return RedirectResponse
   */
  public function register(): RedirectResponse
  {
    $rules = [
      "name" => "required|min_length[2]|max_length[100]",
      "email" => "required|valid_email|is_unique[users.email]",
      "password" => "required|min_length[8]",
      "password_confirm" => "required|matches[password]",
    ];

    $messages = [
      "name" => [
        "required" => "Nama lengkap wajib diisi pada kolom yang tersedia.",
        "min_length" => "Nama lengkap harus memiliki minimal 2 karakter.",
        "max_length" => "Nama lengkap maksimal 100 karakter.",
      ],
      "email" => [
        "required" => "Alamat email wajib diisi pada kolom formulir.",
        "valid_email" => "Format alamat email tidak valid.",
        "is_unique" => "Alamat email tersebut sudah terdaftar.",
      ],
      "password" => [
        "required" => "Kolom kata sandi wajib diisi oleh pengguna.",
        "min_length" => "Kata sandi harus memiliki minimal 8 karakter.",
      ],
      "password_confirm" => [
        "required" => "Konfirmasi kata sandi wajib diisi.",
        "matches" => "Konfirmasi kata sandi tidak sesuai.",
      ],
    ];

    if (!$this->validate($rules, $messages)) {
      return redirect()->back()->withInput()->with("errors", $this->validator->getErrors());
    }

    $userModel = new UserModel();
    $userId = $userModel->registerUser([
      "name" => $this->request->getPost("name"),
      "email" => $this->request->getPost("email"),
      "password" => password_hash($this->request->getPost("password"), PASSWORD_DEFAULT),
      "role" => "user",
    ]);

    $user = $userModel->find($userId);
    session()->set([
      "user_id" => $user["id"],
      "user_name" => $user["name"],
      "user_email" => $user["email"],
      "user_role" => $user["role"],
    ]);

    return redirect()->to("/")->with("success", "Proses registrasi akun baru Anda telah berhasil dilakukan.");
  }

  /**
   * GET /auth/logout
   *
   * Destroys the current session and redirects to the login page.
   *
   * @return RedirectResponse
   */
  public function logout(): RedirectResponse
  {
    session()->destroy();
    return redirect()->to("/auth/login")->with("success", "Anda telah berhasil keluar dari sistem.");
  }
}
