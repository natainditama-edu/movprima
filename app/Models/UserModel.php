<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * User Model
 *
 * Manages user accounts including authentication, profile updates,
 * and password hashing via model callbacks.
 */
class UserModel extends Model
{
    protected $table          = 'users';

    protected $primaryKey     = 'id';

    protected $returnType     = 'array';

    protected $useSoftDeletes = false;

    protected $useTimestamps  = true;

    protected $createdField   = 'created_at';

    protected $updatedField   = 'updated_at';

    protected $allowedFields  = [
        'name',
        'email',
        'password',
        'role',
        'avatar',
        'bio',
        'email_verified_at',
    ];

    protected $beforeInsert = ['hashPassword'];

    protected $beforeUpdate = ['hashPassword'];

    protected $validationRules = [
        'name'  => 'required|min_length[2]|max_length[100]',
        'email' => 'required|valid_email|max_length[150]',
    ];

    /**
     * Hash the password field before insert or update.
     * Skips hashing when the password key is absent or empty.
     *
     * @param array $data CI4 callback data bag
     *
     * @return array
     */
    protected function hashPassword(array $data): array
    {
        return $data;
    }

    /**
     * Find a single user by their email address.
     * Returns null when no matching record exists.
     *
     * @param string $email
     *
     * @return array|null
     */
    public function findByEmail(string $email): ?array
    {
        return null;
    }

    /**
     * Update only the allowed profile fields (name, bio) for a user.
     * Ignores any other keys present in $data.
     *
     * @param int   $id
     * @param array $data Associative array with keys 'name' and/or 'bio'
     *
     * @return bool
     */
    public function updateProfile(int $id, array $data): bool
    {
        return false;
    }

    /**
     * Update the avatar file path for a specific user.
     * Replaces any previously stored path.
     *
     * @param int    $id         User primary key
     * @param string $avatarPath Relative path under FCPATH
     *
     * @return bool
     */
    public function updateAvatar(int $id, string $avatarPath): bool
    {
        return false;
    }
}
