import React from 'react';
import './ThemeToggle.css';

const ThemeToggle = ({ theme, onToggle }) => {
  return (
    <div className="theme-toggle-container">
      <span className="theme-label">
        {theme === 'light' ? '☀️' : '🌙'} {theme === 'light' ? 'Light' : 'Dark'}
      </span>
      <button 
        className={`toggle-button ${theme}`}
        onClick={onToggle}
        aria-label={`Switch to ${theme === 'light' ? 'dark' : 'light'} mode`}
      >
        <div className={`toggle-slider ${theme}`}>
          <div className="toggle-icon">
            {theme === 'light' ? '🌙' : '☀️'}
          </div>
        </div>
      </button>
    </div>
  );
};

export default ThemeToggle;