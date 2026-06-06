<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

/**
 * Admin User Controller
 *
 * User account management: paginated searchable listing and deletion
 * with self-delete prevention.
 * Owner: Gita
 */
class User extends BaseController
{
  /**
   * GET /admin/users
   *
   * Lists all users with review count, searchable by name or email,
   * paginated at 20 per page.
   *
   * @return string
   */
  public function index(): string
  {
    $userModel = new UserModel();
    return view("admin/users/index", [
      "users" => $userModel->getWithReviewCount(),
    ]);
  }

  /**
   * POST /admin/users/{id}/delete
   *
   * Prevents self-deletion by comparing $id with session('user_id');
   * deletes the user account and redirects to /admin/users.
   *
   * @param int $id User primary key
   *
   * @return \CodeIgniter\HTTP\RedirectResponse
   */
  public function destroy(int $id): \CodeIgniter\HTTP\RedirectResponse
  {
    if ($id === (int) session()->get("user_id")) {
      return redirect()->to("/admin/users")->with("error", "Anda tidak diizinkan untuk menghapus akun Anda sendiri.");
    }

    $userModel = new UserModel();
    $userModel->delete($id);

    return redirect()->to("/admin/users")->with("success", "Akun pengguna berhasil dihapus dari sistem secara permanen.");
  }
}
