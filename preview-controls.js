// Preview control functions
function refreshPreview() {
    const previewFrame = document.getElementById('codeMobile');
    const loadingSpinner = document.querySelector('.preview-loading');
    
    loadingSpinner.classList.add('show');
    
    // Force iframe refresh
    previewFrame.src = 'about:blank';
    setTimeout(() => {
        compile(); // Re-run the existing compile function
        loadingSpinner.classList.remove('show');
    }, 300);
}

function setDevice(device, buttonElement) {
    const previewFrame = document.getElementById('codeMobile');
    const buttons = document.querySelectorAll('.device-selector .preview-btn');
    const viewportSize = document.querySelector('.viewport-size');
    
    if (!previewFrame || !viewportSize) return;
    
    // Remove all device classes and active states
    previewFrame.classList.remove('mobile', 'tablet', 'desktop');
    buttons.forEach(btn => btn.classList.remove('active'));
    
    // Add the selected device class
    previewFrame.classList.add(device);
    
    // Set active state on clicked button
    if (buttonElement) {
        buttonElement.classList.add('active');
    }
    
    // Update viewport size display
    const sizes = {
        mobile: '375 x 667',
        tablet: '768 x 1024',
        desktop: '1920 x 1080'
    };
    viewportSize.textContent = sizes[device];
}

// Update viewport size on window resize
window.addEventListener('resize', () => {
    const viewportSize = document.querySelector('.viewport-size');
    const previewFrame = document.getElementById('codeMobile');
    
    // Only update if in desktop mode
    if (previewFrame.classList.contains('desktop')) {
        viewportSize.textContent = `${window.innerWidth} x ${window.innerHeight}`;
    }
});

// Initialize preview
document.addEventListener('DOMContentLoaded', () => {
    // Set initial device mode to desktop
    const desktopBtn = document.querySelector('.device-btn[data-device="desktop"]');
    if (desktopBtn) {
        setDevice('desktop', desktopBtn);
    }
    
    // Add event listeners to device buttons
    const deviceButtons = document.querySelectorAll('.device-btn');
    deviceButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const device = this.getAttribute('data-device');
            setDevice(device, this);
        });
    });
    
    // Add event listener to refresh button
    const refreshBtn = document.querySelector('.refresh-btn');
    if (refreshBtn) {
        refreshBtn.addEventListener('click', function(e) {
            e.preventDefault();
            refreshPreview();
        });
    }
});
