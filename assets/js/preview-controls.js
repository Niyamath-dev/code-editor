/**
 * HCJ Code Editor - Enhanced Preview Controls
 * Advanced preview functionality with comprehensive device simulation
 */

const PreviewControls = {
    // Current device mode and settings
    currentDevice: 'desktop',
    currentOrientation: 'portrait',
    currentZoom: 1,
    isCustomViewport: false,
    
    // Comprehensive device configurations
    devices: {
        // Desktop configurations
        desktop: {
            width: '100%',
            height: '100%',
            viewport: '1920 x 1080',
            class: 'desktop',
            category: 'desktop',
            name: 'Desktop',
            icon: 'bi-display'
        },
        
        // Mobile devices
        'iphone-se': {
            width: '375px',
            height: '667px',
            viewport: '375 x 667',
            class: 'mobile',
            category: 'mobile',
            name: 'iPhone SE',
            icon: 'bi-phone',
            landscape: { width: '667px', height: '375px', viewport: '667 x 375' }
        },
        'iphone-12': {
            width: '390px',
            height: '844px',
            viewport: '390 x 844',
            class: 'mobile',
            category: 'mobile',
            name: 'iPhone 12/13/14',
            icon: 'bi-phone',
            landscape: { width: '844px', height: '390px', viewport: '844 x 390' }
        },
        'iphone-12-pro-max': {
            width: '428px',
            height: '926px',
            viewport: '428 x 926',
            class: 'mobile',
            category: 'mobile',
            name: 'iPhone 12/13/14 Pro Max',
            icon: 'bi-phone',
            landscape: { width: '926px', height: '428px', viewport: '926 x 428' }
        },
        'samsung-galaxy-s21': {
            width: '384px',
            height: '854px',
            viewport: '384 x 854',
            class: 'mobile',
            category: 'mobile',
            name: 'Samsung Galaxy S21',
            icon: 'bi-phone',
            landscape: { width: '854px', height: '384px', viewport: '854 x 384' }
        },
        'pixel-5': {
            width: '393px',
            height: '851px',
            viewport: '393 x 851',
            class: 'mobile',
            category: 'mobile',
            name: 'Google Pixel 5',
            icon: 'bi-phone',
            landscape: { width: '851px', height: '393px', viewport: '851 x 393' }
        },
        
        // Tablet devices
        'ipad': {
            width: '768px',
            height: '1024px',
            viewport: '768 x 1024',
            class: 'tablet',
            category: 'tablet',
            name: 'iPad',
            icon: 'bi-tablet',
            landscape: { width: '1024px', height: '768px', viewport: '1024 x 768' }
        },
        'ipad-air': {
            width: '820px',
            height: '1180px',
            viewport: '820 x 1180',
            class: 'tablet',
            category: 'tablet',
            name: 'iPad Air',
            icon: 'bi-tablet',
            landscape: { width: '1180px', height: '820px', viewport: '1180 x 820' }
        },
        'ipad-pro': {
            width: '1024px',
            height: '1366px',
            viewport: '1024 x 1366',
            class: 'tablet',
            category: 'tablet',
            name: 'iPad Pro 12.9"',
            icon: 'bi-tablet',
            landscape: { width: '1366px', height: '1024px', viewport: '1366 x 1024' }
        },
        'surface-pro': {
            width: '912px',
            height: '1368px',
            viewport: '912 x 1368',
            class: 'tablet',
            category: 'tablet',
            name: 'Surface Pro 7',
            icon: 'bi-tablet',
            landscape: { width: '1368px', height: '912px', viewport: '1368 x 912' }
        },
        
        // Laptop/Desktop variations
        'laptop': {
            width: '1366px',
            height: '768px',
            viewport: '1366 x 768',
            class: 'laptop',
            category: 'desktop',
            name: 'Laptop',
            icon: 'bi-laptop'
        },
        'desktop-hd': {
            width: '1920px',
            height: '1080px',
            viewport: '1920 x 1080',
            class: 'desktop',
            category: 'desktop',
            name: 'Desktop HD',
            icon: 'bi-display'
        },
        'desktop-4k': {
            width: '3840px',
            height: '2160px',
            viewport: '3840 x 2160',
            class: 'desktop',
            category: 'desktop',
            name: 'Desktop 4K',
            icon: 'bi-display'
        }
    },
    
    // Responsive breakpoints
    breakpoints: {
        xs: { min: 0, max: 575, name: 'Extra Small', color: '#dc3545' },
        sm: { min: 576, max: 767, name: 'Small', color: '#fd7e14' },
        md: { min: 768, max: 991, name: 'Medium', color: '#ffc107' },
        lg: { min: 992, max: 1199, name: 'Large', color: '#198754' },
        xl: { min: 1200, max: 1399, name: 'Extra Large', color: '#0d6efd' },
        xxl: { min: 1400, max: Infinity, name: '2X Large', color: '#6f42c1' }
    },
    
    // Initialize preview controls
    init() {
        this.bindEvents();
        this.createEnhancedControls();
        this.setDevice('desktop');
        this.updateBreakpointIndicator();
        console.log('Enhanced preview controls initialized');
    },
    
    // Bind event handlers
    bindEvents() {
        // Device selector buttons
        const deviceButtons = document.querySelectorAll('.device-btn');
        deviceButtons.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const device = btn.getAttribute('data-device');
                this.setDevice(device, btn);
            });
        });
        
        // Refresh button
        const refreshBtn = document.querySelector('.refresh-btn');
        if (refreshBtn) {
            refreshBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.refreshPreview();
            });
        }
        
        // Orientation toggle button
        const orientationBtn = document.querySelector('.orientation-btn');
        if (orientationBtn) {
            orientationBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.toggleOrientation();
            });
        }
        
        // Zoom controls
        const zoomInBtn = document.querySelector('.zoom-in-btn');
        const zoomOutBtn = document.querySelector('.zoom-out-btn');
        const zoomResetBtn = document.querySelector('.zoom-reset-btn');
        
        if (zoomInBtn) {
            zoomInBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.zoomIn();
            });
        }
        
        if (zoomOutBtn) {
            zoomOutBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.zoomOut();
            });
        }
        
        if (zoomResetBtn) {
            zoomResetBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.resetZoom();
            });
        }
        
        // Custom viewport inputs
        const customWidthInput = document.querySelector('.custom-width-input');
        const customHeightInput = document.querySelector('.custom-height-input');
        const applyCustomBtn = document.querySelector('.apply-custom-btn');
        
        if (applyCustomBtn && customWidthInput && customHeightInput) {
            applyCustomBtn.addEventListener('click', (e) => {
                e.preventDefault();
                const width = parseInt(customWidthInput.value);
                const height = parseInt(customHeightInput.value);
                if (width && height) {
                    this.setCustomViewport(width, height);
                }
            });
        }
        
        // Device dropdown
        const deviceDropdown = document.querySelector('.device-dropdown');
        if (deviceDropdown) {
            deviceDropdown.addEventListener('change', (e) => {
                this.setDevice(e.target.value);
            });
        }
        
        // Window resize handler
        window.addEventListener('resize', () => {
            this.updateViewportSize();
            this.updateBreakpointIndicator();
        });
        
        // Preview frame load handler
        const previewFrame = document.getElementById('codeMobile');
        if (previewFrame) {
            previewFrame.addEventListener('load', () => {
                this.onPreviewLoad();
            });
        }
    },
    
    // Create enhanced control elements
    createEnhancedControls() {
        // This method will be called to create additional UI elements
        // if they don't exist in the HTML
        this.createBreakpointIndicator();
    },
    
    // Create breakpoint indicator
    createBreakpointIndicator() {
        const existingIndicator = document.querySelector('.breakpoint-indicator');
        if (existingIndicator) return;
        
        const previewInfo = document.querySelector('.preview-info');
        if (!previewInfo) return;
        
        const indicator = document.createElement('div');
        indicator.className = 'breakpoint-indicator';
        indicator.innerHTML = '<span class="breakpoint-name">Large</span>';
        
        previewInfo.appendChild(indicator);
    },
    
    // Set device mode
    setDevice(device, buttonElement = null) {
        if (!this.devices[device]) {
            console.error('Unknown device:', device);
            return;
        }
        
        this.currentDevice = device;
        const config = this.devices[device];
        const previewFrame = document.getElementById('codeMobile');
        const viewportSize = document.querySelector('.viewport-size');
        
        if (!previewFrame || !viewportSize) return;
        
        // Update preview frame classes
        Object.keys(this.devices).forEach(d => {
            previewFrame.classList.remove(this.devices[d].class);
        });
        previewFrame.classList.add(config.class);
        
        // Update viewport size display
        if (device === 'desktop') {
            viewportSize.textContent = `${window.innerWidth} x ${window.innerHeight}`;
        } else {
            viewportSize.textContent = config.viewport;
        }
        
        // Update active button
        this.updateActiveButton(buttonElement || document.querySelector(`[data-device="${device}"]`));
        
        // Apply enhanced device-specific styles
        this.applyEnhancedDeviceStyles(device, previewFrame);
        
        // Update breakpoint indicator
        this.updateBreakpointIndicator();
        
        // Show notification
        HCJEditor.showNotification(`Switched to ${device} view`, 'info');
        
        console.log(`Preview device set to: ${device}`);
    },
    
    // Apply device-specific styles
    applyDeviceStyles(device, previewFrame) {
        const config = this.devices[device];
        
        // Reset styles
        previewFrame.style.width = '';
        previewFrame.style.height = '';
        previewFrame.style.maxWidth = '';
        previewFrame.style.margin = '';
        previewFrame.style.borderRadius = '';
        previewFrame.style.boxShadow = '';
        
        // Apply device-specific styles
        switch (device) {
            case 'mobile':
                previewFrame.style.maxWidth = config.width;
                previewFrame.style.margin = '0 auto';
                previewFrame.style.borderRadius = '20px';
                previewFrame.style.boxShadow = '0 8px 32px rgba(0, 0, 0, 0.3)';
                break;
                
            case 'tablet':
                previewFrame.style.maxWidth = config.width;
                previewFrame.style.margin = '0 auto';
                previewFrame.style.borderRadius = '12px';
                previewFrame.style.boxShadow = '0 6px 24px rgba(0, 0, 0, 0.2)';
                break;
                
            case 'desktop':
                previewFrame.style.width = '100%';
                previewFrame.style.borderRadius = '0';
                break;
        }
        
        // Add transition for smooth changes
        previewFrame.style.transition = 'all 0.3s ease';
    },
    
    // Update active button state
    updateActiveButton(activeButton) {
        if (!activeButton) return;
        
        // Remove active class from all buttons
        const allButtons = document.querySelectorAll('.device-btn');
        allButtons.forEach(btn => btn.classList.remove('active'));
        
        // Add active class to clicked button
        activeButton.classList.add('active');
    },
    
    // Refresh preview
    refreshPreview() {
        const previewFrame = document.getElementById('codeMobile');
        const loadingSpinner = document.querySelector('.preview-loading');
        
        if (!previewFrame) return;
        
        // Show loading spinner
        this.showLoading();
        
        // Force iframe refresh
        previewFrame.src = 'about:blank';
        
        setTimeout(() => {
            this.updatePreview();
            this.hideLoading();
            HCJEditor.showNotification('Preview refreshed', 'success');
        }, 300);
    },
    
    // Update preview content
    updatePreview() {
        const previewFrame = document.getElementById('codeMobile');
        if (!previewFrame) return;
        
        const htmlEditor = document.getElementById('htmlMobile');
        const cssEditor = document.getElementById('cssMobile');
        const jsEditor = document.getElementById('jsMobile');
        
        const html = htmlEditor ? htmlEditor.value : '';
        const css = cssEditor ? cssEditor.value : '';
        const js = jsEditor ? jsEditor.value : '';
        
        // Create preview content with error handling
        const previewContent = this.generatePreviewContent(html, css, js);
        
        try {
            const previewDoc = previewFrame.contentDocument || previewFrame.contentWindow.document;
            previewDoc.open();
            previewDoc.write(previewContent);
            previewDoc.close();
            
            // Add error handling to the preview frame
            this.addErrorHandling(previewFrame);
            
        } catch (error) {
            console.error('Preview update error:', error);
            this.showPreviewError('Failed to update preview');
        }
    },
    
    // Generate preview content with enhanced features
    generatePreviewContent(html, css, js) {
        return `<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Preview</title>
    <style>
        /* Reset and base styles for preview */
        * {
            box-sizing: border-box;
        }
        
        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        
        /* User CSS */
        ${css}
        
        /* Error display styles */
        .preview-error {
            background: #fee;
            border: 1px solid #fcc;
            color: #c33;
            padding: 10px;
            margin: 10px;
            border-radius: 4px;
            font-family: monospace;
            font-size: 12px;
        }
    </style>
</head>
<body>
    ${html}
    
    <script>
        // Error handling for preview
        window.addEventListener('error', function(e) {
            console.error('Preview Error:', e.error);
            const errorDiv = document.createElement('div');
            errorDiv.className = 'preview-error';
            errorDiv.innerHTML = '<strong>JavaScript Error:</strong><br>' + e.error.message + '<br><small>Line: ' + e.lineno + '</small>';
            document.body.insertBefore(errorDiv, document.body.firstChild);
        });
        
        // User JavaScript
        try {
            ${js}
        } catch (error) {
            console.error('User JavaScript Error:', error);
            const errorDiv = document.createElement('div');
            errorDiv.className = 'preview-error';
            errorDiv.innerHTML = '<strong>JavaScript Error:</strong><br>' + error.message;
            document.body.insertBefore(errorDiv, document.body.firstChild);
        }
        
        // Notify parent frame that preview is loaded
        try {
            if (window.parent && window.parent.PreviewControls) {
                window.parent.PreviewControls.onPreviewContentLoad();
            }
        } catch (e) {
            // Ignore cross-origin errors
        }
    </script>
</body>
</html>`;
    },
    
    // Add error handling to preview frame
    addErrorHandling(previewFrame) {
        try {
            const previewWindow = previewFrame.contentWindow;
            if (previewWindow) {
                previewWindow.addEventListener('error', (e) => {
                    console.error('Preview frame error:', e.error);
                });
            }
        } catch (error) {
            // Ignore cross-origin errors
        }
    },
    
    // Show loading state
    showLoading() {
        const loadingSpinner = document.querySelector('.preview-loading');
        if (loadingSpinner) {
            loadingSpinner.classList.add('show');
        }
    },
    
    // Hide loading state
    hideLoading() {
        const loadingSpinner = document.querySelector('.preview-loading');
        if (loadingSpinner) {
            loadingSpinner.classList.remove('show');
        }
    },
    
    // Handle preview frame load
    onPreviewLoad() {
        this.hideLoading();
        console.log('Preview frame loaded');
    },
    
    // Handle preview content load (called from within iframe)
    onPreviewContentLoad() {
        console.log('Preview content loaded successfully');
    },
    
    // Show preview error
    showPreviewError(message) {
        const previewContent = document.querySelector('.preview-content');
        if (!previewContent) return;
        
        const errorDiv = document.createElement('div');
        errorDiv.className = 'alert alert-danger preview-error-message';
        errorDiv.innerHTML = `
            <i class="bi bi-exclamation-triangle"></i>
            <strong>Preview Error:</strong> ${message}
            <button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>
        `;
        
        // Remove existing error messages
        const existingErrors = previewContent.querySelectorAll('.preview-error-message');
        existingErrors.forEach(error => error.remove());
        
        previewContent.insertBefore(errorDiv, previewContent.firstChild);
    },
    
    // Update viewport size display
    updateViewportSize() {
        if (this.currentDevice === 'desktop') {
            const viewportSize = document.querySelector('.viewport-size');
            if (viewportSize) {
                viewportSize.textContent = `${window.innerWidth} x ${window.innerHeight}`;
            }
        }
    },
    
    // Take screenshot of preview (future enhancement)
    takeScreenshot() {
        // Implementation for taking preview screenshots
        HCJEditor.showNotification('Screenshot functionality coming soon!', 'info');
    },
    
    // Toggle fullscreen preview
    toggleFullscreen() {
        const previewContainer = document.querySelector('.preview-container');
        if (!previewContainer) return;
        
        if (document.fullscreenElement) {
            document.exitFullscreen();
        } else {
            previewContainer.requestFullscreen().catch(err => {
                console.error('Fullscreen error:', err);
                HCJEditor.showNotification('Fullscreen not supported', 'warning');
            });
        }
    },
    
    // Open preview in new window
    openInNewWindow() {
        const previewFrame = document.getElementById('codeMobile');
        if (!previewFrame) return;
        
        const htmlEditor = document.getElementById('htmlMobile');
        const cssEditor = document.getElementById('cssMobile');
        const jsEditor = document.getElementById('jsMobile');
        
        const html = htmlEditor ? htmlEditor.value : '';
        const css = cssEditor ? cssEditor.value : '';
        const js = jsEditor ? jsEditor.value : '';
        
        const previewContent = this.generatePreviewContent(html, css, js);
        
        const newWindow = window.open('', '_blank', 'width=1200,height=800');
        if (newWindow) {
            newWindow.document.write(previewContent);
            newWindow.document.close();
            HCJEditor.showNotification('Preview opened in new window', 'success');
        } else {
            HCJEditor.showNotification('Popup blocked. Please allow popups for this site.', 'warning');
        }
    },
    
    // Get current device info
    getCurrentDevice() {
        return {
            name: this.currentDevice,
            config: this.devices[this.currentDevice]
        };
    },
    
    // Set custom viewport size
    setCustomViewport(width, height) {
        const previewFrame = document.getElementById('codeMobile');
        const viewportSize = document.querySelector('.viewport-size');
        
        if (previewFrame && viewportSize) {
            previewFrame.style.width = width + 'px';
            previewFrame.style.height = height + 'px';
            previewFrame.style.maxWidth = 'none';
            previewFrame.style.margin = '0 auto';
            previewFrame.style.transform = `scale(${this.currentZoom})`;
            
            viewportSize.textContent = `${width} x ${height}`;
            this.currentDevice = 'custom';
            this.isCustomViewport = true;
            
            // Remove active state from device buttons
            const deviceButtons = document.querySelectorAll('.device-btn');
            deviceButtons.forEach(btn => btn.classList.remove('active'));
            
            // Update breakpoint indicator
            this.updateBreakpointIndicator(width);
            
            HCJEditor.showNotification(`Custom viewport: ${width}x${height}`, 'info');
        }
    },
    
    // Toggle orientation (portrait/landscape)
    toggleOrientation() {
        const device = this.devices[this.currentDevice];
        if (!device || !device.landscape || this.currentDevice === 'desktop') {
            HCJEditor.showNotification('Orientation toggle not available for this device', 'warning');
            return;
        }
        
        const previewFrame = document.getElementById('codeMobile');
        const viewportSize = document.querySelector('.viewport-size');
        const orientationBtn = document.querySelector('.orientation-btn');
        
        if (!previewFrame || !viewportSize) return;
        
        // Toggle orientation
        this.currentOrientation = this.currentOrientation === 'portrait' ? 'landscape' : 'portrait';
        
        let config;
        if (this.currentOrientation === 'landscape') {
            config = device.landscape;
        } else {
            config = device;
        }
        
        // Apply new dimensions
        previewFrame.style.width = config.width;
        previewFrame.style.height = config.height;
        previewFrame.style.maxWidth = config.width;
        previewFrame.style.transform = `scale(${this.currentZoom})`;
        
        // Update viewport display
        viewportSize.textContent = config.viewport;
        
        // Update orientation button icon
        if (orientationBtn) {
            const icon = orientationBtn.querySelector('i');
            if (icon) {
                icon.className = this.currentOrientation === 'landscape' ? 'bi bi-phone-landscape' : 'bi bi-phone';
            }
        }
        
        // Update breakpoint indicator
        const width = parseInt(config.width);
        this.updateBreakpointIndicator(width);
        
        HCJEditor.showNotification(`Switched to ${this.currentOrientation} orientation`, 'info');
    },
    
    // Zoom in
    zoomIn() {
        if (this.currentZoom >= 2) {
            HCJEditor.showNotification('Maximum zoom level reached', 'warning');
            return;
        }
        
        this.currentZoom = Math.min(2, this.currentZoom + 0.25);
        this.applyZoom();
        HCJEditor.showNotification(`Zoom: ${Math.round(this.currentZoom * 100)}%`, 'info');
    },
    
    // Zoom out
    zoomOut() {
        if (this.currentZoom <= 0.25) {
            HCJEditor.showNotification('Minimum zoom level reached', 'warning');
            return;
        }
        
        this.currentZoom = Math.max(0.25, this.currentZoom - 0.25);
        this.applyZoom();
        HCJEditor.showNotification(`Zoom: ${Math.round(this.currentZoom * 100)}%`, 'info');
    },
    
    // Reset zoom
    resetZoom() {
        this.currentZoom = 1;
        this.applyZoom();
        HCJEditor.showNotification('Zoom reset to 100%', 'info');
    },
    
    // Apply zoom to preview frame
    applyZoom() {
        const previewFrame = document.getElementById('codeMobile');
        const previewContent = document.querySelector('.preview-content');
        
        if (!previewFrame || !previewContent) return;
        
        // Apply zoom transform
        previewFrame.style.transform = `scale(${this.currentZoom})`;
        previewFrame.style.transformOrigin = 'top center';
        
        // Adjust container height to accommodate zoom
        if (this.currentZoom !== 1) {
            const originalHeight = previewFrame.offsetHeight;
            const scaledHeight = originalHeight * this.currentZoom;
            previewContent.style.minHeight = scaledHeight + 'px';
        } else {
            previewContent.style.minHeight = '';
        }
        
        // Update zoom display
        const zoomDisplay = document.querySelector('.zoom-display');
        if (zoomDisplay) {
            zoomDisplay.textContent = `${Math.round(this.currentZoom * 100)}%`;
        }
    },
    
    // Update breakpoint indicator
    updateBreakpointIndicator(customWidth = null) {
        const indicator = document.querySelector('.breakpoint-indicator');
        if (!indicator) return;
        
        let width;
        if (customWidth) {
            width = customWidth;
        } else if (this.currentDevice === 'desktop') {
            width = window.innerWidth;
        } else {
            const config = this.devices[this.currentDevice];
            if (this.currentOrientation === 'landscape' && config.landscape) {
                width = parseInt(config.landscape.width);
            } else {
                width = parseInt(config.width);
            }
        }
        
        // Find matching breakpoint
        let currentBreakpoint = null;
        for (const [key, breakpoint] of Object.entries(this.breakpoints)) {
            if (width >= breakpoint.min && width <= breakpoint.max) {
                currentBreakpoint = { key, ...breakpoint };
                break;
            }
        }
        
        if (currentBreakpoint) {
            const nameSpan = indicator.querySelector('.breakpoint-name');
            if (nameSpan) {
                nameSpan.textContent = currentBreakpoint.name;
                nameSpan.style.color = currentBreakpoint.color;
            }
        }
    },
    
    // Enhanced device styling with orientation and zoom support
    applyEnhancedDeviceStyles(device, previewFrame) {
        const config = this.devices[device];
        let activeConfig = config;
        
        // Use landscape config if in landscape mode
        if (this.currentOrientation === 'landscape' && config.landscape) {
            activeConfig = { ...config, ...config.landscape };
        }
        
        // Reset styles
        previewFrame.style.width = '';
        previewFrame.style.height = '';
        previewFrame.style.maxWidth = '';
        previewFrame.style.margin = '';
        previewFrame.style.borderRadius = '';
        previewFrame.style.boxShadow = '';
        previewFrame.style.transform = '';
        previewFrame.style.transformOrigin = '';
        
        // Apply device-specific styles with enhanced features
        if (config.category === 'mobile') {
            previewFrame.style.width = activeConfig.width;
            previewFrame.style.height = activeConfig.height;
            previewFrame.style.maxWidth = activeConfig.width;
            previewFrame.style.margin = '0 auto';
            previewFrame.style.borderRadius = '20px';
            previewFrame.style.boxShadow = '0 8px 32px rgba(0, 0, 0, 0.3)';
            previewFrame.style.border = '8px solid #333';
            previewFrame.style.background = '#333';
        } else if (config.category === 'tablet') {
            previewFrame.style.width = activeConfig.width;
            previewFrame.style.height = activeConfig.height;
            previewFrame.style.maxWidth = activeConfig.width;
            previewFrame.style.margin = '0 auto';
            previewFrame.style.borderRadius = '12px';
            previewFrame.style.boxShadow = '0 6px 24px rgba(0, 0, 0, 0.2)';
            previewFrame.style.border = '4px solid #666';
        } else {
            previewFrame.style.width = '100%';
            previewFrame.style.height = '100%';
            previewFrame.style.borderRadius = '0';
        }
        
        // Apply zoom
        previewFrame.style.transform = `scale(${this.currentZoom})`;
        previewFrame.style.transformOrigin = 'top center';
        
        // Add transition for smooth changes
        previewFrame.style.transition = 'all 0.3s ease';
    },
    
    // Get responsive design insights
    getResponsiveInsights() {
        const insights = [];
        const currentWidth = this.getCurrentViewportWidth();
        
        // Check breakpoint transitions
        const breakpointKeys = Object.keys(this.breakpoints);
        for (let i = 0; i < breakpointKeys.length - 1; i++) {
            const current = this.breakpoints[breakpointKeys[i]];
            const next = this.breakpoints[breakpointKeys[i + 1]];
            
            if (Math.abs(currentWidth - current.max) <= 50) {
                insights.push({
                    type: 'breakpoint-transition',
                    message: `Close to ${current.name}/${next.name} breakpoint transition`,
                    suggestion: `Test at ${current.max}px and ${next.min}px`
                });
            }
        }
        
        // Check common device widths
        const commonWidths = [320, 375, 414, 768, 1024, 1366, 1920];
        const closest = commonWidths.reduce((prev, curr) => 
            Math.abs(curr - currentWidth) < Math.abs(prev - currentWidth) ? curr : prev
        );
        
        if (Math.abs(currentWidth - closest) <= 20) {
            insights.push({
                type: 'common-width',
                message: `Close to common device width: ${closest}px`,
                suggestion: 'Consider testing exact width for optimal experience'
            });
        }
        
        return insights;
    },
    
    // Get current viewport width
    getCurrentViewportWidth() {
        if (this.currentDevice === 'desktop') {
            return window.innerWidth;
        } else if (this.isCustomViewport) {
            const previewFrame = document.getElementById('codeMobile');
            return previewFrame ? parseInt(previewFrame.style.width) : 0;
        } else {
            const config = this.devices[this.currentDevice];
            if (this.currentOrientation === 'landscape' && config.landscape) {
                return parseInt(config.landscape.width);
            }
            return parseInt(config.width);
        }
    },
    
    // Show responsive insights
    showResponsiveInsights() {
        const insights = this.getResponsiveInsights();
        if (insights.length === 0) {
            HCJEditor.showNotification('No responsive insights available', 'info');
            return;
        }
        
        const insightMessages = insights.map(insight => 
            `${insight.message}\n${insight.suggestion}`
        ).join('\n\n');
        
        alert(`Responsive Design Insights:\n\n${insightMessages}`);
    }
};

// Enhanced compile function for backward compatibility
function compile() {
    PreviewControls.updatePreview();
}

// Initialize preview controls when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    PreviewControls.init();
    
    // Initial preview update
    setTimeout(() => {
        PreviewControls.updatePreview();
    }, 100);
});

// Export for global access
window.PreviewControls = PreviewControls;
