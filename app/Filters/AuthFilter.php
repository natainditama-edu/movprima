<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Auth Filter
 *
 * Protects routes that require a logged-in user.
 * Redirects unauthenticated guests to the login page with a flash message.
 */
class AuthFilter implements FilterInterface
{
    /**
     * Redirect the request to /auth/login when no active user session exists.
     * Returns null to allow the request to proceed when authenticated.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return \CodeIgniter\HTTP\RedirectResponse|null
     */
    public function before(RequestInterface $request, $arguments = null): mixed
    {
        if (! session()->get('user_id')) {
            return redirect()
                ->to('/auth/login')
                ->with('error', 'You must be logged in to access that page.');
        }

        return null;
    }

    /**
     * No-op after handler required by FilterInterface.
     * Returns null so the response pipeline continues unmodified.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return null
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null): mixed
    {
        return null;
    }
}
