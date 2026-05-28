<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * User Model
 *
 * Manages user account structures including system authentication processes.
 * Handles profile updates and secure password cryptographic hashing.
 */
class UserModel extends Model
{
  protected $table = "users";
  protected $primaryKey = "id";
  protected $returnType = "array";
  protected $useSoftDeletes = false;
  protected $useTimestamps = true;
  protected $createdField = "created_at";
  protected $updatedField = "updated_at";

  protected $allowedFields = ["name", "email", "password", "role", "bio", "email_verified_at"];

  protected $beforeInsert = ["hashPassword"];
  protected $beforeUpdate = ["hashPassword"];

  protected $validationRules = [
    "name" => "required|min_length[2]|max_length[100]",
    "email" => "required|valid_email|max_length[150]",
  ];

  /**
   * Encrypt password securely before insertion or update.
   * Ignores encryption operation if password is not sent.
   *
   * @param array $data
   *
   * @return array
   */
  protected function hashPassword(array $data): array
  {
    if (isset($data["data"]["password"])) {
      $data["data"]["password"] = password_hash($data["data"]["password"], PASSWORD_DEFAULT);
    }

    return $data;
  }

  /**
   * Fetch specific user data row by email address.
   * Returns profile array collection if matched or null.
   *
   * @param string $email
   *
   * @return array|null
   */
  public function findByEmail(string $email): ?array
  {
    return $this->where("email", $email)->first();
  }

  /**
   * Update allowed information columns on user profile data.
   * Executes internal database procedure and returns success flag.
   *
   * @param int   $id
   * @param array $data
   *
   * @return bool
   */
  public function updateProfile(int $id, array $data): bool
  {
    return $this->update($id, $data);
  }

  /**
   * Find full single profile structure using primary key.
   * Returns associative array if the record is found.
   *
   * @param int $id
   *
   * @return array|null
   */
  public function findById(int $id): ?array
  {
    return $this->find($id);
  }

  /**
   * Insert new profile form data to relational database.
   * Returns new primary integer ID allocated by database.
   *
   * @param array $data
   *
   * @return int
   */
  public function registerUser(array $data): int
  {
    return $this->insert($data);
  }

  /**
   * Fetch all users and include their total published review counts.
   * Returns complete array data of users with additional review_count field.
   *
   * @return array
   */
  public function getWithReviewCount(): array
  {
    return $this->select("users.*, (SELECT COUNT(*) FROM reviews WHERE reviews.user_id = users.id AND reviews.status = 'published') as review_count")->orderBy("users.id", "DESC")->findAll();
  }
}
