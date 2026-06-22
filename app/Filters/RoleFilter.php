<?php

namespace App\Filters;

use App\Libraries\DemoSession;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

class RoleFilter implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return RequestInterface|ResponseInterface|string|void
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        DemoSession::ensureLoggedIn();

        $session = session();

        $user = $session->get('user');

        if (! $user) {
            return redirect()->to('/');
        }

        // roles passed in routes: ['admin', 'pharmacist']
        $allowedRoles = $arguments ?? [];

        $currentRole = strtolower(trim((string) ($user['role'] ?? '')));
        $allowedRoles = array_map(static fn ($role) => strtolower(trim((string) $role)), $allowedRoles);

        if (! in_array($currentRole, $allowedRoles, true)) {
            // forbidden
            return Services::response()
                ->setStatusCode(403)
                ->setBody('Forbidden: insufficient role');
        }
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return ResponseInterface|void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
