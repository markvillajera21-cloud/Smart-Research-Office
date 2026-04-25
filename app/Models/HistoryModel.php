<?php

namespace App\Models;

use CodeIgniter\Model;

class HistoryModel extends Model
{
    protected $table            = 'research_history';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id', 
        'category', 
        'event_name', 
        'description', 
        'impact_score', 
        'achieved_at', 
        'metadata'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Records a milestone or career achievement for a researcher.
     */
    public static function record(int $userId, string $category, string $eventName, string $description = '', int $impactScore = 1, array $metadata = [])
    {
        $model = new self();
        
        return $model->insert([
            'user_id'      => $userId,
            'category'     => strtoupper($category),
            'event_name'   => $eventName,
            'description'  => $description,
            'impact_score' => $impactScore,
            'achieved_at'  => date('Y-m-d H:i:s'),
            'metadata'     => json_encode($metadata)
        ]);
    }

    /**
     * Gets history for a specific user, ordered by date.
     */
    public function getUserPortfolio(int $userId)
    {
        return $this->where('user_id', $userId)
                    ->orderBy('achieved_at', 'DESC')
                    ->findAll();
    }

    /**
     * Gets data for productivity heatmaps (grouped by date and impact).
     */
    public function getProductivityData(int $userId)
    {
        return $this->select('DATE(achieved_at) as date, SUM(impact_score) as total_impact, COUNT(*) as activity_count')
                    ->where('user_id', $userId)
                    ->groupBy('DATE(achieved_at)')
                    ->orderBy('date', 'ASC')
                    ->findAll();
    }
}
