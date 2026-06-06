<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\WatchlistModel;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\Response;

/**
 * Watchlist Controller
 *
 * Manages the per-user movie watchlist with AJAX toggle support
 * and status update / removal actions.
 * Owner: Shyfa
 */
class Watchlist extends BaseController
{
  /**
   * POST /watchlist  [AJAX only]
   *
   * Reads movie_id from JSON body; calls WatchlistModel::toggle();
   * returns JSON with keys 'status' (string) and 'message' (string).
   *
   * @return Response
   */
  public function store(): Response
  {
    $userId = session()->get("user_id");
    if (!$userId) {
      return $this->response->setJSON([
        "success" => false,
        "message" => "Anda harus masuk terlebih dahulu untuk melanjutkan akses.",
      ]);
    }

    $json = $this->request->getJSON();
    $movieId = $json->movie_id ?? $this->request->getPost("movie_id");
    if (!$movieId) {
      return $this->response->setJSON([
        "success" => false,
        "message" => "Identitas film wajib disertakan dalam permintaan ini.",
      ]);
    }

    $watchlistModel = new WatchlistModel();
    $status = $watchlistModel->toggle($userId, $movieId);

    return $this->response->setJSON([
      "success" => true,
      "status" => $status,
      "message" => $status == "added" ? "Film berhasil ditambahkan ke daftar tontonan." : "Film berhasil dihapus dari daftar tontonan.",
    ]);
  }

  /**
   * POST /watchlist/{id}/status
   *
   * Asserts the session user owns this watchlist entry;
   * updates the watching status; redirects back.
   *
   * @param int $id Watchlist entry primary key
   *
   * @return RedirectResponse
   */
  public function update(int $id): RedirectResponse
  {
    $userId = session()->get("user_id");
    if (!$userId) {
      return redirect()->to("/auth/login")->with("error", "Anda harus masuk terlebih dahulu untuk melanjutkan akses.");
    }

    $watchlistModel = new WatchlistModel();
    $entry = $watchlistModel->getEntryById($id);

    if (!$entry) {
      return redirect()->back()->with("error", "Data daftar tontonan tidak ditemukan.");
    }

    if ($entry["user_id"] != $userId) {
      return redirect()->back()->with("error", "Anda tidak memiliki hak akses untuk tindakan ini.");
    }

    $status = $this->request->getPost("status");
    if ($watchlistModel->updateStatus($id, $status)) {
      return redirect()->back()->with("success", "Status tontonan film berhasil diperbarui.");
    }

    return redirect()->back()->with("error", "Status tontonan yang dikirimkan tidak valid.");
  }

  /**
   * POST /watchlist/{id}/delete
   *
   * Asserts the session user owns this watchlist entry;
   * removes the entry; redirects to profile.
   *
   * @param int $id Watchlist entry primary key
   *
   * @return RedirectResponse
   */
  public function destroy(int $id): RedirectResponse
  {
    $userId = session()->get("user_id");
    if (!$userId) {
      return redirect()->to("/auth/login")->with("error", "Anda harus masuk terlebih dahulu untuk melanjutkan akses.");
    }

    $watchlistModel = new WatchlistModel();
    $entry = $watchlistModel->getEntryById($id);

    if (!$entry) {
      return redirect()->back()->with("error", "Data daftar tontonan tidak ditemukan.");
    }

    if ($entry["user_id"] != $userId) {
      return redirect()->back()->with("error", "Anda tidak memiliki hak akses untuk tindakan ini.");
    }

    $watchlistModel->removeEntry($id);
    return redirect()->back()->with("success", "Film berhasil dihapus dari daftar tontonan.");
  }
}
