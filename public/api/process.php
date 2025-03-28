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

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
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
    
    // Initialize required arrays if they don't exist
    if (!isset($gameData['gold'])) {
        $gameData['gold'] = $CONFIG['starting_gold'];
    }
    if (!isset($gameData['army'])) {
        $gameData['army'] = [];
    }
    if (!isset($gameData['queue'])) {
        $gameData['queue'] = [];
    }
    
    // Get request data
    $action = $_POST['action'] ?? '';
    $unitType = $_POST['unit_type'] ?? '';
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
    
    if ($action !== 'build') {
        throw new Exception('Invalid action');
    }
    
    if (!isset($CONFIG['units'][$unitType])) {
        throw new Exception('Invalid unit type');
    }
    
    // Get unit configuration
    $unitConfig = $CONFIG['units'][$unitType];
    
    // Calculate total cost
    $totalCost = $unitConfig['cost'] * $quantity;
    
    // Check if player has enough gold
    if ($gameData['gold'] < $totalCost) {
        throw new Exception('Not enough gold');
    }
    
    // Check queue limit
    if (count($gameData['queue']) + $quantity > $CONFIG['mechanics']['queue_limit']) {
        throw new Exception('Build queue is full');
    }
    
    // Deduct gold
    $gameData['gold'] -= $totalCost;
    
    // Add units to queue
    $currentTime = time();
    for ($i = 0; $i < $quantity; $i++) {
        $gameData['queue'][] = [
            'unit_type' => $unitType,
            'completion_time' => $currentTime + $unitConfig['build_time']
        ];
    }
    
    // Save updated game state
    $db->saveGameState($_SESSION['username'], $gameData);
    
    // Return success response
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'message' => "Added $quantity $unitType to build queue",
        'game_data' => $gameData
    ]);
    
} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} 