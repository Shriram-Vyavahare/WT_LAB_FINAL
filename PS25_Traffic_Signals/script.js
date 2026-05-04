// Traffic Light System JavaScript

class TrafficLightSystem {
    constructor() {
        this.currentState = 'red';
        this.isAutomatic = false;
        this.timer = null;
        this.timeRemaining = 0;
        this.pedestrianRequested = false;
        
        // Traffic light timing (in seconds)
        this.timings = {
            red: 30,
            yellow: 5,
            green: 25
        };
        
        // Initialize the system
        this.init();
    }
    
    init() {
        this.updateDisplay();
        this.updateStatus();
        this.setLight('red');
        
        // Initialize pedestrian light
        this.setPedestrianLight('dont-walk');
        
        // Initialize intersection diagram
        this.updateIntersectionDiagram();
    }
    
    setLight(color) {
        // Clear all lights
        document.getElementById('red').classList.remove('active');
        document.getElementById('yellow').classList.remove('active');
        document.getElementById('green').classList.remove('active');
        
        // Set the specified light
        document.getElementById(color).classList.add('active');
        
        this.currentState = color;
        this.updateStatus();
        this.updateIntersectionDiagram();
        
        // Handle pedestrian crossing logic
        if (color === 'red') {
            this.setPedestrianLight('walk');
        } else {
            this.setPedestrianLight('dont-walk');
        }
    }
    
    setPedestrianLight(state) {
        const walkLight = document.getElementById('walk');
        const dontWalkLight = document.getElementById('dontWalk');
        
        walkLight.classList.remove('active');
        dontWalkLight.classList.remove('active');
        
        if (state === 'walk') {
            walkLight.classList.add('active');
        } else {
            dontWalkLight.classList.add('active');
        }
    }
    
    updateIntersectionDiagram() {
        // Clear all mini lights
        const directions = ['north', 'east', 'south', 'west'];
        const colors = ['', 'Yellow', 'Green'];
        
        directions.forEach(direction => {
            colors.forEach(color => {
                const lightId = direction + (color === '' ? 'Light' : color);
                const element = document.getElementById(lightId);
                if (element) {
                    element.classList.remove('active');
                }
            });
        });
        
        // Set lights based on current state
        if (this.currentState === 'red') {
            // North-South red, East-West green
            document.getElementById('northLight').classList.add('active');
            document.getElementById('southLight').classList.add('active');
            document.getElementById('eastGreen').classList.add('active');
            document.getElementById('westGreen').classList.add('active');
        } else if (this.currentState === 'yellow') {
            // North-South yellow, East-West yellow
            document.getElementById('northYellow').classList.add('active');
            document.getElementById('southYellow').classList.add('active');
            document.getElementById('eastYellow').classList.add('active');
            document.getElementById('westYellow').classList.add('active');
        } else if (this.currentState === 'green') {
            // North-South green, East-West red
            document.getElementById('northGreen').classList.add('active');
            document.getElementById('southGreen').classList.add('active');
            document.getElementById('eastLight').classList.add('active');
            document.getElementById('westLight').classList.add('active');
        }
    }
    
    startAutomatic() {
        this.isAutomatic = true;
        this.updateStatus();
        this.runAutomaticCycle();
    }
    
    stopAutomatic() {
        this.isAutomatic = false;
        if (this.timer) {
            clearTimeout(this.timer);
            this.timer = null;
        }
        this.timeRemaining = 0;
        this.updateStatus();
    }
    
    runAutomaticCycle() {
        if (!this.isAutomatic) return;
        
        const currentTiming = this.timings[this.currentState];
        this.timeRemaining = currentTiming;
        
        // Update countdown
        const countdown = setInterval(() => {
            this.timeRemaining--;
            this.updateStatus();
            
            if (this.timeRemaining <= 0) {
                clearInterval(countdown);
            }
        }, 1000);
        
        // Set timer for next state
        this.timer = setTimeout(() => {
            if (this.isAutomatic) {
                this.nextState();
                this.runAutomaticCycle();
            }
        }, currentTiming * 1000);
    }
    
    nextState() {
        switch (this.currentState) {
            case 'red':
                this.setLight('green');
                break;
            case 'green':
                this.setLight('yellow');
                break;
            case 'yellow':
                this.setLight('red');
                break;
        }
    }
    
    requestCrossing() {
        if (!this.pedestrianRequested) {
            this.pedestrianRequested = true;
            this.showNotification('Pedestrian crossing requested');
            
            // If in automatic mode and not red, speed up to red
            if (this.isAutomatic && this.currentState !== 'red') {
                if (this.currentState === 'green') {
                    // Switch to yellow immediately
                    clearTimeout(this.timer);
                    this.setLight('yellow');
                    this.timeRemaining = 3; // Shorter yellow for pedestrian
                    
                    this.timer = setTimeout(() => {
                        if (this.isAutomatic) {
                            this.setLight('red');
                            this.pedestrianRequested = false;
                            this.runAutomaticCycle();
                        }
                    }, 3000);
                }
            }
        }
    }
    
    showNotification(message) {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = 'notification';
        notification.textContent = message;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: #3498db;
            color: white;
            padding: 15px 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
            z-index: 1000;
            animation: slideIn 0.3s ease;
        `;
        
        document.body.appendChild(notification);
        
        // Remove notification after 3 seconds
        setTimeout(() => {
            notification.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 3000);
    }
    
    updateStatus() {
        document.getElementById('currentState').textContent = 
            this.currentState.charAt(0).toUpperCase() + this.currentState.slice(1);
        
        document.getElementById('timeRemaining').textContent = 
            this.timeRemaining > 0 ? `${this.timeRemaining}s` : '--';
        
        document.getElementById('currentMode').textContent = 
            this.isAutomatic ? 'Automatic' : 'Manual';
    }
    
    updateDisplay() {
        // Add any additional display updates here
    }
}

// Animation styles for notifications
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);

// Initialize the traffic light system
let trafficSystem;

// Wait for DOM to load
document.addEventListener('DOMContentLoaded', function() {
    trafficSystem = new TrafficLightSystem();
});

// Global functions for button controls
function startAutomatic() {
    trafficSystem.startAutomatic();
}

function stopAutomatic() {
    trafficSystem.stopAutomatic();
}

function setLight(color) {
    trafficSystem.stopAutomatic();
    trafficSystem.setLight(color);
}

function requestCrossing() {
    trafficSystem.requestCrossing();
}

// Add some interactive features
document.addEventListener('keydown', function(event) {
    switch(event.key) {
        case '1':
            setLight('red');
            break;
        case '2':
            setLight('yellow');
            break;
        case '3':
            setLight('green');
            break;
        case ' ':
            event.preventDefault();
            if (trafficSystem.isAutomatic) {
                stopAutomatic();
            } else {
                startAutomatic();
            }
            break;
        case 'p':
        case 'P':
            requestCrossing();
            break;
    }
});

// Add keyboard shortcuts info
window.addEventListener('load', function() {
    const shortcutsInfo = document.createElement('div');
    shortcutsInfo.innerHTML = `
        <div style="position: fixed; bottom: 20px; left: 20px; background: rgba(0,0,0,0.8); color: white; padding: 15px; border-radius: 10px; font-size: 12px; z-index: 1000;">
            <strong>Keyboard Shortcuts:</strong><br>
            1 - Red Light<br>
            2 - Yellow Light<br>
            3 - Green Light<br>
            Space - Toggle Auto Mode<br>
            P - Request Crossing
        </div>
    `;
    document.body.appendChild(shortcutsInfo);
    
    // Hide shortcuts after 5 seconds
    setTimeout(() => {
        shortcutsInfo.style.opacity = '0.3';
    }, 5000);
    
    // Show on hover
    shortcutsInfo.addEventListener('mouseenter', () => {
        shortcutsInfo.style.opacity = '1';
    });
    
    shortcutsInfo.addEventListener('mouseleave', () => {
        shortcutsInfo.style.opacity = '0.3';
    });
});