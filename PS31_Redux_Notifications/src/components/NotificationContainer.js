import React from 'react';
import { useSelector, useDispatch } from 'react-redux';
import { clearAllNotifications } from '../store/notificationSlice';
import NotificationItem from './NotificationItem';
import './NotificationContainer.css';

const NotificationContainer = () => {
  const notifications = useSelector(state => state.notifications.notifications);
  const dispatch = useDispatch();

  const handleClearAll = () => {
    dispatch(clearAllNotifications());
  };

  if (notifications.length === 0) {
    return null;
  }

  return (
    <div className="notification-container">
      {notifications.length > 1 && (
        <button 
          className="clear-all-button"
          onClick={handleClearAll}
        >
          Clear All ({notifications.length})
        </button>
      )}
      {notifications.map(notification => (
        <NotificationItem 
          key={notification.id} 
          notification={notification} 
        />
      ))}
    </div>
  );
};

export default NotificationContainer;