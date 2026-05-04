function calculateAge() {
    const birthDateInput = document.getElementById('birthDate');
    const resultDiv = document.getElementById('result');
    const ageDisplay = document.getElementById('ageDisplay');
    
    // Get the birth date value
    const birthDate = birthDateInput.value;
    
    // Validate input
    if (!birthDate) {
        showError('Please select your birth date');
        return;
    }
    
    // Convert birth date to Date object
    const birth = new Date(birthDate);
    const today = new Date();
    
    // Check if birth date is in the future
    if (birth > today) {
        showError('Birth date cannot be in the future');
        return;
    }
    
    // Calculate age
    const age = getDetailedAge(birth, today);
    
    // Display result
    showResult(age);
}

function getDetailedAge(birthDate, currentDate) {
    let years = currentDate.getFullYear() - birthDate.getFullYear();
    let months = currentDate.getMonth() - birthDate.getMonth();
    let days = currentDate.getDate() - birthDate.getDate();
    
    // Adjust for negative days
    if (days < 0) {
        months--;
        // Get days in previous month
        const prevMonth = new Date(currentDate.getFullYear(), currentDate.getMonth(), 0);
        days += prevMonth.getDate();
    }
    
    // Adjust for negative months
    if (months < 0) {
        years--;
        months += 12;
    }
    
    return {
        years: years,
        months: months,
        days: days
    };
}

function showResult(age) {
    const resultDiv = document.getElementById('result');
    const ageDisplay = document.getElementById('ageDisplay');
    
    // Format the age display
    let ageText = '';
    
    if (age.years > 0) {
        ageText += `${age.years} year${age.years !== 1 ? 's' : ''}`;
    }
    
    if (age.months > 0) {
        if (ageText) ageText += ', ';
        ageText += `${age.months} month${age.months !== 1 ? 's' : ''}`;
    }
    
    if (age.days > 0) {
        if (ageText) ageText += ', ';
        ageText += `${age.days} day${age.days !== 1 ? 's' : ''}`;
    }
    
    // Handle edge case where age is 0
    if (!ageText) {
        ageText = 'Born today!';
    }
    
    ageDisplay.textContent = `Your age is: ${ageText}`;
    
    // Show result with success styling
    resultDiv.className = 'result';
    resultDiv.style.display = 'block';
}

function showError(message) {
    const resultDiv = document.getElementById('result');
    const ageDisplay = document.getElementById('ageDisplay');
    
    ageDisplay.textContent = message;
    
    // Show result with error styling
    resultDiv.className = 'result error';
    resultDiv.style.display = 'block';
}

// Allow Enter key to trigger calculation
document.getElementById('birthDate').addEventListener('keypress', function(event) {
    if (event.key === 'Enter') {
        calculateAge();
    }
});

// Set max date to today to prevent future dates in date picker
document.getElementById('birthDate').max = new Date().toISOString().split('T')[0];