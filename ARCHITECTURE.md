# Kingdom Management Game Architecture

This document outlines the architecture of the Kingdom Management Game, a browser-based strategy game where players build and manage their own kingdoms, train armies, and attack other kingdoms.

## Project Structure

The project follows a PHP-based architecture with the following components:

- **Frontend**: HTML, CSS, and JavaScript embedded in `index.php`
- **Backend**: PHP files for game logic and data management
- **Data Storage**: JSON files for game state persistence
- **Configuration**: Game settings and constants in `config.php`

## Directory Structure

```
/
├── index.php              # Main entry point with HTML, CSS, and JavaScript
├── game_data.php          # Core game data functions
├── config.php             # Game configuration
├── login.php              # User authentication
├── *.php                  # Various PHP backend files
├── styles.css             # Main stylesheet
└── /saves/                # Player save files
    └── {username}.json    # Individual player data
```

## Core Components

### Frontend (UI)

The game's user interface is built with HTML, CSS, and JavaScript embedded directly in `index.php`. The UI is organized into tabs:

- **Kingdom**: Main view of the player's kingdom
- **Resources**: Shows resources and income
- **Army**: Displays army units and training options
- **World**: Shows other kingdoms that can be attacked
- **War**: Handles war-related activities

### Backend (Game Logic)

The backend consists of multiple PHP files that handle different aspects of the game:

| File | Description |
|------|-------------|
| `index.php` | Main entry point containing UI structure, CSS, and JavaScript |
| `game_data.php` | Core game data functions (getGameData, updateGameData, etc.) |
| `config.php` | Game configuration settings (unit stats, building costs, etc.) |
| `login.php` | Handles user authentication |
| `register.php` | Handles user registration |
| `get_game_state.php` | Returns the current game state for the player |
| `update_game_state.php` | Updates the game state based on player actions |
| `build_serf.php` | Handles building serf units |
| `build_soldier.php` | Handles building soldier units |
| `build_mercenaire.php` | Handles building mercenaire units |
| `build_cavalier.php` | Handles building cavalier units |
| `build_arbaletier.php` | Handles building arbaletier units |
| `build_espion.php` | Handles building espion units |
| `build_mage.php` | Handles building mage units |
| `upgrade_castle.php` | Handles castle upgrades |
| `get_kingdoms.php` | Returns list of other kingdoms for the World tab |
| `attack_kingdom.php` | Initiates an attack on another kingdom |
| `war_end.php` | Processes the results of a war when it ends |
| `get_war_reports.php` | Retrieves war reports for the player |
| `claim_gold.php` | Claims gold from successful attacks |
| `claim_loot.php` | Claims loot from successful attacks |
| `claim_report_loot.php` | Claims loot from war reports |
| `defend_castle.php` | Handles castle defense during attacks |
| `give_gold.php` | Handles giving gold to avoid attacks |
| `simulate_attack.php` | Simulates attack results for preview |
| `clear_defense_results.php` | Clears previous defense results |
| `check_war_status.php` | Checks if a war is in progress and returns status |
| `clear_recent_units.php` | Clears the recently added units list |
| `clear_war_results.php` | Clears war results after viewing |
| `game_state.php` | Manages game state operations |
| `generate_income.php` | Generates income based on serfs and time |
| `get_config.php` | Returns game configuration settings |
| `get_income.php` | Calculates and returns income information |
| `load_save.php` | Loads a saved game |
| `logout.php` | Handles user logout |
| `mark_results_seen.php` | Marks war results as seen |
| `process.php` | Processes various game actions |
| `process_war_results.php` | Processes war results after a war ends |
| `queue.php` | Manages the building queue |
| `reload_config.php` | Reloads game configuration |
| `reset_game.php` | Resets the game state |
| `war.php` | Handles war-related operations |

### Data Storage

Game data is stored in JSON files in the `saves/` directory:

- Each player's game state is stored in a file named `{username}.json`
- The JSON structure contains all player data including resources, army, buildings, and war status

## Game Mechanics

### Resource Management

- Players earn gold over time based on their serf count
- Gold is used to build units and upgrade the castle
- Resource income is calculated and added periodically

### Army Management

- Players can build different types of units with varying costs and stats
- Units have attack and defense values defined in `config.php`
- Army strength is calculated based on unit counts and their stats

### Combat System

1. **Attack Initiation**:
   - Player selects a target kingdom in the World tab
   - Attack is processed by `attack_kingdom.php`
   - A countdown timer is displayed (5 seconds for testing)

2. **Attack Resolution**:
   - When the timer ends, `war_end.php` calculates the results
   - Attack strength vs. defense strength determines outcome
   - Gold is stolen based on the attack/defense ratio:
     - No gold stolen if ratio < 1
     - 100% gold stolen if ratio ≥ 2
     - Linear scaling for ratios between 1 and 2

3. **War Reports**:
   - Results are stored in the player's war reports
   - Players can view reports and claim stolen gold

### Castle Defense

- Castle provides a defense bonus to all units
- Castle can be upgraded to increase defense bonus
- When attacked, players can choose to defend or give gold

## Client-Server Communication

- Client-side JavaScript makes AJAX requests to PHP endpoints
- Server responds with JSON data
- The client updates the UI based on the response

## Key JavaScript Functions

### Core Functions (Embedded in `index.php`)

| Function | Description |
|----------|-------------|
| `updateGameState()` | Fetches and updates the game state |
| `updateUI()` | Updates the user interface with game data |
| `updateQueueDisplay()` | Updates the building queue display |
| `updateCountdowns()` | Updates countdown timers |
| `formatTime()` | Formats time in seconds to a readable format |
| `showNotification()` | Shows a notification message |
| `capitalizeFirstLetter()` | Capitalizes the first letter of a string |
| `loadConfig()` | Loads configuration from the server |
| `reloadConfig()` | Reloads game configuration |
| `updateUnitInfo()` | Updates unit information display |
| `updateUnitInfoFromConfig()` | Updates unit info from config data |
| `resetGame()` | Resets the game to initial state |
| `buildSerf()` | Builds serf units |
| `buildUnit()` | Builds military units |
| `calculateArmyStrength()` | Calculates and displays army strength |
| `upgradeCastle()` | Upgrades the castle |
| `simulateCastleAttack()` | Simulates a castle attack |
| `sendToWar()` | Sends the army to war |
| `checkWarStatus()` | Checks the status of an ongoing war |
| `updateWarTimer()` | Updates the war timer display |
| `resetWarButton()` | Resets the war button after a war |
| `checkWarEnd()` | Checks if a war has ended and fetches the results |
| `showWarResults()` | Shows war results |
| `closeWarResultsModal()` | Closes the war results modal |
| `showWarReports()` | Displays the war reports modal |
| `updateWarReportsButton()` | Updates the war reports button with notification badge |
| `loadKingdoms()` | Loads other kingdoms for the World tab |
| `attackKingdom()` | Initiates an attack on another kingdom |
| `defendCastle()` | Defends the castle against an attack |
| `giveGold()` | Gives gold to avoid an attack |
| `claimLoot()` | Claims loot from successful attacks |

## Attack Button Types

The game has three distinct types of attack buttons, each serving a different purpose:

1. **Send Army to War** (`send-to-war-btn`):
   - Located in the Army tab
   - Initiates a PVE (Player vs. Environment) attack
   - Player's army attacks NPC enemies
   - Calls the `sendToWar()` function

2. **Simulate Defense** (`defense-simulation-btn`):
   - Located in the footer controls
   - Initiates a PVE defense simulation
   - Tests player's defenses against simulated attacks
   - Previously labeled as "Simulate Attack" but renamed to avoid confusion

3. **Attack** (`PVP-attack-btn`):
   - Located in the World tab
   - Initiates a PVP (Player vs. Player) attack
   - Player's army attacks another player's kingdom
   - Calls the `attackKingdom()` function

These buttons are distinctly styled and named to avoid confusion and ensure that changes to one don't affect the others.

## Recent Code Improvements

### CSS Organization
- All CSS has been moved from inline `<style>` tags in `index.php` to the external `styles.css` file
- This separation of concerns improves maintainability and follows best practices for web development
- No styling changes were made during this migration, only the location of the CSS code

### Error Handling Improvements
- Added robust error handling in JavaScript functions:
  - `checkWarStatus()` now includes null checks for DOM elements before accessing them
  - `updateUnitInfoFromConfig()` validates configuration data before attempting to use it
  - These changes prevent common JavaScript errors when elements don't exist or data isn't loaded

### Configuration Management
- Added a new `loadUnitConfig()` function that properly loads unit configuration data
- Includes fallback to default configuration values when server data is unavailable
- Stores configuration in `window.CONFIG` for consistent access throughout the application

### UI Improvements
- Removed the redundant "Status" column from War Reports table
- Simplified the user interface while maintaining all necessary functionality
- The "Claim" button in the Actions column now provides the same information that was in the Status column

These improvements enhance the robustness, maintainability, and organization of the codebase without changing the core game functionality.

## PVP Attack System Improvements

### Attack Button Countdown
- Implemented a 5-second visual countdown directly inside the PVP attack button (`PVP-attack-btn`)
- The countdown uses animated styling to provide clear visual feedback to the player
- Added CSS animations with the `pulse` effect to make the countdown more engaging
- Button state changes from "Attack" → "Attacking in X" → "Attacking..." during the process

### War Report Enhancements
- Added a new "Opponent" column to the War Reports table
- Reports now display the name of the attacked player for PVP battles
- Shows "Computer" for PVE attacks initiated with the "Send Army to War" button
- Improved date formatting to use a more readable format: "Thu Mar 27 8:32PM"

### Target Kingdom Impact
- PVP attacks now have real consequences for the targeted player:
  - Target player loses up to 20% of their gold when successfully attacked
  - Target player's army is reduced by 5-15% randomly across all unit types
  - These changes are saved to the target player's save file immediately
  - Changes are reflected when the target player next logs in

### Attack Process Flow
- The attack process now follows a clear sequence:
  1. Player clicks the Attack button and confirms the attack
  2. 5-second countdown appears inside the button
  3. Attack is executed when countdown reaches zero
  4. War report is immediately generated and added to the reports list
  5. Player can view the report details and claim any loot earned

These improvements make the PVP combat system more interactive and provide immediate feedback to players about their attacks, while ensuring that PVP battles have meaningful consequences in the game world.
