# Kingdom Game

A medieval strategy game where you build your kingdom, train armies, and engage in epic battles. Built with PHP, JavaScript, and modern web technologies.

![Kingdom Game Screenshot](screenshot.png)

## Features

### Kingdom Management
- Build and upgrade your castle
- Manage resources (gold, wood, stone)
- Train and maintain different types of units
- Research and develop new technologies

### Military System
- Train various military units:
  - Soldiers (balanced units)
  - Archers (ranged attackers)
  - Cavaliers (heavy cavalry)
  - Mages (magical support)
  - Espions (stealth units)
- Strategic battle system with attack/defense mechanics
- PvP and PvE combat options
- War reports and battle analysis

### Economy
- Resource generation system
- Trading mechanics
- Gold management
- Unit maintenance costs

### UI/UX
- Modern, responsive design
- Real-time updates
- Interactive dialogs
- Battle animations
- Notification system

## Tech Stack

- **Backend**: PHP
- **Frontend**: HTML5, CSS3, JavaScript
- **Database**: JSON-based file storage
- **Web Server**: Apache/Nginx
- **Dependencies**: 
  - Font Awesome for icons
  - Modern CSS Grid and Flexbox
  - WebSocket for real-time updates

## Installation

1. Clone the repository:
```bash
git clone https://github.com/yourusername/kingdom.git
cd kingdom
```

2. Set up your web server (Apache/Nginx) to point to the project directory

3. Ensure PHP is installed and configured on your server

4. Set proper permissions for the saves directory:
```bash
chmod 755 saves/
```

5. Configure your web server to handle PHP files

## Configuration

The game can be configured through the `config.json` file, which includes:
- Unit statistics
- Resource generation rates
- Battle mechanics
- Game balance parameters

## Development

### Project Structure
```
kingdom/
├── assets/          # Static assets (images, sounds)
├── saves/           # Player save files
├── styles/          # CSS stylesheets
├── config.json      # Game configuration
├── index.php        # Main game interface
├── process.php      # Game logic processing
└── README.md        # This file
```

### Adding New Features
1. Create new PHP endpoints in `process.php`
2. Add corresponding JavaScript functions in `index.php`
3. Update the UI components as needed
4. Test thoroughly before committing

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## License

This project is licensed under the GNU Affero General Public License v3.0 (AGPL-3.0) - see the [LICENSE](LICENSE) file for details.

This license allows you to:
- Use the code commercially
- Modify the code
- Distribute the code
- Use the code privately
- Sublicense the code

While requiring you to:
- Include the license and copyright notice
- State changes
- Disclose source code
- Use the same license for network use

The license can be terminated if its terms are violated.

## Acknowledgments

- Font Awesome for the icons
- The gaming community for feedback and suggestions
- Contributors who have helped improve the game

## Support

For support, please open an issue in the GitHub repository or contact the maintainers.

## Roadmap

- [ ] Add multiplayer support
- [ ] Implement alliance system
- [ ] Add more unit types
- [ ] Create mobile app version
- [ ] Add achievements system

## Version History

- 1.0.0
  - Initial release
  - Basic game mechanics
  - Core features implementation

## Authors

- Your Name - Initial work

## License

This project is licensed under the GNU Affero General Public License v3.0 (AGPL-3.0) - see the [LICENSE](LICENSE) file for details.
