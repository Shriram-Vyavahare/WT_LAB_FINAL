# React Notifications App

A professional React application that displays system notifications using Redux for state management.

## Features

- ✅ Redux store for notification management
- ✅ Add and remove notification actions
- ✅ Clean notification reducer
- ✅ Professional UI with smooth animations
- ✅ Auto-dismiss notifications after 5 seconds
- ✅ Manual dismiss functionality
- ✅ Multiple notification types (info, success, warning, error)
- ✅ Clear all notifications option
- ✅ Responsive design
- ✅ Custom notification creation

## Project Structure

```
src/
├── components/
│   ├── NotificationContainer.js    # Main notification display container
│   ├── NotificationContainer.css
│   ├── NotificationItem.js         # Individual notification component
│   └── NotificationItem.css
├── store/
│   ├── store.js                    # Redux store configuration
│   └── notificationSlice.js        # Notification actions and reducer
├── App.js                          # Main application component
├── App.css
├── index.js                        # Application entry point
└── index.css
```

## Redux Implementation

### Store Structure
```javascript
{
  notifications: {
    notifications: [
      {
        id: unique_id,
        message: "Notification message",
        type: "info|success|warning|error",
        timestamp: "ISO_string"
      }
    ]
  }
}
```

### Actions
- `addNotification(payload)` - Adds a new notification
- `removeNotification(id)` - Removes a specific notification
- `clearAllNotifications()` - Removes all notifications

## Getting Started

1. Install dependencies:
```bash
npm install
```

2. Start the development server:
```bash
npm start
```

3. Open [http://localhost:3000](http://localhost:3000) to view the app

## Usage

### Adding Notifications Programmatically

```javascript
import { useDispatch } from 'react-redux';
import { addNotification } from './store/notificationSlice';

const dispatch = useDispatch();

// Add a notification
dispatch(addNotification({
  message: "Your message here",
  type: "success" // info, success, warning, error
}));
```

### Notification Types

- **Info** (blue) - General information
- **Success** (green) - Success messages
- **Warning** (orange) - Warning messages  
- **Error** (red) - Error messages

## Features in Detail

- **Auto-dismiss**: Notifications automatically disappear after 5 seconds
- **Manual dismiss**: Click the × button to dismiss immediately
- **Bulk actions**: Clear all notifications with one click
- **Responsive**: Works on desktop and mobile devices
- **Animations**: Smooth slide-in animations for new notifications
- **Professional styling**: Clean, modern design with hover effects

## Technologies Used

- React 18
- Redux Toolkit
- React-Redux
- CSS3 with animations
- Modern ES6+ JavaScript

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)