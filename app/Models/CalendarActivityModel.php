<?php

namespace App\Models;

use CodeIgniter\Model;

class CalendarActivityModel extends Model
{
    public const TYPE_TITLE_HEARING = 'title_hearing';
    public const TYPE_PRE_ORAL      = 'pre_oral';
    public const TYPE_FINAL_DEFENSE = 'final_defense';

    public const STATUS_APPROVED  = 'approved';
    public const STATUS_PENDING   = 'pending';
    public const STATUS_CANCELLED = 'cancelled';

    protected $table            = 'calendar_activities';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'activity_type',
        'title',
        'description',
        'start_date',
        'end_date',
        'status',
        'academic_year',
        'created_by',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'activity_type' => 'required|in_list[title_hearing,pre_oral,final_defense]',
        'title'         => 'required|min_length[3]|max_length[255]',
        'start_date'    => 'required|valid_date',
        'end_date'      => 'required|valid_date',
        'status'        => 'required|in_list[approved,pending,cancelled]',
    ];

    public static function typeLabels(): array
    {
        return [
            self::TYPE_TITLE_HEARING => 'Title Hearing',
            self::TYPE_PRE_ORAL      => 'Pre-Oral Defense',
            self::TYPE_FINAL_DEFENSE => 'Final Defense',
        ];
    }

    public static function typeLabel(?string $type): string
    {
        return self::typeLabels()[$type] ?? ucwords(str_replace('_', ' ', (string) $type));
    }

    public static function statusLabels(): array
    {
        return [
            self::STATUS_APPROVED  => 'Approved',
            self::STATUS_PENDING   => 'Pending',
            self::STATUS_CANCELLED => 'Cancelled',
        ];
    }

    public static function statusLabel(?string $status): string
    {
        return self::statusLabels()[$status] ?? ucfirst((string) $status);
    }

    protected function tableExists(string $table): bool
    {
        try {
            return $this->db()->tableExists($table);
        } catch (\Throwable $e) {
            return false;
        }
    }

    public function isReady(): bool
    {
        return $this->tableExists($this->table);
    }

    /**
     * Get all approved activities, optionally filtered by activity type.
     */
    public function getApprovedActivities(?string $type = null): array
    {
        if (!$this->isReady()) {
            return [];
        }

        $query = $this->where('status', self::STATUS_APPROVED);
        if ($type !== null && array_key_exists($type, self::typeLabels())) {
            $query->where('activity_type', $type);
        }

        return $query->orderBy('start_date', 'ASC')->findAll();
    }

    /**
     * Check if a specific date falls within any approved activity window for the given defense type.
     */
    public function isDateAllowed(string $date, string $type): bool
    {
        if (!$this->isReady() || empty($date) || empty($type)) {
            return false;
        }

        $matched = $this->where('status', self::STATUS_APPROVED)
            ->where('activity_type', $type)
            ->where('start_date <=', $date)
            ->where('end_date >=', $date)
            ->first();

        return $matched !== null;
    }

    /**
     * Get array of allowed date ranges for a defense type.
     */
    public function getAllowedDateRanges(string $type): array
    {
        if (!$this->isReady() || empty($type)) {
            return [];
        }

        $activities = $this->where('status', self::STATUS_APPROVED)
            ->where('activity_type', $type)
            ->orderBy('start_date', 'ASC')
            ->findAll();

        $ranges = [];
        foreach ($activities as $act) {
            $ranges[] = [
                'id'            => (int) $act['id'],
                'title'         => $act['title'],
                'start_date'    => $act['start_date'],
                'end_date'      => $act['end_date'],
                'academic_year' => $act['academic_year'] ?? '',
            ];
        }

        return $ranges;
    }

    /**
     * Get a map of allowed date ranges for all defense types.
     */
    public function getAllAllowedDateRangesMap(): array
    {
        return [
            self::TYPE_TITLE_HEARING => $this->getAllowedDateRanges(self::TYPE_TITLE_HEARING),
            self::TYPE_PRE_ORAL      => $this->getAllowedDateRanges(self::TYPE_PRE_ORAL),
            self::TYPE_FINAL_DEFENSE => $this->getAllowedDateRanges(self::TYPE_FINAL_DEFENSE),
        ];
    }

    /**
     * Get a map of individual approved dates (with formatted labels) for each defense type.
     */
    public function getAllowedIndividualDatesMap(): array
    {
        if (!$this->isReady()) {
            return [
                self::TYPE_TITLE_HEARING => [],
                self::TYPE_PRE_ORAL      => [],
                self::TYPE_FINAL_DEFENSE => [],
            ];
        }

        $types = [
            self::TYPE_TITLE_HEARING,
            self::TYPE_PRE_ORAL,
            self::TYPE_FINAL_DEFENSE,
        ];

        $result = [];
        foreach ($types as $type) {
            $result[$type] = [];
            $activities = $this->where('status', self::STATUS_APPROVED)
                ->where('activity_type', $type)
                ->orderBy('start_date', 'ASC')
                ->findAll();

            $seen = [];
            foreach ($activities as $act) {
                try {
                    $period = new \DatePeriod(
                        new \DateTime($act['start_date']),
                        new \DateInterval('P1D'),
                        (new \DateTime($act['end_date']))->modify('+1 day')
                    );

                    foreach ($period as $dt) {
                        $dateStr = $dt->format('Y-m-d');
                        if (isset($seen[$dateStr])) {
                            continue;
                        }
                        $seen[$dateStr] = true;

                        $result[$type][] = [
                            'date'           => $dateStr,
                            'formatted'      => $dt->format('M d, Y (D)'),
                            'activity_title' => $act['title'],
                            'academic_year'  => $act['academic_year'] ?? '',
                        ];
                    }
                } catch (\Throwable $e) {
                    // skip invalid dates
                }
            }
        }

        return $result;
    }

    /**
     * Get activities spanning across a specific month & year for the calendar view.
     */
    public function getByMonth(int $month, int $year, ?string $type = null): array
    {
        if (!$this->isReady()) {
            return [];
        }

        $startDate = sprintf('%04d-%02d-01', $year, $month);
        $endDate   = date('Y-m-t', strtotime($startDate));

        $query = $this->groupStart()
            ->where('start_date <=', $endDate)
            ->where('end_date >=', $startDate)
            ->groupEnd();

        if ($type !== null && array_key_exists($type, self::typeLabels())) {
            $query->where('activity_type', $type);
        }

        return $query->orderBy('start_date', 'ASC')->findAll();
    }
}
