# Currency Converter React App

A simple React application that converts US Dollars to Indian Rupees.

## Features

- Input field for dollar amount
- Real-time conversion to rupees
- Simple and clean UI
- Clear button to reset values
- Responsive design

## How to Run

1. Install dependencies:
   ```bash
   npm install
   ```

2. Start the development server:
   ```bash
   npm start
   ```

3. Open your browser and go to `http://localhost:3000`

## Key React Concepts Used

- **useState Hook**: Managing component state for dollar and rupee amounts
- **Event Handlers**: `onChange` for input field and `onClick` for clear button
- **Controlled Components**: Input field value controlled by React state
- **Conditional Rendering**: Display rupee amount only when valid input exists

## Code Structure

- `src/App.js` - Main component with conversion logic
- `src/App.css` - Styling for the application
- `src/index.js` - Entry point
- `public/index.html` - HTML template

## Exchange Rate

The app uses a fixed exchange rate of 1 USD = 83 INR for simplicity.

## Exam Points Covered

1. React functional components
2. useState hook for state management
3. Event handling (onChange, onClick)
4. Controlled components
5. Basic styling with CSS
6. Component structure and organization