import React, { useState } from 'react';
import './App.css';

function App() {
  // State to store the dollar amount entered by user
  const [dollars, setDollars] = useState('');
  
  // State to store the converted rupee amount
  const [rupees, setRupees] = useState('');
  
  // Exchange rate (1 USD = 83 INR - simplified for exam)
  const exchangeRate = 83;
  
  // Event handler for input change
  const handleInputChange = (event) => {
    const dollarAmount = event.target.value;
    setDollars(dollarAmount);
    
    // Convert to rupees if input is valid
    if (dollarAmount && !isNaN(dollarAmount)) {
      const convertedAmount = (parseFloat(dollarAmount) * exchangeRate).toFixed(2);
      setRupees(convertedAmount);
    } else {
      setRupees('');
    }
  };
  
  // Event handler to clear the form
  const handleClear = () => {
    setDollars('');
    setRupees('');
  };

  return (
    <div className="App">
      <div className="converter-container">
        <h1>Currency Converter</h1>
        <p>Convert USD to INR</p>
        
        <div className="input-section">
          <label htmlFor="dollar-input">Enter Amount in Dollars ($):</label>
          <input
            id="dollar-input"
            type="number"
            value={dollars}
            onChange={handleInputChange}
            placeholder="Enter dollar amount"
            min="0"
            step="0.01"
          />
        </div>
        
        <div className="result-section">
          <label>Amount in Rupees (₹):</label>
          <div className="result-display">
            {rupees ? `₹ ${rupees}` : '₹ 0.00'}
          </div>
        </div>
        
        <div className="exchange-rate">
          <small>Exchange Rate: 1 USD = {exchangeRate} INR</small>
        </div>
        
        <button onClick={handleClear} className="clear-btn">
          Clear
        </button>
      </div>
    </div>
  );
}

export default App;