# React Theme Toggle App

A clean and modern React application that demonstrates light/dark mode switching using React Hooks with persistent theme selection.

## Features

✨ **Smooth Theme Transitions** - Seamless switching between light and dark modes
💾 **Persistent Theme Selection** - Theme preference saved in localStorage
🎨 **Clean and Modern Design** - Professional UI with smooth animations
📱 **Responsive Layout** - Works perfectly on all device sizes
♿ **Accessible Toggle Button** - Proper ARIA labels and keyboard navigation
🎯 **React Hooks Implementation** - Uses useState and useEffect hooks

## Technologies Used

- React 18
- React Hooks (useState, useEffect)
- CSS3 with CSS Variables
- Local Storage API
- Modern CSS Grid and Flexbox

## Getting Started

### Prerequisites

- Node.js (version 14 or higher)
- npm or yarn

### Installation

1. Clone or download the project files
2. Install dependencies:
   ```bash
   npm install
   ```

3. Start the development server:
   ```bash
   npm start
   ```

4. Open [http://localhost:3000](http://localhost:3000) to view it in the browser

## Project Structure

```
src/
├── App.js                 # Main application component
├── App.css               # Global styles and theme variables
├── index.js              # React DOM entry point
└── components/
    ├── ThemeToggle.js    # Toggle button component
    ├── ThemeToggle.css   # Toggle button styles
    ├── ContentSection.js # Main content component
    └── ContentSection.css # Content section styles
```

## How It Works

### Theme State Management
- Uses `useState` hook to manage current theme state
- Initializes theme from localStorage or defaults to 'light'
- Theme state is lifted up to the App component for global access

### Persistence
- `useEffect` hook saves theme preference to localStorage
- Theme is automatically restored on page reload/component re-render
- Document body class is updated for global theme application

### Dynamic Styling
- CSS variables define color schemes for both themes
- Theme classes (`.light` and `.dark`) are applied conditionally
- Smooth transitions between theme changes using CSS transitions

### Toggle Functionality
- Clean toggle button with animated slider
- Visual feedback with hover and active states
- Accessible with proper ARIA labels
- Displays current theme mode with icons

## Customization

### Adding New Themes
1. Define new color variables in `App.css`
2. Add corresponding CSS classes
3. Update the toggle logic in `App.js`

### Styling Modifications
- Modify CSS variables in `:root` for global color changes
- Adjust `--transition` variable for animation speed
- Update `--border-radius` for different corner styles

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)

## Available Scripts

- `npm start` - Runs the app in development mode
- `npm run build` - Builds the app for production
- `npm test` - Launches the test runner
- `npm run eject` - Ejects from Create React App (one-way operation)

## License

This project is open source and available under the MIT License.