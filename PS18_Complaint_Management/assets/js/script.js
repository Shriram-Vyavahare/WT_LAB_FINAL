// Custom JavaScript for Complaint Management System

document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });

    // Organization change handler for categories
    const orgSelect = document.getElementById('organization_id');
    const categorySelect = document.getElementById('category_id');
    
    if (orgSelect && categorySelect) {
        orgSelect.addEventListener('change', function() {
            const orgId = this.value;
            
            // Clear existing options
            categorySelect.innerHTML = '<option value="">Select Category</option>';
            
            if (orgId) {
                // Show loading
                categorySelect.innerHTML = '<option value="">Loading...</option>';
                
                // Fetch categories
                fetch(`ajax/get_categories.php?org_id=${orgId}`)
                    .then(response => response.json())
                    .then(data => {
                        categorySelect.innerHTML = '<option value="">Select Category</option>';
                        data.forEach(category => {
                            const option = document.createElement('option');
                            option.value = category.id;
                            option.textContent = category.name;
                            categorySelect.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching categories:', error);
                        categorySelect.innerHTML = '<option value="">Error loading categories</option>';
                    });
            }
        });
    }

    // Form validation
    const forms = document.querySelectorAll('.needs-validation');
    forms.forEach(function(form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });

    // File upload preview
    const fileInput = document.getElementById('attachment');
    const filePreview = document.getElementById('file-preview');
    
    if (fileInput && filePreview) {
        fileInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const fileName = file.name;
                const fileSize = (file.size / 1024 / 1024).toFixed(2);
                
                filePreview.innerHTML = `
                    <div class="alert alert-info">
                        <i class="fas fa-file me-2"></i>
                        <strong>${fileName}</strong> (${fileSize} MB)
                    </div>
                `;
            } else {
                filePreview.innerHTML = '';
            }
        });
    }

    // Search functionality
    const searchInput = document.getElementById('search');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                performSearch(this.value);
            }, 500);
        });
    }

    // Status update handler
    const statusSelects = document.querySelectorAll('.status-update');
    statusSelects.forEach(function(select) {
        select.addEventListener('change', function() {
            const complaintId = this.dataset.complaintId;
            const newStatus = this.value;
            
            if (confirm('Are you sure you want to update the status?')) {
                updateComplaintStatus(complaintId, newStatus);
            } else {
                // Reset to original value
                this.value = this.dataset.originalValue;
            }
        });
    });

    // Priority update handler
    const prioritySelects = document.querySelectorAll('.priority-update');
    prioritySelects.forEach(function(select) {
        select.addEventListener('change', function() {
            const complaintId = this.dataset.complaintId;
            const newPriority = this.value;
            
            updateComplaintPriority(complaintId, newPriority);
        });
    });
});

// Search function
function performSearch(query) {
    const resultsContainer = document.getElementById('search-results');
    if (!resultsContainer) return;
    
    if (query.length < 2) {
        resultsContainer.innerHTML = '';
        return;
    }
    
    fetch(`ajax/search.php?q=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {
            displaySearchResults(data);
        })
        .catch(error => {
            console.error('Search error:', error);
        });
}

// Display search results
function displaySearchResults(results) {
    const container = document.getElementById('search-results');
    if (!container) return;
    
    if (results.length === 0) {
        container.innerHTML = '<div class="alert alert-info">No results found.</div>';
        return;
    }
    
    let html = '<div class="list-group">';
    results.forEach(result => {
        html += `
            <a href="view_complaint.php?id=${result.id}" class="list-group-item list-group-item-action">
                <div class="d-flex w-100 justify-content-between">
                    <h6 class="mb-1">${result.complaint_number}</h6>
                    <small class="text-muted">${result.created_at}</small>
                </div>
                <p class="mb-1">${result.subject}</p>
                <small class="text-muted">${result.organization_name}</small>
            </a>
        `;
    });
    html += '</div>';
    
    container.innerHTML = html;
}

// Update complaint status
function updateComplaintStatus(complaintId, status) {
    const formData = new FormData();
    formData.append('complaint_id', complaintId);
    formData.append('status', status);
    
    fetch('ajax/update_status.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('Status updated successfully', 'success');
            // Refresh the page or update the UI
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            showAlert('Error updating status: ' + data.message, 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Error updating status', 'danger');
    });
}

// Update complaint priority
function updateComplaintPriority(complaintId, priority) {
    const formData = new FormData();
    formData.append('complaint_id', complaintId);
    formData.append('priority', priority);
    
    fetch('ajax/update_priority.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('Priority updated successfully', 'success');
        } else {
            showAlert('Error updating priority: ' + data.message, 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Error updating priority', 'danger');
    });
}

// Show alert message
function showAlert(message, type = 'info') {
    const alertContainer = document.getElementById('alert-container') || document.body;
    
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    alertContainer.insertBefore(alertDiv, alertContainer.firstChild);
    
    // Auto-hide after 5 seconds
    setTimeout(() => {
        const bsAlert = new bootstrap.Alert(alertDiv);
        bsAlert.close();
    }, 5000);
}

// Confirm delete
function confirmDelete(message = 'Are you sure you want to delete this item?') {
    return confirm(message);
}

// Print function
function printPage() {
    window.print();
}

// Export function (placeholder)
function exportData(format) {
    alert(`Export to ${format} functionality would be implemented here.`);
}