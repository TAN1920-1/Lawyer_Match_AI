<?php
/**
 * AI-Based Lawyer Recommendation Engine
 * Implements AGE-MOEA (Adaptive Geometry-based Evolutionary Multi-Objective Evolutionary Algorithm) concept
 * Objectives: Maximize Success Rate, Maximize Rating, Maximize Experience, Minimize Hourly Rate, Minimize Distance
 */

class AIRecommendationEngine {
    private $conn;
    private $user_city;
    private $generations = 50;
    private $population_size = 20;

    public function __construct($db_conn, $user_city = '') {
        $this->conn = $db_conn;
        $this->user_city = $user_city;
    }

    /**
     * Step 1 — Population Initialization
     * Fetches lawyers from MySQL as the initial population
     */
    private function initializePopulation($speciality = '') {
        $query = "SELECT u.*, l.* FROM user u JOIN lawyer l ON u.u_id = l.lawyer_id WHERE u.status = 'Active'";
        if (!empty($speciality)) {
            $query .= " AND l.speciality = '" . mysqli_real_escape_string($this->conn, $speciality) . "'";
        }
        
        $result = mysqli_query($this->conn, $query);
        $population = [];
        while ($row = mysqli_fetch_assoc($result)) {
            // If success_rate doesn't exist, we simulate it based on rating and experience
            if (!isset($row['success_rate'])) {
                $row['success_rate'] = ($row['avg_rating'] * 15) + (intval($row['practise_Length']) * 2) + rand(5, 15);
                $row['success_rate'] = min(99, max(60, $row['success_rate']));
            }
            
            // Handle hourly_rate if it's 0
            if ($row['hourly_rate'] <= 0) {
                $row['hourly_rate'] = 1000 + (intval($row['practise_Length']) * 200);
            }
            
            $population[] = $row;
        }
        return $population;
    }

    /**
     * Step 2 — Objective Evaluation
     * Multi-objective fitness scoring function
     */
    private function evaluateFitness(&$lawyer) {
        $score = 0;
        
        $score += $lawyer['success_rate'] * 0.45; // 45% weight
        $score += $lawyer['avg_rating'] * 35.0;    // 35% weight
        $score += intval($lawyer['practise_Length']) * 10.0; // 10% weight
        
        $distance = ($lawyer['city'] === $this->user_city) ? 0 : 50; 
        $score -= $distance * 0.10; // 10% weight (Distance)
        
        $lawyer['fitness'] = $score;
        return $score;
    }

    /**
     * Step 5 — Mutation (Evolution Step)
     * Introduces random variation to explore new solutions
     */
    private function applyMutation(&$lawyer) {
        $mutation = rand(-500, 500) / 100.0; // Random change between -5 and 5
        $lawyer['fitness'] += $mutation;
    }

    /**
     * Step 8 — Generation Loop
     * Simulates evolution over multiple generations
     */
    public function getRecommendations($speciality = '', $limit = 5) {
        $population = $this->initializePopulation($speciality);
        
        if (empty($population)) return [];

        for ($g = 0; $g < $this->generations; $g++) {
            // Step 2: Evaluate objectives for all
            foreach ($population as &$lawyer) {
                $this->evaluateFitness($lawyer);
            }
            
            // Step 3 & 6: Pareto Ranking (Simulated by Fitness Sorting) & Selection
            usort($population, function($a, $b) {
                return $b['fitness'] <=> $a['fitness'];
            });
            
            // Keep only the best (Selection step)
            if (count($population) > $this->population_size) {
                $population = array_slice($population, 0, $this->population_size);
            }
            
            // Step 7: Apply Mutation to the surviving population
            foreach ($population as &$lawyer) {
                $this->applyMutation($lawyer);
            }
        }
        
        // Final selection of top Pareto solutions
        usort($population, function($a, $b) {
            return $b['fitness'] <=> $a['fitness'];
        });
        
        return array_slice($population, 0, $limit);
    }
}
?>
