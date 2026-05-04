import React, { useState } from 'react';
import { useDispatch } from 'react-redux';
import { addNotification } from './store/notificationSlice';
import NotificationContainer from './components/NotificationContainer';
import './App.css';

const App = () => {
  const dispatch = useDispatch();
  const [customMessage, setCustomMessage] = useState('');
  const [customType, setCustomType] = useState('info');

  const demoMessages = {
    info: [
      'System update available',
      'New message received',
      'Backup completed successfully',
      'Connection established'
    ],
    success: [
      'File uploaded successfully!',
      'Settings saved',
      'Task completed',
      'Payment processed'
    ],
    warning: [
      'Low disk space detected',
      'Session expires in 5 minutes',
      'Unsaved changes detected',
      'Network connection unstable'
    ],
    error: [
      'Failed to save changes',
      'Connection timeout',
      'Invalid credentials',
      'Server error occurred'
    ]
  };

  const handleDemoNotification = (type) => {
    const messages = demoMessages[type];
    const randomMessage = messages[Math.floor(Math.random() * messages.length)];
    
    dispatch(addNotification({
      message: randomMessage,
      type: type
    }));
  };

  const handleCustomNotification = () => {
    if (customMessage.trim()) {
      dispatch(addNotification({
        message: customMessage.trim(),
        type: customType
      }));
      setCustomMessage('');
    }
  };

  const handleKeyPress = (e) => {
    if (e.key === 'Enter') {
      handleCustomNotification();
    }
  };

  return (
    <div className="app">
      <header className="app-header">
        <h1 className="app-title">React Notifications</h1>
        <p className="app-subtitle">Redux-powered notification system</p>
      </header>

      <main className="demo-section">
        <h2 className="demo-title">Try Different Notification Types</h2>
        
        <div className="button-grid">
          {Object.keys(demoMessages).map(type => (
            <button
              key={type}
              className={`demo-button ${type}`}
              onClick={() => handleDemoNotification(type)}
            >
              {type} notification
            </button>
          ))}
        </div>

        <div className="custom-notification">
          <input
            type="text"
            className="custom-input"
            placeholder="Enter your custom message..."
            value={customMessage}
            onChange={(e) => setCustomMessage(e.target.value)}
            onKeyPress={handleKeyPress}
          />
          <select
            className="custom-select"
            value={customType}
            onChange={(e) => setCustomType(e.target.value)}
          >
            <option value="info">Info</option>
            <option value="success">Success</option>
            <option value="warning">Warning</option>
            <option value="error">Error</option>
          </select>
          <button
            className="add-button"
            onClick={handleCustomNotification}
            disabled={!customMessage.trim()}
          >
            Add
          </button>
        </div>
      </main>

      <NotificationContainer />
    </div>
  );
};

export default App;