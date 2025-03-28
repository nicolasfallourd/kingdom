<?php
session_start();
require_once 'config/bootstrap.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'User not logged in'
    ]);
    exit;
}

try {
    $db = Kingdom\Database\Database::getInstance();
    
    // Get current game state
    $gameData = $db->getGameState($_SESSION['username']);
    if (!$gameData) {
        throw new Exception('Game state not found');
    }
    
    // Initialize queue if it doesn't exist
    if (!isset($gameData['queue'])) {
        $gameData['queue'] = [];
    }
    
    $currentTime = time();
    $updated = false;
    
    // Process each item in the queue
    foreach ($gameData['queue'] as $key => $item) {
        if ($item['completion_time'] <= $currentTime) {
            // Unit is ready
            $unitType = $item['unit_type'];
            
            // Add unit to army
            if (!isset($gameData['army'][$unitType])) {
                $gameData['army'][$unitType] = 0;
            }
            $gameData['army'][$unitType]++;
            
            // Remove from queue
            unset($gameData['queue'][$key]);
            $updated = true;
        }
    }
    
    // Reindex array after removing items
    if ($updated) {
        $gameData['queue'] = array_values($gameData['queue']);
        $db->saveGameState($_SESSION['username'], $gameData);
    }
    
    // Return updated game state
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'updated' => $updated,
        'game_data' => $gameData,
        'queue' => $gameData['queue']
    ]);
    
} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} 