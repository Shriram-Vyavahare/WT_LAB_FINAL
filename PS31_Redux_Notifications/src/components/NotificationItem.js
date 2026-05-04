import React, { useEffect } from 'react';
import { useDispatch } from 'react-redux';
import { removeNotification } from '../store/notificationSlice';
import './NotificationItem.css';

const NotificationItem = ({ notification }) => {
  const dispatch = useDispatch();

  const handleDismiss = () => {
    dispatch(removeNotification(notification.id));
  };

  // Auto-dismiss after 5 seconds
  useEffect(() => {
    const timer = setTimeout(() => {
      dispatch(removeNotification(notification.id));
    }, 5000);

    return () => clearTimeout(timer);
  }, [dispatch, notification.id]);

  const getIcon = () => {
    switch (notification.type) {
      case 'success':
        return '✓';
      case 'error':
        return '✕';
      case 'warning':
        return '⚠';
      default:
        return 'ℹ';
    }
  };

  return (
    <div className={`notification-item notification-${notification.type}`}>
      <div className="notification-content">
        <span className="notification-icon">{getIcon()}</span>
        <span className="notification-message">{notification.message}</span>
      </div>
      <button 
        className="notification-dismiss"
        onClick={handleDismiss}
        aria-label="Dismiss notification"
      >
        ×
      </button>
    </div>
  );
};

export default NotificationItem;