# Professional Tic-Tac-Toe Game

A modern, responsive Tic-Tac-Toe game built with PHP, featuring a clean and professional user interface.

## Features

### 🎮 Game Features
- **Classic Tic-Tac-Toe gameplay** - Two players take turns placing X's and O's
- **Win detection** - Automatically detects wins, ties, and game over states
- **Session persistence** - Game state is maintained across page refreshes
- **Reset functionality** - Start a new game at any time

### 🎨 Professional UI/UX
- **Modern gradient design** - Beautiful color schemes and smooth transitions
- **Responsive layout** - Works perfectly on desktop, tablet, and mobile devices
- **Smooth animations** - Cell filling animations and hover effects
- **Visual feedback** - Clear indication of current player and game status
- **Confetti celebration** - Special animation when a player wins

### ⌨️ Accessibility & Controls
- **Keyboard navigation** - Full keyboard support for accessibility
- **Keyboard shortcuts**:
  - Press `1-9` to make moves in corresponding cells
  - Press `R` to reset the game
  - Use `Tab` to navigate between cells
  - Press `Enter` or `Space` to select a cell
- **Screen reader friendly** - Proper ARIA labels and semantic HTML
- **Focus management** - Clear visual focus indicators

### 🛠️ Technical Features
- **Object-oriented PHP** - Clean, maintainable code structure
- **Session management** - Secure game state persistence
- **Form handling** - Proper POST request handling for moves
- **CSS Grid layout** - Modern, flexible game board layout
- **Progressive enhancement** - Works without JavaScript, enhanced with it

## File Structure

```
tic-tac-toe/
├── index.php      # Main game logic and HTML structure
├── style.css      # Professional styling and responsive design
├── script.js      # Enhanced user experience and interactions
└── README.md      # This documentation file
```

## Installation & Setup

1. **Requirements**:
   - PHP 7.0 or higher
   - Web server (Apache, Nginx, or PHP built-in server)

2. **Quick Start**:
   ```bash
   # Clone or download the files to your web directory
   # Start PHP built-in server (for development)
   php -S localhost:8000
   
   # Open your browser and navigate to:
   # http://localhost:8000
   ```

3. **Production Setup**:
   - Upload files to your web server
   - Ensure PHP sessions are enabled
   - No database required - uses PHP sessions for state management

## How to Play

1. **Starting the Game**:
   - Player X always goes first
   - Click on any empty cell to make your move

2. **Making Moves**:
   - Click on empty cells to place your mark (X or O)
   - Players alternate turns automatically
   - Use keyboard numbers 1-9 for quick moves

3. **Winning**:
   - Get three of your marks in a row (horizontal, vertical, or diagonal)
   - The game will automatically detect and announce the winner
   - Enjoy the confetti celebration!

4. **New Game**:
   - Click the "New Game" button to reset
   - Or press 'R' on your keyboard

## Code Architecture

### PHP Backend (`index.php`)
- **TicTacToe Class**: Handles all game logic
  - `makeMove()`: Processes player moves
  - `checkWinner()`: Detects winning conditions
  - `resetGame()`: Initializes new game state
  - Session management for persistence

### CSS Styling (`style.css`)
- **Responsive Design**: Mobile-first approach with media queries
- **Modern Aesthetics**: Gradient backgrounds, smooth shadows, rounded corners
- **Animations**: Smooth transitions and micro-interactions
- **Accessibility**: High contrast, focus indicators, reduced motion support

### JavaScript Enhancement (`script.js`)
- **Progressive Enhancement**: Game works without JS, enhanced with it
- **Keyboard Navigation**: Full keyboard accessibility
- **Visual Effects**: Confetti animation, hover effects
- **User Feedback**: Loading states, click animations

## Browser Compatibility

- ✅ Chrome 60+
- ✅ Firefox 55+
- ✅ Safari 12+
- ✅ Edge 79+
- ✅ Mobile browsers (iOS Safari, Chrome Mobile)

## Customization

### Colors & Themes
Edit `style.css` to customize:
- Background gradients
- Player colors (X and O)
- Button styles
- Animation effects

### Game Logic
Modify `index.php` to add:
- Different board sizes
- AI opponent
- Score tracking
- Tournament mode

## Performance

- **Lightweight**: ~15KB total file size
- **Fast Loading**: Minimal HTTP requests
- **Efficient**: Session-based state management
- **Optimized**: CSS Grid for layout, minimal JavaScript

## Security

- **Input Validation**: All user inputs are validated
- **Session Security**: Proper session handling
- **XSS Protection**: HTML output is escaped
- **CSRF Protection**: Form-based interactions only

## License

This project is open source and available under the MIT License.

## Contributing

Feel free to submit issues, fork the repository, and create pull requests for any improvements.

---

**Enjoy playing Tic-Tac-Toe!** 🎮