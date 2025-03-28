<?php
namespace Kingdom\Models;

use Kingdom\Database\Database;

class Kingdom {
    private $username;
    private $resources;
    private $buildings;
    private $units;
    private $db;

    public function __construct($username) {
        $this->username = $username;
        $this->db = Database::getInstance();
        
        // Create user if it doesn't exist
        $user = $this->db->getUser($username);
        if (!$user) {
            $this->db->createUser($username, 'default_password_' . time());
        }
        
        $this->loadGameState();
    }

    private function loadGameState() {
        $gameState = $this->db->getGameState($this->username);
        if (!$gameState) {
            // Initialize new game state
            $this->resources = [
                'gold' => 1000,
                'wood' => 500,
                'stone' => 300
            ];
            $this->buildings = [
                'castle' => 1,
                'barracks' => 0
            ];
            $this->units = [
                'soldiers' => 5,
                'archers' => 3
            ];
            $this->saveGameState();
        } else {
            $this->resources = $gameState['resources'];
            $this->buildings = $gameState['buildings'];
            $this->units = $gameState['units'];
        }
    }

    private function saveGameState() {
        $gameData = [
            'username' => $this->username,
            'resources' => $this->resources,
            'buildings' => $this->buildings,
            'units' => $this->units
        ];
        $this->db->saveGameState($this->username, $gameData);
    }

    // Resource Management
    public function getResources() {
        return $this->resources;
    }

    public function addResources($resources) {
        foreach ($resources as $type => $amount) {
            if (isset($this->resources[$type])) {
                $this->resources[$type] += $amount;
            }
        }
        $this->saveGameState();
    }

    public function spendResources($resources) {
        foreach ($resources as $type => $amount) {
            if (!isset($this->resources[$type]) || $this->resources[$type] < $amount) {
                return false;
            }
        }
        
        foreach ($resources as $type => $amount) {
            $this->resources[$type] -= $amount;
        }
        $this->saveGameState();
        return true;
    }

    // Building Management
    public function getBuildings() {
        return $this->buildings;
    }

    public function build($buildingType) {
        $buildingCosts = [
            'castle' => ['gold' => 1000, 'stone' => 500],
            'barracks' => ['gold' => 300, 'wood' => 200]
        ];

        if (!isset($buildingCosts[$buildingType])) {
            return false;
        }

        if (!$this->spendResources($buildingCosts[$buildingType])) {
            return false;
        }

        if (!isset($this->buildings[$buildingType])) {
            $this->buildings[$buildingType] = 0;
        }
        $this->buildings[$buildingType]++;
        $this->saveGameState();
        return true;
    }

    // Unit Management
    public function getUnits() {
        return $this->units;
    }

    public function trainUnit($unitType) {
        $unitCosts = [
            'soldiers' => ['gold' => 100, 'wood' => 50],
            'archers' => ['gold' => 150, 'wood' => 30]
        ];

        if (!isset($unitCosts[$unitType])) {
            return false;
        }

        if (!$this->spendResources($unitCosts[$unitType])) {
            return false;
        }

        if (!isset($this->units[$unitType])) {
            $this->units[$unitType] = 0;
        }
        $this->units[$unitType]++;
        $this->saveGameState();
        return true;
    }

    // Battle System
    public function attack($enemyKingdom, $units) {
        // Validate units
        foreach ($units as $type => $amount) {
            if (!isset($this->units[$type]) || $this->units[$type] < $amount) {
                return false;
            }
        }

        // Calculate battle result (simplified for now)
        $totalPower = 0;
        foreach ($units as $type => $amount) {
            $power = [
                'soldiers' => 10,
                'archers' => 8
            ];
            $totalPower += $power[$type] * $amount;
        }

        // Random battle result (60% chance of victory)
        $victory = (rand(1, 100) <= 60);

        if ($victory) {
            // Calculate casualties (20-40% of units)
            $casualties = [];
            foreach ($units as $type => $amount) {
                $casualties[$type] = floor($amount * (rand(20, 40) / 100));
            }

            // Calculate loot (random between 100-500 gold and 50-200 wood)
            $loot = [
                'gold' => rand(100, 500),
                'wood' => rand(50, 200)
            ];

            // Apply casualties
            foreach ($casualties as $type => $amount) {
                $this->units[$type] -= $amount;
            }

            // Add loot
            $this->addResources($loot);

            // Save war report
            $warReport = [
                'battle_date' => date('Y-m-d H:i:s'),
                'enemy' => $enemyKingdom,
                'result' => 'victory',
                'casualties' => $casualties,
                'loot' => $loot
            ];
            $this->db->saveWarReport($this->username, $warReport);
        } else {
            // Defeat - lose all units
            foreach ($units as $type => $amount) {
                $this->units[$type] -= $amount;
            }

            // Save war report
            $warReport = [
                'battle_date' => date('Y-m-d H:i:s'),
                'enemy' => $enemyKingdom,
                'result' => 'defeat',
                'casualties' => $units,
                'loot' => []
            ];
            $this->db->saveWarReport($this->username, $warReport);
        }

        $this->saveGameState();
        return $victory;
    }

    // Get war reports
    public function getWarReports() {
        return $this->db->getWarReports($this->username);
    }
} 