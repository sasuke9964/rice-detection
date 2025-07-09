/**
 * Rice Quality Analyzer - Main JavaScript
 */

document.addEventListener('DOMContentLoaded', function() {
    // Handle file upload preview
    const fileInput = document.getElementById('rice-image');
    const previewArea = document.getElementById('image-preview');
    const uploadArea = document.querySelector('.upload-area');
    
    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            if (e.target.files.length > 0) {
                const file = e.target.files[0];
                
                // Only process image files
                if (!file.type.match('image.*')) {
                    showAlert('Please select an image file (JPEG, PNG, etc.)', 'danger');
                    return;
                }
                
                // File size validation (max 5MB)
                if (file.size > 5 * 1024 * 1024) {
                    showAlert('Image size should not exceed 5MB', 'danger');
                    return;
                }
                
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    // Clear previous preview
                    if (previewArea) {
                        previewArea.innerHTML = '';
                        
                        // Create image element
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.classList.add('img-fluid', 'rounded', 'shadow-sm');
                        img.style.maxHeight = '300px';
                        
                        // Add image to preview area
                        previewArea.appendChild(img);
                        
                        // Show submit button
                        const submitBtn = document.getElementById('analyze-btn');
                        if (submitBtn) {
                            submitBtn.disabled = false;
                        }
                    }
                };
                
                reader.readAsDataURL(file);
            }
        });
    }
    
    // Handle drag and drop file upload
    if (uploadArea) {
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, preventDefaults, false);
        });
        
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        ['dragenter', 'dragover'].forEach(eventName => {
            uploadArea.addEventListener(eventName, highlight, false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, unhighlight, false);
        });
        
        function highlight() {
            uploadArea.classList.add('bg-light');
        }
        
        function unhighlight() {
            uploadArea.classList.remove('bg-light');
        }
        
        uploadArea.addEventListener('drop', handleDrop, false);
        
        function handleDrop(e) {
            if (fileInput) {
                fileInput.files = e.dataTransfer.files;
                
                // Trigger change event
                const event = new Event('change', { bubbles: true });
                fileInput.dispatchEvent(event);
            }
        }
    }
    
    // Analysis form submission
    const analysisForm = document.getElementById('analysis-form');
    if (analysisForm) {
        analysisForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Show loading spinner
            const submitBtn = document.getElementById('analyze-btn');
            if (submitBtn) {
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Analyzing...';
                submitBtn.disabled = true;
            }
            
            // Simulate analysis delay (remove in production)
            setTimeout(function() {
                // Submit the form
                analysisForm.submit();
            }, 2000);
        });
    }
    
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Initialize popovers
    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
    
    // Helper function to show alerts
    window.showAlert = function(message, type = 'info') {
        const alertsContainer = document.getElementById('alerts-container');
        if (alertsContainer) {
            const alert = document.createElement('div');
            alert.className = `alert alert-${type} alert-dismissible fade show`;
            alert.role = 'alert';
            
            alert.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;
            
            alertsContainer.appendChild(alert);
            
            // Auto-dismiss after 5 seconds
            setTimeout(() => {
                alert.classList.remove('show');
                setTimeout(() => {
                    alertsContainer.removeChild(alert);
                }, 150);
            }, 5000);
        }
    };
    
    // Add chart initialization for results page
    const resultCharts = document.querySelectorAll('.result-chart');
    if (resultCharts.length > 0 && typeof Chart !== 'undefined') {
        resultCharts.forEach(canvas => {
            // Get data from data attributes
            const normal = parseFloat(canvas.dataset.normal || 0);
            const broken = parseFloat(canvas.dataset.broken || 0);
            const blackSpotted = parseFloat(canvas.dataset.blackSpotted || 0);
            
            const ctx = canvas.getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Normal Rice', 'Broken Rice', 'Black Spotted Rice'],
                    datasets: [{
                        data: [normal, broken, blackSpotted],
                        backgroundColor: [
                            '#2ecc71',
                            '#f39c12',
                            '#e74c3c'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        });
    }
}); 