<?php

use Config\Roles;

if (! function_exists('current_role')) {
    function current_role(): string
    {
        return (string) session()->get('role');
    }
}

if (! function_exists('is_student')) {
    function is_student(?string $role = null): bool
    {
        return Roles::isStudent($role);
    }
}

if (! function_exists('is_teacher')) {
    function is_teacher(?string $role = null): bool
    {
        return Roles::isTeacher($role);
    }
}

if (! function_exists('is_admin')) {
    function is_admin(?string $role = null): bool
    {
        return Roles::isAdmin($role);
    }
}

if (! function_exists('is_sub_admin')) {
    function is_sub_admin(?string $role = null): bool
    {
        return Roles::isSubAdmin($role);
    }
}

if (! function_exists('is_research_office')) {
    function is_research_office(?string $role = null): bool
    {
        return Roles::isResearchOffice($role);
    }
}

if (! function_exists('can_manage_users')) {
    function can_manage_users(?string $role = null): bool
    {
        return Roles::canManageUsers($role);
    }
}

if (! function_exists('role_home')) {
    function role_home(?string $role = null): string
    {
        return Roles::home($role);
    }
}

if (! function_exists('role_label')) {
    function role_label(?string $role = null): string
    {
        return Roles::label($role);
    }
}

if (! function_exists('unauthorized_redirect')) {
    function unauthorized_redirect(string $message = 'Unauthorized access')
    {
        $role = current_role();
        if ($role === '') {
            return redirect()->to('/login')->with('error', 'Please login first');
        }
        return redirect()->to(role_home($role))->with('error', $message);
    }
}
