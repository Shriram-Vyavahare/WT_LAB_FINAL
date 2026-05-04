$(document).ready(function() {
    // Initialize with modern theme
    $('body').addClass('modern');
    
    // Handle style button clicks
    $('.style-btn').on('click', function() {
        // Get the selected theme
        const selectedTheme = $(this).data('theme');
        
        // Remove active class from all buttons
        $('.style-btn').removeClass('active');
        
        // Add active class to clicked button
        $(this).addClass('active');
        
        // Remove all theme classes from body
        $('body').removeClass('modern classic dark');
        
        // Add the selected theme class to body
        $('body').addClass(selectedTheme);
        
        // Add a subtle animation effect
        $('body').css('opacity', '0.8');
        setTimeout(function() {
            $('body').css('opacity', '1');
        }, 150);
        
        // Optional: Store user preference in localStorage
        localStorage.setItem('preferredTheme', selectedTheme);
        
        // Optional: Trigger custom event for other scripts to listen to
        $(document).trigger('themeChanged', [selectedTheme]);
    });
    
    // Load saved theme preference on page load
    const savedTheme = localStorage.getItem('preferredTheme');
    if (savedTheme) {
        // Remove default theme
        $('body').removeClass('modern classic dark');
        
        // Apply saved theme
        $('body').addClass(savedTheme);
        
        // Update button states
        $('.style-btn').removeClass('active');
        $(`.style-btn[data-theme="${savedTheme}"]`).addClass('active');
    }
    
    // Add hover effects to cards and info items
    $('.card, .info-item').hover(
        function() {
            $(this).css('transform', 'translateY(-5px)');
        },
        function() {
            $(this).css('transform', 'translateY(0)');
        }
    );
    
    // Add click animation to action buttons
    $('.action-btn, .submit-btn').on('click', function(e) {
        e.preventDefault();
        
        // Create ripple effect
        const button = $(this);
        const ripple = $('<span class="ripple"></span>');
        
        // Add ripple styles
        ripple.css({
            position: 'absolute',
            borderRadius: '50%',
            background: 'rgba(255, 255, 255, 0.6)',
            transform: 'scale(0)',
            animation: 'ripple 0.6s linear',
            left: e.pageX - button.offset().left - 10,
            top: e.pageY - button.offset().top - 10,
            width: '20px',
            height: '20px'
        });
        
        // Make button relative for absolute positioning
        button.css('position', 'relative').css('overflow', 'hidden');
        
        // Add ripple to button
        button.append(ripple);
        
        // Remove ripple after animation
        setTimeout(function() {
            ripple.remove();
        }, 600);
        
        // Button feedback
        button.css('transform', 'scale(0.95)');
        setTimeout(function() {
            button.css('transform', 'scale(1)');
        }, 150);
    });
    
    // Form submission handler
    $('.sample-form').on('submit', function(e) {
        e.preventDefault();
        
        // Get form data
        const formData = {
            name: $('#name').val(),
            email: $('#email').val(),
            message: $('#message').val()
        };
        
        // Simple validation
        if (!formData.name || !formData.email || !formData.message) {
            alert('Please fill in all fields');
            return;
        }
        
        // Simulate form submission
        const submitBtn = $('.submit-btn');
        const originalText = submitBtn.text();
        
        submitBtn.text('Submitting...').prop('disabled', true);
        
        setTimeout(function() {
            alert('Form submitted successfully!\n\nData:\n' + JSON.stringify(formData, null, 2));
            submitBtn.text(originalText).prop('disabled', false);
            
            // Reset form
            $('.sample-form')[0].reset();
        }, 1500);
    });
    
    // Add smooth scrolling for better UX
    $('html').css('scroll-behavior', 'smooth');
    
    // Listen for theme changes and log them (for debugging)
    $(document).on('themeChanged', function(event, themeName) {
        console.log('Theme changed to:', themeName);
        
        // You can add additional logic here when theme changes
        // For example, updating analytics, changing favicon, etc.
    });
    
    // Add keyboard navigation for style buttons
    $('.style-btn').on('keydown', function(e) {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            $(this).click();
        }
    });
    
    // Add focus styles for accessibility
    $('.style-btn').on('focus', function() {
        $(this).css('outline', '2px solid rgba(255, 255, 255, 0.5)');
    }).on('blur', function() {
        $(this).css('outline', 'none');
    });
});

// Add CSS animation for ripple effect
$('<style>')
    .prop('type', 'text/css')
    .html(`
        @keyframes ripple {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
        
        .ripple {
            pointer-events: none;
        }
    `)
    .appendTo('head');