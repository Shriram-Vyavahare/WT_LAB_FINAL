import React from 'react';
import DigitalClock from './components/DigitalClock';
import './App.css';

function App() {
  return (
    <div className="App">
      <div className="app-container">
        <h1 className="app-title">Digital Clock</h1>
        <DigitalClock />
      </div>
    </div>
  );
}

export default App;