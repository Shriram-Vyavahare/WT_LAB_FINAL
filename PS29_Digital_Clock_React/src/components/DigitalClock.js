import React, { useState, useEffect } from 'react';
import './DigitalClock.css';

const DigitalClock = () => {
  const [currentTime, setCurrentTime] = useState(new Date());
  const [isRunning, setIsRunning] = useState(true);

  useEffect(() => {
    let interval = null;
    
    if (isRunning) {
      interval = setInterval(() => {
        setCurrentTime(new Date());
      }, 1000);
    } else {
      clearInterval(interval);
    }

    return () => clearInterval(interval);
  }, [isRunning]);

  const formatTime = (date) => {
    const hours = date.getHours().toString().padStart(2, '0');
    const minutes = date.getMinutes().toString().padStart(2, '0');
    const seconds = date.getSeconds().toString().padStart(2, '0');
    return `${hours}:${minutes}:${seconds}`;
  };

  const formatDate = (date) => {
    const options = { 
      weekday: 'long', 
      year: 'numeric', 
      month: 'long', 
      day: 'numeric' 
    };
    return date.toLocaleDateString('en-US', options);
  };

  const toggleClock = () => {
    setIsRunning(!isRunning);
  };

  const resetClock = () => {
    setCurrentTime(new Date());
    setIsRunning(true);
  };

  return (
    <div className="digital-clock">
      <div className="clock-display">
        <div className="time-display">
          {formatTime(currentTime)}
        </div>
        <div className="date-display">
          {formatDate(currentTime)}
        </div>
        <div className="status-indicator">
          <span className={`status-dot ${isRunning ? 'running' : 'stopped'}`}></span>
          <span className="status-text">
            {isRunning ? 'Running' : 'Stopped'}
          </span>
        </div>
      </div>
      
      <div className="clock-controls">
        <button 
          className={`control-btn ${isRunning ? 'stop-btn' : 'start-btn'}`}
          onClick={toggleClock}
        >
          {isRunning ? (
            <>
              <span className="btn-icon">⏸</span>
              Stop
            </>
          ) : (
            <>
              <span className="btn-icon">▶</span>
              Start
            </>
          )}
        </button>
        
        <button 
          className="control-btn reset-btn"
          onClick={resetClock}
        >
          <span className="btn-icon">🔄</span>
          Reset
        </button>
      </div>
    </div>
  );
};

export default DigitalClock;