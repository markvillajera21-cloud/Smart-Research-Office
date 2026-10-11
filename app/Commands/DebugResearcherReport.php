<?php
namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class DebugResearcherReport extends BaseCommand
{
    protected $group       = 'Debug';
    protected $name        = 'debug:researcher-report';
    protected $description = 'Debug researcher report DB data';

    public function run(array $params)
    {
        $db = db_connect();

        CLI::write('=== COURSES ===', 'cyan');
        foreach($db->query('SELECT id, name FROM courses ORDER BY name')->getResultArray() as $r)
            CLI::write($r['id'] . ' => ' . $r['name']);

        CLI::write('=== SCHOOL_YEARS ===', 'cyan');
        foreach($db->query('SELECT id, name FROM school_years ORDER BY name')->getResultArray() as $r)
            CLI::write($r['id'] . ' => ' . $r['name']);

        CLI::write('=== STRANDS ===', 'cyan');
        foreach($db->query('SELECT id, name FROM strands ORDER BY name')->getResultArray() as $r)
            CLI::write($r['id'] . ' => ' . $r['name']);

        CLI::write('=== STATUSES ===', 'cyan');
        foreach($db->query('SELECT id, name FROM statuses ORDER BY name')->getResultArray() as $r)
            CLI::write($r['id'] . ' => ' . $r['name']);

        CLI::write('=== RESEARCHER DISTRIBUTION ===', 'cyan');
        $rows = $db->query('SELECT course_id, strand_id, school_year_id, COUNT(*) as cnt FROM researchers GROUP BY course_id, strand_id, school_year_id ORDER BY cnt DESC')->getResultArray();
        foreach($rows as $r)
            CLI::write("course_id=[{$r['course_id']}] strand_id=[{$r['strand_id']}] sy_id=[{$r['school_year_id']}] count={$r['cnt']}");

        CLI::write('=== SAMPLE RESEARCHERS (first 5) ===', 'cyan');
        $rows = $db->query('SELECT id, fullname, author, course_id, strand_id, school_year_id, status_id, school_year, strand_degree_program, status FROM researchers LIMIT 5')->getResultArray();
        foreach($rows as $r) {
            CLI::write('---');
            foreach($r as $k => $v) CLI::write("  $k = " . var_export($v, true));
        }
    }
}
