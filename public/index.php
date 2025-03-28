<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kingdom Management Game</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Kingdom Management Game</h1>
            <div class="user-info">
                <p>Welcome, <span id="username">Loading...</span></p>
                <a href="logout.php" class="logout-btn">Logout</a>
            </div>
        </div>

        <div class="left-column">
            <div class="tabs">
                <div class="tab active" data-tab="kingdom">Kingdom</div>
                <div class="tab" data-tab="resources">Resources</div>
                <div class="tab" data-tab="army">Army</div>
                <div class="tab" data-tab="world">World</div>
                <div class="tab" data-tab="war">War</div>
            </div>

            <div class="tab-content active" id="kingdom-tab">
                <div class="castle">
                    <div class="castle-info">
                        <h3>Castle Level: <span id="castle-level">1</span></h3>
                        <p>Defense Bonus: <span id="defense-bonus">+10%</span></p>
                        <div class="castle-upgrade">
                            <h3>Upgrade Castle</h3>
                            <table class="castle-table">
                                <tr>
                                    <th>Level</th>
                                    <th>Cost</th>
                                    <th>Defense Bonus</th>
                                    <th>Action</th>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>1000 Gold</td>
                                    <td>+20%</td>
                                    <td><button class="upgrade-castle-btn" onclick="upgradeCastle()">Upgrade</button></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-content" id="resources-tab">
                <div class="resources">
                    <h2>Resources</h2>
                    <p>Gold: <span id="gold">0</span></p>
                    <p>Wood: <span id="wood">0</span></p>
                    <p>Stone: <span id="stone">0</span></p>
                    <div class="income-info">
                        <h3>Income</h3>
                        <p>Per Hour: <span id="income-per-hour">0</span></p>
                        <div class="income-progress">
                            <div class="progress-bar">
                                <div class="progress" id="income-progress"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-content" id="army-tab">
                <div class="army">
                    <h2>Your Army</h2>
                    <div id="army-units"></div>
                    <div class="army-strength">
                        <h3>Total Army Strength: <span id="total-strength">0</span></h3>
                    </div>
                </div>
                <div class="build-units">
                    <h2>Build Units</h2>
                    <div id="unit-options"></div>
                </div>
            </div>

            <div class="tab-content" id="world-tab">
                <h2>World Map</h2>
                <div class="world-kingdoms">
                    <table id="kingdoms-table">
                        <thead>
                            <tr>
                                <th>Kingdom</th>
                                <th>Army Strength</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="kingdoms-list"></tbody>
                    </table>
                </div>
            </div>

            <div class="tab-content" id="war-tab">
                <h2>War Reports</h2>
                <div class="war-reports">
                    <button id="war-reports-button" onclick="showWarReports()">View War Reports</button>
                    <div id="war-reports-list"></div>
                </div>
            </div>
        </div>

        <div class="right-column">
            <div class="build-queue">
                <h2>Build Queue</h2>
                <div id="queue-container">
                    <ul id="build-queue"></ul>
                </div>
            </div>
        </div>

        <div class="actions">
            <div class="game-controls">
                <button class="reset-btn" onclick="resetGame()">Reset Game</button>
                <button class="reload-btn" onclick="reloadConfig()">Reload Config</button>
                <button id="war-reports-button" onclick="showWarReports()">War Reports</button>
            </div>
        </div>
    </div>

    <!-- Modals -->
    <div id="war-results-modal" class="modal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeWarResultsModal()">&times;</span>
            <div id="war-results-content"></div>
        </div>
    </div>

    <div id="war-reports-modal" class="modal">
        <div class="modal-content war-reports-content">
            <span class="close-modal" onclick="closeWarReportsModal()">&times;</span>
            <h2>War Reports</h2>
            <div class="war-reports-table-container">
                <table class="war-reports-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Opponent</th>
                            <th>Result</th>
                            <th>Loot</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="war-reports-table-body"></tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="defense-alert" class="defense-alert" style="display: none;">
        <h3 class="defense-alert-header">Under Attack!</h3>
        <div class="defense-alert-content">
            <p>Your kingdom is being attacked!</p>
            <p>Enemy Army Strength: <span id="enemy-strength">0</span></p>
            <p>Your Army Strength: <span id="your-strength">0</span></p>
        </div>
        <div class="defense-alert-buttons">
            <button onclick="defendCastle()">Defend</button>
            <button class="give-gold-btn" onclick="giveGold()">Give Gold</button>
        </div>
    </div>

    <div class="notification-area" id="notification-area"></div>

    <script>
        // Game state management
        let gameState = null;
        let config = null;
        let lastUpdate = 0;
        let updateInterval = null;

        // Initialize the game
        document.addEventListener('DOMContentLoaded', function() {
            loadConfig();
            updateGameState();
            setupTabs();
            startUpdateInterval();
        });

        // Tab management
        function setupTabs() {
            const tabs = document.querySelectorAll('.tab');
            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    const tabId = tab.getAttribute('data-tab');
                    switchTab(tabId);
                });
            });
        }

        function switchTab(tabId) {
            // Update active tab
            document.querySelectorAll('.tab').forEach(tab => {
                tab.classList.remove('active');
                if (tab.getAttribute('data-tab') === tabId) {
                    tab.classList.add('active');
                }
            });

            // Update active content
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
                if (content.id === `${tabId}-tab`) {
                    content.classList.add('active');
                }
            });

            // Load specific tab data
            switch(tabId) {
                case 'world':
                    loadKingdoms();
                    break;
                case 'war':
                    updateWarReportsButton();
                    break;
            }
        }

        // Game state updates
        function startUpdateInterval() {
            updateInterval = setInterval(updateGameState, 5000);
        }

        function updateGameState() {
            fetch('get_game_state.php')
                .then(response => response.json())
                .then(data => {
                    gameState = data;
                    updateUI();
                })
                .catch(error => console.error('Error updating game state:', error));
        }

        function updateUI() {
            if (!gameState) return;

            // Update resources
            document.getElementById('gold').textContent = gameState.resources.gold;
            document.getElementById('wood').textContent = gameState.resources.wood;
            document.getElementById('stone').textContent = gameState.resources.stone;

            // Update castle
            document.getElementById('castle-level').textContent = gameState.buildings.castle;
            document.getElementById('defense-bonus').textContent = `+${gameState.buildings.castle * 10}%`;

            // Update army
            updateArmyDisplay();
            calculateArmyStrength();

            // Update build queue
            updateQueueDisplay();

            // Update income
            updateIncomeDisplay();
        }

        // Army management
        function updateArmyDisplay() {
            const armyContainer = document.getElementById('army-units');
            armyContainer.innerHTML = '';

            Object.entries(gameState.army).forEach(([unit, count]) => {
                const unitInfo = config.units[unit];
                if (!unitInfo) return;

                const unitDiv = document.createElement('div');
                unitDiv.className = 'unit-row';
                unitDiv.innerHTML = `
                    <h3>${capitalizeFirstLetter(unit)}</h3>
                    <p>Count: ${count}</p>
                    <p>Attack: ${unitInfo.attack}</p>
                    <p>Defense: ${unitInfo.defense}</p>
                    <div class="build-form">
                        <input type="number" min="1" value="1" id="${unit}-count">
                        <button onclick="buildUnit('${unit}')">Build</button>
                    </div>
                `;
                armyContainer.appendChild(unitDiv);
            });
        }

        function calculateArmyStrength() {
            if (!gameState || !config) return;

            let totalStrength = 0;
            Object.entries(gameState.army).forEach(([unit, count]) => {
                const unitInfo = config.units[unit];
                if (!unitInfo) return;

                totalStrength += (unitInfo.attack + unitInfo.defense) * count;
            });

            const castleBonus = 1 + (gameState.buildings.castle * 0.1);
            totalStrength *= castleBonus;

            document.getElementById('total-strength').textContent = Math.round(totalStrength);
        }

        // Build queue management
        function updateQueueDisplay() {
            const queueContainer = document.getElementById('build-queue');
            queueContainer.innerHTML = '';

            gameState.buildQueue.forEach((item, index) => {
                const li = document.createElement('li');
                li.innerHTML = `
                    ${capitalizeFirstLetter(item.unit)} x${item.count}
                    <span class="countdown">${formatTime(item.endTime - Date.now())}</span>
                `;
                queueContainer.appendChild(li);
            });
        }

        // Income management
        function updateIncomeDisplay() {
            if (!gameState || !config) return;

            const serfCount = gameState.army.serf || 0;
            const incomePerHour = serfCount * config.income.serf;
            const incomePerSecond = incomePerHour / 3600;

            document.getElementById('income-per-hour').textContent = incomePerHour;

            const progress = ((Date.now() - lastUpdate) / 5000) * 100;
            document.getElementById('income-progress').style.width = `${progress}%`;

            if (progress >= 100) {
                lastUpdate = Date.now();
                document.getElementById('income-progress').style.width = '0%';
            }
        }

        // Utility functions
        function capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        }

        function formatTime(ms) {
            if (ms <= 0) return '0s';
            
            const seconds = Math.ceil(ms / 1000);
            return `${seconds}s`;
        }

        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            notification.textContent = message;

            const container = document.getElementById('notification-area');
            container.appendChild(notification);

            setTimeout(() => {
                notification.classList.add('fade-out');
                setTimeout(() => notification.remove(), 500);
            }, 3000);
        }

        // Game actions
        function buildUnit(unit) {
            const count = parseInt(document.getElementById(`${unit}-count`).value);
            if (count < 1) return;

            fetch(`build_${unit}.php`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ count })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(`Started building ${count} ${unit}(s)`);
                    updateGameState();
                } else {
                    showNotification(data.error, 'error');
                }
            })
            .catch(error => {
                console.error('Error building unit:', error);
                showNotification('Error building unit', 'error');
            });
        }

        function upgradeCastle() {
            fetch('upgrade_castle.php', {
                method: 'POST'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Castle upgrade started');
                    updateGameState();
                } else {
                    showNotification(data.error, 'error');
                }
            })
            .catch(error => {
                console.error('Error upgrading castle:', error);
                showNotification('Error upgrading castle', 'error');
            });
        }

        function resetGame() {
            if (!confirm('Are you sure you want to reset your game? This cannot be undone.')) {
                return;
            }

            fetch('reset_game.php', {
                method: 'POST'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Game reset successfully');
                    updateGameState();
                } else {
                    showNotification(data.error, 'error');
                }
            })
            .catch(error => {
                console.error('Error resetting game:', error);
                showNotification('Error resetting game', 'error');
            });
        }

        function reloadConfig() {
            fetch('reload_config.php', {
                method: 'POST'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Configuration reloaded');
                    loadConfig();
                } else {
                    showNotification(data.error, 'error');
                }
            })
            .catch(error => {
                console.error('Error reloading config:', error);
                showNotification('Error reloading config', 'error');
            });
        }

        // War system
        function loadKingdoms() {
            fetch('get_kingdoms.php')
                .then(response => response.json())
                .then(data => {
                    const kingdomsList = document.getElementById('kingdoms-list');
                    kingdomsList.innerHTML = '';

                    data.forEach(kingdom => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${kingdom.username}</td>
                            <td>${kingdom.armyStrength}</td>
                            <td>
                                <button class="PVP-attack-btn" onclick="attackKingdom('${kingdom.username}')">
                                    Attack
                                </button>
                            </td>
                        `;
                        kingdomsList.appendChild(row);
                    });
                })
                .catch(error => console.error('Error loading kingdoms:', error));
        }

        function attackKingdom(target) {
            if (!confirm(`Are you sure you want to attack ${target}?`)) {
                return;
            }

            const button = event.target;
            button.disabled = true;
            let countdown = 5;

            const timer = setInterval(() => {
                if (countdown > 0) {
                    button.textContent = `Attacking in ${countdown}`;
                    countdown--;
                } else {
                    clearInterval(timer);
                    button.textContent = 'Attacking...';
                    executeAttack(target);
                }
            }, 1000);
        }

        function executeAttack(target) {
            fetch('attack_kingdom.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ target })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Attack initiated');
                    checkWarStatus();
                } else {
                    showNotification(data.error, 'error');
                    resetWarButton();
                }
            })
            .catch(error => {
                console.error('Error attacking kingdom:', error);
                showNotification('Error attacking kingdom', 'error');
                resetWarButton();
            });
        }

        function checkWarStatus() {
            fetch('check_war_status.php')
                .then(response => response.json())
                .then(data => {
                    if (data.inWar) {
                        updateWarTimer(data.endTime);
                        setTimeout(checkWarStatus, 1000);
                    } else {
                        checkWarEnd();
                    }
                })
                .catch(error => console.error('Error checking war status:', error));
        }

        function updateWarTimer(endTime) {
            const timeLeft = endTime - Date.now();
            if (timeLeft <= 0) {
                checkWarEnd();
            }
        }

        function checkWarEnd() {
            fetch('war_end.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showWarResults(data.results);
                    }
                    resetWarButton();
                })
                .catch(error => console.error('Error checking war end:', error));
        }

        function showWarResults(results) {
            const modal = document.getElementById('war-results-modal');
            const content = document.getElementById('war-results-content');
            
            content.innerHTML = `
                <div class="war-outcome">
                    <h2 class="${results.victory ? 'victory-title' : 'defeat-title'}">
                        ${results.victory ? 'Victory!' : 'Defeat'}
                    </h2>
                    <div class="battle-details-container">
                        <div class="battle-comparison">
                            <h3>Battle Comparison</h3>
                            <div class="battle-stats">
                                <div class="battle-stat">
                                    <span class="stat-label">Your Army Strength</span>
                                    <span class="stat-value">${results.yourStrength}</span>
                                </div>
                                <div class="battle-stat">
                                    <span class="stat-label">Enemy Army Strength</span>
                                    <span class="stat-value">${results.enemyStrength}</span>
                                </div>
                            </div>
                        </div>
                        <div class="casualties-section">
                            <h3>Casualties</h3>
                            <div class="casualties-grid">
                                ${Object.entries(results.casualties).map(([unit, count]) => `
                                    <div class="casualty-item">
                                        <span class="unit-name">${capitalizeFirstLetter(unit)}</span>
                                        <span class="casualty-count">-${count}</span>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                    </div>
                    ${results.loot > 0 ? `
                        <div class="claim-loot-banner">
                            <div class="gold-icon">💰</div>
                            <div class="loot-amount">${results.loot} Gold</div>
                            <button class="claim-button" onclick="claimLoot()">Claim Loot</button>
                        </div>
                    ` : ''}
                </div>
            `;

            modal.style.display = 'block';
        }

        function closeWarResultsModal() {
            document.getElementById('war-results-modal').style.display = 'none';
        }

        function claimLoot() {
            fetch('claim_loot.php', {
                method: 'POST'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Loot claimed successfully');
                    closeWarResultsModal();
                    updateGameState();
                } else {
                    showNotification(data.error, 'error');
                }
            })
            .catch(error => {
                console.error('Error claiming loot:', error);
                showNotification('Error claiming loot', 'error');
            });
        }

        function resetWarButton() {
            const buttons = document.querySelectorAll('.PVP-attack-btn');
            buttons.forEach(button => {
                button.disabled = false;
                button.textContent = 'Attack';
            });
        }

        // War Reports
        function showWarReports() {
            fetch('get_war_reports.php')
                .then(response => response.json())
                .then(data => {
                    const tbody = document.getElementById('war-reports-table-body');
                    tbody.innerHTML = '';

                    data.forEach(report => {
                        const row = document.createElement('tr');
                        row.className = report.victory ? 'victory-row' : 'defeat-row';
                        row.innerHTML = `
                            <td>${new Date(report.timestamp).toLocaleString()}</td>
                            <td>${report.opponent}</td>
                            <td>${report.victory ? 'Victory' : 'Defeat'}</td>
                            <td>${report.loot} Gold</td>
                            <td>
                                <button class="action-button detail-button" onclick="showReportDetails('${report.id}')">Details</button>
                                ${report.loot > 0 ? `
                                    <button class="action-button claim-button" onclick="claimReportLoot('${report.id}')">Claim</button>
                                ` : ''}
                            </td>
                        `;
                        tbody.appendChild(row);
                    });

                    document.getElementById('war-reports-modal').style.display = 'block';
                })
                .catch(error => console.error('Error loading war reports:', error));
        }

        function closeWarReportsModal() {
            document.getElementById('war-reports-modal').style.display = 'none';
        }

        function showReportDetails(reportId) {
            // Implementation for showing report details
        }

        function claimReportLoot(reportId) {
            fetch('claim_report_loot.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ reportId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Report loot claimed successfully');
                    showWarReports();
                    updateGameState();
                } else {
                    showNotification(data.error, 'error');
                }
            })
            .catch(error => {
                console.error('Error claiming report loot:', error);
                showNotification('Error claiming report loot', 'error');
            });
        }

        function updateWarReportsButton() {
            fetch('get_war_reports.php')
                .then(response => response.json())
                .then(data => {
                    const button = document.getElementById('war-reports-button');
                    const unclaimedReports = data.filter(report => report.loot > 0);
                    
                    if (unclaimedReports.length > 0) {
                        button.classList.add('has-unclaimed');
                        const badge = document.createElement('span');
                        badge.className = 'unclaimed-badge';
                        badge.textContent = unclaimedReports.length;
                        button.appendChild(badge);
                    } else {
                        button.classList.remove('has-unclaimed');
                        const badge = button.querySelector('.unclaimed-badge');
                        if (badge) badge.remove();
                    }
                })
                .catch(error => console.error('Error updating war reports button:', error));
        }

        // Defense system
        function showDefenseAlert(enemyStrength, yourStrength) {
            const alert = document.getElementById('defense-alert');
            document.getElementById('enemy-strength').textContent = enemyStrength;
            document.getElementById('your-strength').textContent = yourStrength;
            alert.style.display = 'block';
        }

        function defendCastle() {
            fetch('defend_castle.php', {
                method: 'POST'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Defending castle');
                    document.getElementById('defense-alert').style.display = 'none';
                    checkWarStatus();
                } else {
                    showNotification(data.error, 'error');
                }
            })
            .catch(error => {
                console.error('Error defending castle:', error);
                showNotification('Error defending castle', 'error');
            });
        }

        function giveGold() {
            fetch('give_gold.php', {
                method: 'POST'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Gold given to avoid attack');
                    document.getElementById('defense-alert').style.display = 'none';
                } else {
                    showNotification(data.error, 'error');
                }
            })
            .catch(error => {
                console.error('Error giving gold:', error);
                showNotification('Error giving gold', 'error');
            });
        }

        // Configuration management
        function loadConfig() {
            fetch('get_config.php')
                .then(response => response.json())
                .then(data => {
                    config = data;
                    updateUnitInfo();
                })
                .catch(error => console.error('Error loading config:', error));
        }

        function updateUnitInfo() {
            if (!config) return;

            const unitOptions = document.getElementById('unit-options');
            unitOptions.innerHTML = '';

            Object.entries(config.units).forEach(([unit, info]) => {
                const div = document.createElement('div');
                div.className = 'unit-row';
                div.innerHTML = `
                    <h3>${capitalizeFirstLetter(unit)}</h3>
                    <p>Cost: ${info.cost} Gold</p>
                    <p>Attack: ${info.attack}</p>
                    <p>Defense: ${info.defense}</p>
                    <div class="build-form">
                        <input type="number" min="1" value="1" id="${unit}-count">
                        <button onclick="buildUnit('${unit}')">Build</button>
                    </div>
                `;
                unitOptions.appendChild(div);
            });
        }
    </script>
</body>
</html> 