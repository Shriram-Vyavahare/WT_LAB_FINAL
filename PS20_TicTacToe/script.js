// Enhanced user experience with JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Add smooth transitions and interactions
    const cells = document.querySelectorAll('.cell-button');
    const resetButton = document.querySelector('.reset-button');
    
    // Add hover effects for better feedback
    cells.forEach(cell => {
        cell.addEventListener('mouseenter', function() {
            if (!this.disabled) {
                this.style.background = 'rgba(102, 126, 234, 0.1)';
            }
        });
        
        cell.addEventListener('mouseleave', function() {
            this.style.background = 'transparent';
        });
        
        // Add click animation
        cell.addEventListener('click', function() {
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 150);
        });
    });
    
    // Reset button animation
    if (resetButton) {
        resetButton.addEventListener('click', function() {
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 150);
        });
    }
    
    // Add keyboard navigation
    document.addEventListener('keydown', function(e) {
        // Press 'R' to reset game
        if (e.key.toLowerCase() === 'r' && resetButton) {
            resetButton.click();
        }
        
        // Press number keys 1-9 to make moves
        const num = parseInt(e.key);
        if (num >= 1 && num <= 9) {
            const cellIndex = num - 1;
            const targetCell = cells[cellIndex];
            if (targetCell && !targetCell.disabled) {
                targetCell.click();
            }
        }
    });
    
    // Add visual feedback for game state changes
    const status = document.querySelector('.status');
    if (status && status.classList.contains('winner')) {
        // Add confetti effect for winner
        createConfetti();
    }
    
    // Simple confetti effect
    function createConfetti() {
        const colors = ['#e74c3c', '#3498db', '#f39c12', '#2ecc71', '#9b59b6'];
        const confettiCount = 50;
        
        for (let i = 0; i < confettiCount; i++) {
            setTimeout(() => {
                const confetti = document.createElement('div');
                confetti.style.position = 'fixed';
                confetti.style.left = Math.random() * 100 + 'vw';
                confetti.style.top = '-10px';
                confetti.style.width = '10px';
                confetti.style.height = '10px';
                confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                confetti.style.borderRadius = '50%';
                confetti.style.pointerEvents = 'none';
                confetti.style.zIndex = '1000';
                confetti.style.animation = 'fall 3s linear forwards';
                
                document.body.appendChild(confetti);
                
                setTimeout(() => {
                    confetti.remove();
                }, 3000);
            }, i * 100);
        }
    }
    
    // Add CSS for confetti animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes fall {
            0% {
                transform: translateY(-10px) rotate(0deg);
                opacity: 1;
            }
            100% {
                transform: translateY(100vh) rotate(360deg);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(style);
    
    // Add sound effects (optional - requires audio files)
    function playSound(type) {
        // Placeholder for sound effects
        // You can add audio files and uncomment this section
        /*
        const audio = new Audio();
        switch(type) {
            case 'move':
                audio.src = 'sounds/move.mp3';
                break;
            case 'win':
                audio.src = 'sounds/win.mp3';
                break;
            case 'tie':
                audio.src = 'sounds/tie.mp3';
                break;
        }
        audio.play().catch(e => console.log('Audio play failed:', e));
        */
    }
    
    // Add accessibility improvements
    cells.forEach((cell, index) => {
        cell.setAttribute('aria-label', `Cell ${index + 1}`);
        cell.setAttribute('tabindex', '0');
    });
    
    // Add focus management
    let currentFocus = 0;
    
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Tab') {
            e.preventDefault();
            currentFocus = (currentFocus + 1) % cells.length;
            cells[currentFocus].focus();
        }
        
        if (e.key === 'Enter' || e.key === ' ') {
            const focusedCell = document.activeElement;
            if (focusedCell.classList.contains('cell-button')) {
                e.preventDefault();
                focusedCell.click();
            }
        }
    });
    
    // Performance optimization: Preload next game state
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            // Add loading state
            const button = this.querySelector('button');
            if (button) {
                button.style.opacity = '0.7';
                button.disabled = true;
            }
        });
    });
    
    console.log('Tic-Tac-Toe game initialized successfully!');
    console.log('Keyboard shortcuts:');
    console.log('- Press 1-9 to make moves');
    console.log('- Press R to reset game');
    console.log('- Use Tab to navigate cells');
    console.log('- Press Enter/Space to select cell');
});