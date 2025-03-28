// Game State
let gameState = {
    username: '',
    gold: 0,
    army: {},
    castle: {
        level: 0,
        defenseBonus: 0
    },
    village: {
        serfs: 0,
        incomePerDay: 0
    },
    queue: []
};

// DOM Elements
const usernameDisplay = document.getElementById('username-display');
const goldDisplay = document.getElementById('gold-display');
const totalArmyPower = document.getElementById('total-army-power');
const defensePower = document.getElementById('defense-power');
const unitGrid = document.getElementById('unit-grid');
const armyDisplay = document.getElementById('army-display');
const queueDisplay = document.getElementById('queue-display');
const castleLevel = document.getElementById('castle-level');
const castleDefenseBonus = document.getElementById('castle-defense-bonus');
const serfCount = document.getElementById('serf-count');
const incomePerDay = document.getElementById('income-per-day');

// Tab Navigation
document.querySelectorAll('.tab-btn').forEach(button => {
    button.addEventListener('click', () => {
        // Remove active class from all buttons and panes
        document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
        document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.remove('active'));
        
        // Add active class to clicked button and corresponding pane
        button.classList.add('active');
        document.getElementById(`${button.dataset.tab}-tab`).classList.add('active');
    });
});

// Initialize Game
async function initializeGame() {
    try {
        // Load game state from server
        const response = await fetch('/api/game-state');
        const data = await response.json();
        
        if (data.success) {
            gameState = data.gameState;
            updateUI();
        } else {
            console.error('Failed to load game state:', data.error);
        }
    } catch (error) {
        console.error('Error initializing game:', error);
    }
}

// Update UI
function updateUI() {
    // Update header
    usernameDisplay.textContent = gameState.username;
    goldDisplay.textContent = gameState.gold;
    
    // Update army power
    const totalPower = calculateTotalArmyPower();
    totalArmyPower.textContent = totalPower;
    
    // Update defense power
    const defense = calculateDefensePower();
    defensePower.textContent = defense;
    
    // Update castle info
    castleLevel.textContent = gameState.castle.level;
    castleDefenseBonus.textContent = gameState.castle.defenseBonus;
    
    // Update village info
    serfCount.textContent = gameState.village.serfs;
    incomePerDay.textContent = gameState.village.incomePerDay;
    
    // Update unit grid
    updateUnitGrid();
    
    // Update army display
    updateArmyDisplay();
    
    // Update queue display
    updateQueueDisplay();
}

// Calculate total army power
function calculateTotalArmyPower() {
    let total = 0;
    for (const [unit, count] of Object.entries(gameState.army)) {
        total += getUnitPower(unit) * count;
    }
    return total;
}

// Calculate defense power
function calculateDefensePower() {
    const baseDefense = calculateTotalArmyPower();
    const castleBonus = gameState.castle.defenseBonus;
    return Math.floor(baseDefense * (1 + castleBonus / 100));
}

// Get unit power
function getUnitPower(unitType) {
    const unitPowers = {
        soldier: 10,
        mercenaire: 15,
        cavalier: 20,
        arbaletrier: 25,
        espion: 5,
        mage: 30,
        serf: 2
    };
    return unitPowers[unitType] || 0;
}

// Update unit grid
function updateUnitGrid() {
    unitGrid.innerHTML = '';
    const units = ['soldier', 'mercenaire', 'cavalier', 'arbaletrier', 'espion', 'mage', 'serf'];
    
    units.forEach(unit => {
        const card = document.createElement('div');
        card.className = 'unit-card';
        card.innerHTML = `
            <h3>${unit.charAt(0).toUpperCase() + unit.slice(1)}</h3>
            <p>Power: ${getUnitPower(unit)}</p>
            <p>Cost: ${getUnitCost(unit)} gold</p>
            <button onclick="buildUnit('${unit}')">Build</button>
        `;
        unitGrid.appendChild(card);
    });
}

// Get unit cost
function getUnitCost(unitType) {
    const unitCosts = {
        soldier: 100,
        mercenaire: 200,
        cavalier: 300,
        arbaletrier: 400,
        espion: 150,
        mage: 500,
        serf: 50
    };
    return unitCosts[unitType] || 0;
}

// Update army display
function updateArmyDisplay() {
    armyDisplay.innerHTML = '';
    for (const [unit, count] of Object.entries(gameState.army)) {
        if (count > 0) {
            const unitElement = document.createElement('div');
            unitElement.className = 'army-unit';
            unitElement.innerHTML = `
                <h3>${unit.charAt(0).toUpperCase() + unit.slice(1)}</h3>
                <p>Count: ${count}</p>
                <p>Power: ${getUnitPower(unit) * count}</p>
            `;
            armyDisplay.appendChild(unitElement);
        }
    }
}

// Update queue display
function updateQueueDisplay() {
    queueDisplay.innerHTML = '';
    gameState.queue.forEach((item, index) => {
        const queueItem = document.createElement('div');
        queueItem.className = 'queue-item';
        queueItem.innerHTML = `
            <h3>${item.unit.charAt(0).toUpperCase() + item.unit.slice(1)}</h3>
            <p>Time remaining: ${formatTime(item.completionTime - Date.now())}</p>
            <button onclick="cancelQueueItem(${index})">Cancel</button>
        `;
        queueDisplay.appendChild(queueItem);
    });
}

// Format time
function formatTime(ms) {
    if (ms <= 0) return 'Complete';
    const seconds = Math.floor(ms / 1000);
    const minutes = Math.floor(seconds / 60);
    const hours = Math.floor(minutes / 60);
    return `${hours}h ${minutes % 60}m ${seconds % 60}s`;
}

// Build unit
async function buildUnit(unitType) {
    try {
        const response = await fetch('/api/build-unit', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ unit: unitType })
        });
        
        const data = await response.json();
        if (data.success) {
            gameState = data.gameState;
            updateUI();
        } else {
            alert(data.error);
        }
    } catch (error) {
        console.error('Error building unit:', error);
        alert('Failed to build unit');
    }
}

// Cancel queue item
async function cancelQueueItem(index) {
    try {
        const response = await fetch('/api/cancel-queue', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ index })
        });
        
        const data = await response.json();
        if (data.success) {
            gameState = data.gameState;
            updateUI();
        } else {
            alert(data.error);
        }
    } catch (error) {
        console.error('Error canceling queue item:', error);
        alert('Failed to cancel queue item');
    }
}

// Modal functions
function showWarResultsModal(content) {
    const modal = document.getElementById('war-results-modal');
    document.getElementById('war-results-content').innerHTML = content;
    modal.style.display = 'block';
}

function closeWarResultsModal() {
    document.getElementById('war-results-modal').style.display = 'none';
}

function showWarReportsModal(content) {
    const modal = document.getElementById('war-reports-modal');
    document.getElementById('war-reports-content').innerHTML = content;
    modal.style.display = 'block';
}

function closeWarReportsModal() {
    document.getElementById('war-reports-modal').style.display = 'none';
}

// Event Listeners
document.getElementById('upgrade-castle-btn').addEventListener('click', async () => {
    try {
        const response = await fetch('/api/upgrade-castle', {
            method: 'POST'
        });
        
        const data = await response.json();
        if (data.success) {
            gameState = data.gameState;
            updateUI();
        } else {
            alert(data.error);
        }
    } catch (error) {
        console.error('Error upgrading castle:', error);
        alert('Failed to upgrade castle');
    }
});

document.getElementById('build-serf-btn').addEventListener('click', () => {
    buildUnit('serf');
});

document.getElementById('send-to-war-btn').addEventListener('click', async () => {
    try {
        const response = await fetch('/api/send-to-war', {
            method: 'POST'
        });
        
        const data = await response.json();
        if (data.success) {
            showWarResultsModal(data.results);
        } else {
            alert(data.error);
        }
    } catch (error) {
        console.error('Error sending army to war:', error);
        alert('Failed to send army to war');
    }
});

// Initialize game when page loads
document.addEventListener('DOMContentLoaded', initializeGame);

// Update UI periodically
setInterval(updateUI, 1000); 