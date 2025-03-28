# Kingdom Management Game - Public Directory Architecture

This document outlines the architecture of the public directory for the Kingdom Management Game, focusing on the frontend components and their interactions.

## Directory Structure 

## Frontend Components

### HTML Structure (`index.html`)

The main interface is organized into several key sections:

1. **Header Section**
   - Player's kingdom name
   - Resource displays (Gold, Army Power, Defense Power)

2. **Main Content Area**
   - Tab Navigation System
   - Content Sections for each tab:
     - Army (unit building, army management)
     - Castle (upgrades, defense)
     - Village (economy, serfs)
     - World (PvP interactions)

3. **Build Queue**
   - Right column showing current build orders
   - Real-time updates of build progress

4. **Modal Systems**
   - War Results Modal
   - War Reports Modal

### Styling (`css/styles.css`)

The CSS is organized into logical sections:

1. **Global Styles**
   - Base styling and resets
   - Common utility classes
   - Color schemes and typography

2. **Layout Components**
   - Game container layout
   - Header styling
   - Main content grid system
   - Queue column design

3. **Interactive Elements**
   - Button styles
   - Tab navigation
   - Modal designs
   - Resource displays

4. **Responsive Design**
   - Mobile-first approach
   - Breakpoints for different screen sizes

### JavaScript (`js/game.js`)

The client-side logic is structured around several core systems:

1. **Game State Management**
   ```javascript
   let gameState = {
       username: '',
       gold: 0,
       army: {},
       castle: { level: 0, defenseBonus: 0 },
       village: { serfs: 0, incomePerDay: 0 },
       queue: []
   };
   ```

2. **UI Update System**
   - `updateUI()`: Main UI refresh function
   - `updateUnitGrid()`: Updates available units
   - `updateArmyDisplay()`: Updates army status
   - `updateQueueDisplay()`: Updates build queue

3. **Game Actions**
   - Unit building system
   - Castle upgrade mechanism
   - Combat system
   - Resource management

4. **Real-time Features**
   - Build queue processing
   - Resource generation
   - Combat resolution
   - Notification system

## API Integration

The frontend interacts with the backend through several key endpoints:

1. **Game State**
   - Fetch: GET `/api/game-state`
   - Update: POST `/api/update-game-state`

2. **Unit Building**
   - Endpoint: POST `/api/build-unit`
   - Payload: `{ unit: string, count: number }`

3. **Combat System**
   - Attack: POST `/api/attack-kingdom`
   - War Status: GET `/api/check-war-status`
   - War Results: GET `/api/war-end`

4. **Resource Management**
   - Income: GET `/api/get-income`
   - Claim Rewards: POST `/api/claim-loot`

## User Interface Components

### Tab System
- Army Tab: Unit management and training
- Castle Tab: Fortification and upgrades
- Village Tab: Economic management
- World Tab: PvP interactions

### Modal System
1. **War Results Modal**
   - Battle outcome display
   - Casualty reports
   - Loot claiming interface

2. **War Reports Modal**
   - Historical battle records
   - Detailed combat statistics
   - Unclaimed rewards management

## Event Handling

The game uses a comprehensive event system for:
- Tab switching
- Unit building
- Combat initiation
- Resource collection
- Modal management

## Responsive Design

The interface adapts to different screen sizes through:
- Flexible grid layouts
- Mobile-friendly navigation
- Responsive unit cards
- Adaptive modal displays

## Performance Considerations

1. **Update Optimization**
   - Throttled state updates
   - Efficient DOM manipulation
   - Cached element references

2. **Resource Loading**
   - Minified CSS and JavaScript
   - Optimized asset loading
   - Efficient state management

## Future Improvements

1. **UI Enhancements**
   - Unit animation systems
   - Enhanced battle visualizations
   - Improved notification system

2. **Performance Optimizations**
   - WebSocket integration
   - Local state caching
   - Progressive loading

3. **Feature Additions**
   - Alliance system UI
   - Trading interface
   - Achievement display 