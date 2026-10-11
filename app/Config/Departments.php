<?php

namespace Config;

class Departments
{
    public const LIST = [
        'cmat' => [
            'name' => 'CMAT',
            'category_names' => ['CMAT'],
            'accent' => '#0f766e',
        ],
        'ceas' => [
            'name' => 'CEAS',
            'category_names' => ['CEAS'],
            'accent' => '#b45309',
        ],
        'coc' => [
            'name' => 'Criminology',
            'category_names' => ['COC', 'Criminology', 'College of Criminology'],
            'accent' => '#7c2d12',
        ],
        'senior-high' => [
            'name' => 'Senior High',
            'category_names' => ['Senior High', 'Senior High School', 'High School Department'],
            'accent' => '#1d4ed8',
        ],
    ];

    public static function get(string $slug): ?array
    {
        return self::LIST[$slug] ?? null;
    }

    public static function choices(): array
    {
        $choices = ['' => 'No Department'];
        foreach (self::LIST as $slug => $department) {
            $choices[$slug] = $department['name'];
        }
        return $choices;
    }
}
