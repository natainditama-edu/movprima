<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

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
     * @return string|\CodeIgniter\HTTP\RedirectResponse
     */
    public function loginForm(): string|\CodeIgniter\HTTP\RedirectResponse
    {
        return view('auth/login');
    }

    /**
     * POST /auth/login
     *
     * Validates credentials, verifies password hash, sets session data,
     * and redirects admin to /admin or regular users to /.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function login(): \CodeIgniter\HTTP\RedirectResponse
    {
        return redirect()->to('/');
    }

    /**
     * GET /auth/register
     *
     * Renders the registration form.
     * Redirects to homepage if the user already has an active session.
     *
     * @return string|\CodeIgniter\HTTP\RedirectResponse
     */
    public function registerForm(): string|\CodeIgniter\HTTP\RedirectResponse
    {
        return view('auth/register');
    }

    /**
     * POST /auth/register
     *
     * Validates input, hashes password, inserts user with role=user,
     * sets session data, and redirects to homepage.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function register(): \CodeIgniter\HTTP\RedirectResponse
    {
        return redirect()->to('/');
    }

    /**
     * GET /auth/logout
     *
     * Destroys the current session and redirects to the login page.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function logout(): \CodeIgniter\HTTP\RedirectResponse
    {
        return redirect()->to('/auth/login');
    }
}
