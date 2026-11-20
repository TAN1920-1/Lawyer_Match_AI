<?php
/**
 * Test Script for AGE-MOEA AI Recommendation Engine
 * This script verifies the multi-objective optimization logic without requiring a web browser.
 */

require_once 'db_con/dbCon.php';
require_once 'includes/ai_recommendation_engine.php';

echo "=== AGE-MOEA Recommendation Engine Test ===\n\n";

$conn = connect();
if (!$conn) {
    die("Database connection failed.\n");
}

// 1. Check if we have lawyers in the database
$check_query = "SELECT COUNT(*) as count FROM user u JOIN lawyer l ON u.u_id = l.lawyer_id WHERE u.status = 'Active'";
$check_res = mysqli_query($conn, $check_query);
$count = mysqli_fetch_assoc($check_res)['count'];

echo "Found $count active lawyers in the database.\n";

if ($count == 0) {
    echo "Warning: No active lawyers found. Test results will be empty.\n";
}

// 2. Initialize Engine
$test_city = "Mumbai";
echo "Testing recommendations for user in: $test_city\n";
$ai_engine = new AIRecommendationEngine($conn, $test_city);

// 3. Run Recommendations
echo "Running AGE-MOEA algorithm (50 generations)...\n";
$start_time = microtime(true);
$recommendations = $ai_engine->getRecommendations('', 5);
$end_time = microtime(true);

echo "Algorithm completed in " . round(($end_time - $start_time) * 1000, 2) . "ms\n\n";

// 4. Output and Verify Results
if (empty($recommendations)) {
    echo "No recommendations generated.\n";
} else {
    echo "Top 5 Recommended Lawyers (Optimized):\n";
    echo str_repeat("-", 80) . "\n";
    echo sprintf("%-5s | %-20s | %-15s | %-8s | %-8s | %-8s\n", "Rank", "Name", "City", "Rating", "Success", "Fitness");
    echo str_repeat("-", 80) . "\n";
    
    foreach ($recommendations as $index => $lawyer) {
        echo sprintf(
            "%-5d | %-20s | %-15s | %-8.1f | %-7d%% | %-8.2f\n",
            $index + 1,
            $lawyer['first_Name'] . " " . $lawyer['last_Name'],
            $lawyer['city'],
            $lawyer['avg_rating'],
            $lawyer['success_rate'],
            $lawyer['fitness']
        );
    }
    echo str_repeat("-", 80) . "\n";
    
    // Verification Logic
    echo "\nVerification Analysis:\n";
    $first = $recommendations[0];
    $last = end($recommendations);
    
    if ($first['fitness'] >= $last['fitness']) {
        echo "[PASS] Sorting: Solutions are correctly ranked by fitness score.\n";
    } else {
        echo "[FAIL] Sorting: Higher fitness solution is ranked lower.\n";
    }
    
    $city_match = false;
    foreach($recommendations as $l) {
        if ($l['city'] === $test_city) $city_match = true;
    }
    if ($city_match) {
        echo "[INFO] Diversity: Found lawyers in the same city ($test_city).\n";
    } else {
        echo "[INFO] Diversity: No local lawyers found, optimized globally.\n";
    }
}

echo "\n=== Test Completed ===\n";
?>
