<?php

namespace Config;

class Roles
{
    public const STUDENT = 'student';
    public const TEACHER = 'teacher';
    public const COORDINATOR = 'coordinator';
    public const DIRECTOR = 'director';
    public const RESEARCH_STAFF = 'research_staff';
    public const SUB_ADMIN = 'sub_admin';
    public const ADMIN = 'admin';

    public const LEGACY_MAP = [
        'instructor' => self::TEACHER,
        'teacher_archive_viewer' => self::TEACHER,
        'researcher_archive_viewer' => self::RESEARCH_STAFF,
    ];

    public const LABELS = [
        self::STUDENT => 'Student',
        self::TEACHER => 'Teacher/Research Instructor',
        self::COORDINATOR => 'Coordinator',
        self::DIRECTOR => 'Director',
        self::RESEARCH_STAFF => 'Research Staff',
        self::SUB_ADMIN => 'Sub-Admin',
        self::ADMIN => 'Admin',
        'instructor' => 'Teacher/Research Instructor',
        'teacher_archive_viewer' => 'Teacher/Research Instructor',
        'researcher_archive_viewer' => 'Research Staff',
    ];

    public const LOGIN_CHOICES = [
        self::STUDENT => 'Student',
        self::TEACHER => 'Teacher/Research Instructor',
        self::COORDINATOR => 'Coordinator',
        self::DIRECTOR => 'Director',
        self::RESEARCH_STAFF => 'Research Staff',
        self::SUB_ADMIN => 'Sub-Admin',
        self::ADMIN => 'Admin',
    ];

    public static function allAssignable(): array
    {
        return [
            self::STUDENT,
            self::TEACHER,
            self::COORDINATOR,
            self::DIRECTOR,
            self::RESEARCH_STAFF,
            self::SUB_ADMIN,
            self::ADMIN,
        ];
    }

    public static function matchesLoginRole(?string $accountRole, ?string $selectedRole): bool
    {
        $selectedRole = (string) $selectedRole;
        if (! array_key_exists($selectedRole, self::LOGIN_CHOICES)) {
            return false;
        }

        return self::normalize($accountRole) === $selectedRole;
    }

    public static function validationList(): string
    {
        return implode(',', array_unique(array_merge(
            self::allAssignable(),
            array_keys(self::LEGACY_MAP)
        )));
    }

    public static function normalize(?string $role): string
    {
        $role = (string) $role;
        return self::LEGACY_MAP[$role] ?? $role;
    }

    public static function teacherRoles(): array
    {
        return [self::TEACHER, 'instructor', 'teacher_archive_viewer'];
    }

    public static function studentRoles(): array
    {
        return [self::STUDENT];
    }

    public static function researchOfficeRoles(): array
    {
        return [
            self::ADMIN,
            self::SUB_ADMIN,
            self::COORDINATOR,
            self::DIRECTOR,
            self::RESEARCH_STAFF,
            'researcher_archive_viewer',
        ];
    }

    public static function isStudent(?string $role = null): bool
    {
        return in_array(self::normalize($role ?? session()->get('role')), self::studentRoles(), true);
    }

    public static function isTeacher(?string $role = null): bool
    {
        return in_array(self::normalize($role ?? session()->get('role')), [self::TEACHER], true)
            || in_array((string) ($role ?? session()->get('role')), self::teacherRoles(), true);
    }

    public static function isSubAdmin(?string $role = null): bool
    {
        return self::normalize($role ?? session()->get('role')) === self::SUB_ADMIN;
    }

    public static function isAdmin(?string $role = null): bool
    {
        return self::normalize($role ?? session()->get('role')) === self::ADMIN;
    }

    public static function isResearchOffice(?string $role = null): bool
    {
        $current = (string) ($role ?? session()->get('role'));
        return in_array($current, self::researchOfficeRoles(), true)
            || in_array(self::normalize($current), [self::ADMIN, self::SUB_ADMIN, self::COORDINATOR, self::DIRECTOR, self::RESEARCH_STAFF], true);
    }

    public static function canManageUsers(?string $role = null): bool
    {
        return self::isAdmin($role);
    }

    public static function home(?string $role = null): string
    {
        $current = (string) ($role ?? session()->get('role'));
        if (self::isStudent($current)) {
            return '/student/dashboard';
        }
        if (self::isTeacher($current)) {
            return '/teacher/dashboard';
        }
        if (self::isSubAdmin($current)) {
            return '/sub-admin/dashboard';
        }
        if (self::isAdmin($current) || self::isResearchOffice($current)) {
            return '/admin/dashboard';
        }
        return '/user/dashboard';
    }

    public static function label(?string $role = null): string
    {
        $current = (string) ($role ?? session()->get('role'));
        return self::LABELS[$current] ?? ucwords(str_replace('_', ' ', $current));
    }
}
