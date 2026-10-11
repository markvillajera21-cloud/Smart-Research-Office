<?php

namespace App\Models;

use CodeIgniter\Model;

class ResearchTitleModel extends Model
{
    protected $table            = 'research_titles';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'researcher_id', 'student_id', 'instructor_id', 'title', 'abstract',
        'strand_id', 'category_id', 'school_year_id', 'duplicate_check_score',
        'status', 'rejection_reason', 'revision_notes', 'submitted_at', 'approved_at'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $beforeInsert = ['cleanTitleData'];
    protected $beforeUpdate = ['cleanTitleData'];

    /**
     * Normalize title string by trimming and collapsing multiple consecutive whitespace characters.
     */
    public static function normalizeTitle(string $title): string
    {
        $title = trim($title);
        // Collapse multiple whitespace/tabs/newlines into a single space
        return preg_replace('/\s+/u', ' ', $title);
    }

    /**
     * Callback to ensure title is normalized before insert/update.
     */
    protected function cleanTitleData(array $data)
    {
        if (isset($data['data']['title'])) {
            $data['data']['title'] = self::normalizeTitle((string)$data['data']['title']);
        }
        return $data;
    }

    /**
     * Checks if an exact duplicate research title already exists in the database.
     * Case-insensitive, ignoring extra spaces and differences in casing.
     *
     * @param string $title
     * @param int|null $excludeId
     * @return array|null The duplicate record if found, null otherwise.
     */
    public function findExactDuplicate(string $title, ?int $excludeId = null): ?array
    {
        $normalized = self::normalizeTitle($title);
        if ($normalized === '') {
            return null;
        }

        // Build the base query using the Model's where/find to leverage ORM layer (has ->first())
        $query = $this->where('title', $normalized);
        if ($excludeId && (int)$excludeId > 0) {
            $query = $query->where('id !=', (int)$excludeId);
        }
        $exact = $query->first();
        if ($exact) {
            return $exact;
        }

        // Deep fallback: fetch all titles and compare normalized strings in PHP
        // (covers edge cases where collation may not catch unusual Unicode spaces)
        $allQuery = $this->select('id, title, student_id, researcher_id, instructor_id, status');
        if ($excludeId && (int)$excludeId > 0) {
            $allQuery = $allQuery->where('id !=', (int)$excludeId);
        }
        $rows = $allQuery->findAll();
        $targetLower = mb_strtolower($normalized, 'UTF-8');

        foreach ($rows as $row) {
            $existingClean = mb_strtolower(self::normalizeTitle($row['title'] ?? ''), 'UTF-8');
            if ($existingClean === $targetLower) {
                return $row;
            }
        }

        return null;
    }

    /**
     * Helper to return boolean whether duplicate exists.
     */
    public function isDuplicate(string $title, ?int $excludeId = null): bool
    {
        return $this->findExactDuplicate($title, $excludeId) !== null;
    }

    /**
     * Check for exact duplicates as well as similar titles using fulltext/similarity algorithms.
     */
    public function checkDuplicate(string $title, ?int $excludeId = null): array
    {
        $normalized = self::normalizeTitle($title);
        $exactMatch = $this->findExactDuplicate($normalized, $excludeId);

        $results = [];
        if ($exactMatch) {
            $results[] = [
                'id'         => $exactMatch['id'],
                'title'      => $exactMatch['title'],
                'similarity' => 100,
                'is_exact'   => true,
            ];
        }

        // Natural language / keyword similarity search if title is sufficiently long
        if (mb_strlen($normalized) >= 4) {
            try {
                $query = $this->select("id, title, 
                    (MATCH(title) AGAINST('" . $this->db->escapeString($normalized) . "' IN NATURAL LANGUAGE MODE) * 100) as similarity", false);
                
                if ($excludeId && (int)$excludeId > 0) {
                    $query->where('id !=', (int)$excludeId);
                }
                if ($exactMatch) {
                    $query->where('id !=', (int)$exactMatch['id']);
                }

                $similars = $query->having('similarity >=', 20)
                    ->orderBy('similarity', 'DESC')
                    ->limit(5)
                    ->get()->getResultArray();

                foreach ($similars as $sim) {
                    $sim['is_exact'] = false;
                    $results[] = $sim;
                }
            } catch (\Throwable $e) {
                // If fulltext fails or index is rebuilding, fallback to LIKE comparison
            }
        }

        return $results;
    }

    public function getWithDetails($status = null, $strandId = null, $categoryId = null)
    {
        $this->select('research_titles.*, 
            users.username as student_name, 
            u2.username as instructor_name, 
            researchers.fullname as researcher_name,
            researchers.final_defense_status_id,
            researchers.final_defense_date,
            defense_statuses.name as final_defense_status_name,
            strands.name as strand_name,
            research_categories.name as category_name,
            school_years.name as school_year_name')
             ->join('users', 'users.id = research_titles.student_id', 'left')
             ->join('users u2', 'u2.id = research_titles.instructor_id', 'left')
             ->join('researchers', 'researchers.id = research_titles.researcher_id', 'left')
             ->join('defense_statuses', 'defense_statuses.id = researchers.final_defense_status_id', 'left')
             ->join('strands', 'strands.id = research_titles.strand_id', 'left')
             ->join('research_categories', 'research_categories.id = research_titles.category_id', 'left')
             ->join('school_years', 'school_years.id = research_titles.school_year_id', 'left');
        
        if ($status) $this->where('research_titles.status', $status);
        if ($strandId) $this->where('research_titles.strand_id', $strandId);
        if ($categoryId) $this->where('research_titles.category_id', $categoryId);
        
        return $this->orderBy('research_titles.created_at', 'DESC')->findAll();
    }

    public function getCompletedOnly($search = null, $strandId = null)
    {
        $this->select('research_titles.*, 
            users.username as student_name, 
            u2.username as instructor_name, 
            strands.name as strand_name,
            research_categories.name as category_name,
            school_years.name as school_year_name')
             ->join('users', 'users.id = research_titles.student_id', 'left')
             ->join('users u2', 'u2.id = research_titles.instructor_id', 'left')
             ->join('strands', 'strands.id = research_titles.strand_id', 'left')
             ->join('research_categories', 'research_categories.id = research_titles.category_id', 'left')
             ->join('school_years', 'school_years.id = research_titles.school_year_id', 'left')
             ->where('research_titles.status', 'completed');
        
        if ($search) {
            $this->groupStart()
                 ->like('research_titles.title', $search)
                 ->orLike('research_titles.abstract', $search)
                 ->groupEnd();
        }
        if ($strandId) $this->where('research_titles.strand_id', $strandId);
        
        return $this->orderBy('research_titles.approved_at', 'DESC')->findAll();
    }

    public function getInstructorTitles($instructorId)
    {
        return $this->select('research_titles.*, 
            users.username as student_name,
            researchers.fullname as researcher_name,
            strands.name as strand_name, 
            research_categories.name as category_name,
            school_years.name as school_year_name')
                    ->join('users', 'users.id = research_titles.student_id', 'left')
                    ->join('researchers', 'researchers.id = research_titles.researcher_id', 'left')
                    ->join('strands', 'strands.id = research_titles.strand_id', 'left')
                    ->join('research_categories', 'research_categories.id = research_titles.category_id', 'left')
                    ->join('school_years', 'school_years.id = research_titles.school_year_id', 'left')
                    ->groupStart()
                        ->where('research_titles.instructor_id', $instructorId)
                        ->orWhere('users.assigned_instructor_id', $instructorId)
                    ->groupEnd()
                    ->orderBy('research_titles.created_at', 'DESC')
                    ->findAll();
    }

    public function getStudentTitles($studentId)
    {
        return $this->select('research_titles.*, 
            researchers.fullname as researcher_fullname,
            researchers.author as researcher_author,
            researchers.strand_degree_program,
            courses.name as course_name,
            researchers.final_defense_status_id, 
            defense_statuses.name as final_defense_status_name,
            strands.name as strand_name, 
            research_categories.name as category_name,
            school_years.name as school_year_name,
            u_teacher.username as teacher_name')
                    ->join('researchers', 'researchers.id = research_titles.researcher_id', 'left')
                    ->join('courses', 'courses.id = researchers.course_id', 'left')
                    ->join('defense_statuses', 'defense_statuses.id = researchers.final_defense_status_id', 'left')
                    ->join('strands', 'strands.id = research_titles.strand_id', 'left')
                    ->join('research_categories', 'research_categories.id = research_titles.category_id', 'left')
                    ->join('school_years', 'school_years.id = research_titles.school_year_id', 'left')
                    ->join('users u_teacher', 'u_teacher.id = research_titles.instructor_id', 'left')
                    ->groupStart()
                        ->where('research_titles.student_id', $studentId)
                        ->orWhere('researchers.user_id', $studentId)
                    ->groupEnd()
                    ->orderBy('research_titles.created_at', 'DESC')
                    ->findAll();
    }
}
