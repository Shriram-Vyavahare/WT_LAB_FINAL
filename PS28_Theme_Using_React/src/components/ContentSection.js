import React from 'react';
import './ContentSection.css';

const ContentSection = ({ theme }) => {
  return (
    <div className="content-section">
      <div className="card">
        <h2>Welcome to Theme Toggle</h2>
        <p>
          This application demonstrates a clean and modern implementation of light/dark mode 
          switching using React Hooks. The theme preference is automatically saved and 
          persisted across browser sessions.
        </p>
        
        <div className="features">
          <h3>Features:</h3>
          <ul>
            <li>✨ Smooth theme transitions</li>
            <li>💾 Persistent theme selection</li>
            <li>🎨 Clean and modern design</li>
            <li>📱 Responsive layout</li>
            <li>♿ Accessible toggle button</li>
          </ul>
        </div>
        
        <div className="theme-info">
          <div className="info-badge">
            Current Theme: <strong>{theme}</strong>
          </div>
        </div>
      </div>
      
      <div className="demo-section">
        <h3>Color Demonstration</h3>
        <div className="color-boxes">
          <div className="color-box primary">Primary</div>
          <div className="color-box secondary">Secondary</div>
          <div className="color-box accent">Accent</div>
        </div>
      </div>
    </div>
  );
};

export default ContentSection;