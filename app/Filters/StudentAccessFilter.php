<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * StudentAccessFilter
 *
 * Blocks authenticated students from directly accessing restricted areas:
 * - admin/* routes  (Research Management, Scheduling, Audit Trail, Account Management, etc.)
 * - teacher/* routes
 *
 * This filter is applied globally (before every request) so that even if a student
 * manually types a restricted URL, they are redirected to their own dashboard.
 * All other roles are unaffected.
 */
class StudentAccessFilter implements FilterInterface
{
    // Paths that students are strictly NOT allowed to access (prefix match)
    protected array $blockedPrefixes = [
        'admin/',
        'sub-admin/',
        'teacher/',
        'department/',
    ];

    public function before(RequestInterface $request, $arguments = null)
    {
        helper('role');

        // Only enforce for logged-in students
        if (! session()->get('isLoggedIn') || ! is_student()) {
            return null; // Let other filters/routes handle non-students
        }

        $uri = ltrim((string) $request->getUri()->getPath(), '/');

        foreach ($this->blockedPrefixes as $prefix) {
            if (str_starts_with($uri, $prefix)) {
                return redirect()
                    ->to('/student/dashboard')
                    ->with('error', 'Access denied. Students are not allowed to access that area.');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
