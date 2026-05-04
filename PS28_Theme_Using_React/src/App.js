import React, { useState, useEffect } from 'react';
import './App.css';
import ThemeToggle from './components/ThemeToggle';
import ContentSection from './components/ContentSection';

function App() {
  // Initialize theme from localStorage or default to 'light'
  const [theme, setTheme] = useState(() => {
    const savedTheme = localStorage.getItem('theme');
    return savedTheme || 'light';
  });

  // Persist theme selection in localStorage
  useEffect(() => {
    localStorage.setItem('theme', theme);
    // Apply theme to document body for global styling
    document.body.className = theme;
  }, [theme]);

  const toggleTheme = () => {
    setTheme(prevTheme => prevTheme === 'light' ? 'dark' : 'light');
  };

  return (
    <div className={`app ${theme}`}>
      <div className="container">
        <header className="header">
          <h1 className="title">Theme Toggle App</h1>
          <ThemeToggle theme={theme} onToggle={toggleTheme} />
        </header>
        
        <main className="main-content">
          <ContentSection theme={theme} />
        </main>
        
        <footer className="footer">
          <p>Built with React Hooks • Current theme: <span className="theme-indicator">{theme} mode</span></p>
        </footer>
      </div>
    </div>
  );
}

export default App;