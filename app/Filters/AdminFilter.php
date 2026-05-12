<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Admin Filter
 *
 * Protects admin routes by requiring an active session with role = 'admin'.
 * Any other visitor is redirected to the homepage with an access-denied message.
 */
class AdminFilter implements FilterInterface
{
    /**
     * Redirect to homepage when the user is not logged in or does not have the admin role.
     * Returns null to allow the request to continue when the check passes.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return \CodeIgniter\HTTP\RedirectResponse|null
     */
    public function before(RequestInterface $request, $arguments = null): mixed
    {
        $userId   = session()->get('user_id');
        $userRole = session()->get('user_role');

        if (! $userId || $userRole !== 'admin') {
            return redirect()
                ->to('/')
                ->with('error', 'Access denied. Admin privileges required.');
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
