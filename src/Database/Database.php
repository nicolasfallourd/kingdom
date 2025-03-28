<?php
namespace Kingdom\Database;

use Exception;

class Database {
    private static $instance = null;
    private $client;
    private $url;
    private $key;

    private function __construct() {
        $this->url = $_ENV['SUPABASE_URL'];
        $this->key = $_ENV['SUPABASE_ANON_KEY'];
        
        if (!$this->url || !$this->key) {
            throw new Exception('Supabase credentials not found in environment variables');
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function request($method, $endpoint, $data = null) {
        $ch = curl_init();
        $url = rtrim($this->url, '/') . '/' . ltrim($endpoint, '/');
        
        $headers = [
            'apikey: ' . $this->key,
            'Authorization: Bearer ' . $this->key,
            'Content-Type: application/json',
            'Prefer: return=minimal'
        ];

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        } else if ($method === 'PATCH') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        $response = curl_exec($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($statusCode >= 400) {
            throw new Exception('Supabase API error: ' . $response);
        }

        return json_decode($response, true);
    }

    // Game State Management
    public function saveGameState($username, $gameData) {
        // First, try to get existing game state
        $existingState = $this->getGameState($username);
        
        if ($existingState) {
            // Update existing game state
            return $this->request('PATCH', "/rest/v1/game_states?username=eq.{$username}", [
                'data' => $gameData,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        } else {
            // Create new game state
            return $this->request('POST', '/rest/v1/game_states', [
                'username' => $username,
                'data' => $gameData,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
    }

    public function getGameState($username) {
        $response = $this->request('GET', "/rest/v1/game_states?username=eq.{$username}");
        $gameData = $response[0]['data'] ?? null;
        
        // If no game state exists, initialize it
        if (!$gameData) {
            $gameData = $this->initializeGameState();
            $this->saveGameState($username, $gameData);
        }
        
        return $gameData;
    }

    private function initializeGameState() {
        // Load configuration if not already loaded
        if (!isset($GLOBALS['CONFIG'])) {
            $configFile = __DIR__ . '/../../public/config.php';
            if (file_exists($configFile)) {
                $GLOBALS['CONFIG'] = require $configFile;
            } else {
                // Fallback default values if config file doesn't exist
                $GLOBALS['CONFIG'] = [
                    'starting_gold' => 1000,
                    'starting_wood' => 500,
                    'starting_stone' => 300
                ];
            }
        }
        
        return [
            'gold' => $GLOBALS['CONFIG']['starting_gold'],
            'wood' => $GLOBALS['CONFIG']['starting_wood'],
            'stone' => $GLOBALS['CONFIG']['starting_stone'],
            'army' => [
                'soldier' => 0,
                'mercenaire' => 0,
                'cavalier' => 0,
                'arbaletrier' => 0,
                'espion' => 0,
                'mage' => 0
            ],
            'queue' => [],
            'castle_level' => 0,
            'village' => [
                'serf' => 0
            ],
            'war_results' => null,
            'defense_results' => null
        ];
    }

    // War Reports Management
    public function saveWarReport($username, $report) {
        $data = [
            'username' => $username,
            'report' => $report,
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->request('POST', '/rest/v1/war_reports', $data);
    }

    public function getWarReports($username) {
        return $this->request('GET', "/rest/v1/war_reports?username=eq.{$username}&order=created_at.desc");
    }

    // User Management
    public function createUser($username, $password) {
        $data = [
            'username' => $username,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->request('POST', '/rest/v1/users', $data);
    }

    public function getUser($username) {
        $response = $this->request('GET', "/rest/v1/users?username=eq.{$username}");
        return $response[0] ?? null;
    }

    public function validateUser($username, $password) {
        $user = $this->getUser($username);
        if (!$user) {
            return false;
        }
        return password_verify($password, $user['password']);
    }
} 