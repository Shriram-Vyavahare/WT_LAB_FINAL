# Digital Clock React Application

A professional real-time digital clock application built with React Hooks that displays the current time in HH:MM:SS format with start/stop functionality.

## Features

- ✅ **Real-time Updates**: Clock updates every second using React Hooks
- ✅ **Professional UI**: Modern glassmorphism design with gradient backgrounds
- ✅ **Start/Stop Control**: Toggle clock running state with intuitive buttons
- ✅ **Reset Functionality**: Reset clock to current time
- ✅ **Status Indicator**: Visual indicator showing if clock is running or stopped
- ✅ **Date Display**: Shows current date alongside the time
- ✅ **Responsive Design**: Works on desktop, tablet, and mobile devices
- ✅ **Smooth Animations**: Hover effects and transitions for better UX

## Technical Implementation

### React Hooks Used:
- **useState()**: Manages current time state and running status
- **useEffect()**: Handles interval creation/cleanup for time updates

### Key Components:
- `DigitalClock.js`: Main clock component with all functionality
- `App.js`: Root application component
- Professional CSS styling with modern design patterns

## Installation & Setup

1. **Install Dependencies**:
   ```bash
   npm install
   ```

2. **Start Development Server**:
   ```bash
   npm start
   ```

3. **Open in Browser**:
   Navigate to `http://localhost:3000`

## Project Structure

```
digital-clock-app/
├── public/
│   └── index.html
├── src/
│   ├── components/
│   │   ├── DigitalClock.js
│   │   └── DigitalClock.css
│   ├── App.js
│   ├── App.css
│   ├── index.js
│   └── index.css
├── package.json
└── README.md
```

## Usage

- **Start/Stop**: Click the play/pause button to start or stop the clock
- **Reset**: Click the reset button to sync the clock with current time and start it
- **Status**: Green dot indicates running, red dot indicates stopped

## Design Features

- **Glassmorphism Effect**: Modern translucent design with backdrop blur
- **Orbitron Font**: Futuristic monospace font for digital display
- **Color Scheme**: Green for active states, red for stopped, blue for reset
- **Responsive Layout**: Adapts to different screen sizes
- **Smooth Animations**: Pulse effect for running indicator and hover transitions

## Browser Compatibility

- Chrome (recommended)
- Firefox
- Safari
- Edge

## License

This project is open source and available under the MIT License.