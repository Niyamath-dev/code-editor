/**
 * HCJ Code Editor - Main Application JavaScript
 * Professional JavaScript Architecture
 */

// Global App Configuration
const HCJEditor = {
    config: {
        autoSave: true,
        autoSaveInterval: 30000, // 30 seconds
        theme: 'dark',
        fontSize: 14,
        tabSize: 2,
        wordWrap: true
    },
    
    // Application state
    state: {
        currentTab: 'html',
        isPreviewVisible: true,
        deviceMode: 'desktop',
        lastSaved: null,
        hasUnsavedChanges: false
    },
    
    // DOM elements cache
    elements: {},
    
    // Event handlers
    handlers: {},
    
    // Initialize the application
    init() {
        this.cacheElements();
        this.bindEvents();
        this.initializeEditor();
        this.setupAutoSave();
        this.loadUserPreferences();
        console.log('HCJ Code Editor initialized successfully');
    },
    
    // Cache frequently used DOM elements
    cacheElements() {
        this.elements = {
            // Editor elements
            htmlEditor: document.getElementById('htmlMobile'),
            cssEditor: document.getElementById('cssMobile'),
            jsEditor: document.getElementById('jsMobile'),
            
            // Line number elements
            htmlLineNumbers: document.getElementById('lineCounterHtmlMobi'),
            cssLineNumbers: document.getElementById('lineCounterCssMobi'),
            jsLineNumbers: document.getElementById('lineCounterJsMobi'),
            
            // Preview elements
            previewFrame: document.getElementById('codeMobile'),
            previewContainer: document.querySelector('.preview-container'),
            
            // Control elements
            refreshBtn: document.querySelector('.refresh-btn'),
            deviceBtns: document.querySelectorAll('.device-btn'),
            copyBtns: document.querySelectorAll('[onclick*="txtCopy"]'),
            clearBtns: document.querySelectorAll('[onclick*="clearText"]'),
            
            // Download buttons
            downloadBtns: document.querySelectorAll('[id*="btn-"]'),
            
            // Tab elements
            tabInputs: document.querySelectorAll('input[name="mytabs"]'),
            
            // Navbar
            navbar: document.querySelector('.navbar')
        };
    },
    
    // Bind event handlers
    bindEvents() {
        // Scroll event for navbar
        if (this.elements.navbar) {
            window.addEventListener('scroll', this.handleScroll.bind(this));
        }
        
        // Editor events
        if (this.elements.htmlEditor) {
            this.elements.htmlEditor.addEventListener('input', () => this.handleEditorChange('html'));
            this.elements.htmlEditor.addEventListener('scroll', () => this.syncLineNumbers('html'));
            this.elements.htmlEditor.addEventListener('keydown', (e) => this.handleTabKey(e, 'html'));
        }
        
        if (this.elements.cssEditor) {
            this.elements.cssEditor.addEventListener('input', () => this.handleEditorChange('css'));
            this.elements.cssEditor.addEventListener('scroll', () => this.syncLineNumbers('css'));
            this.elements.cssEditor.addEventListener('keydown', (e) => this.handleTabKey(e, 'css'));
        }
        
        if (this.elements.jsEditor) {
            this.elements.jsEditor.addEventListener('input', () => this.handleEditorChange('js'));
            this.elements.jsEditor.addEventListener('scroll', () => this.syncLineNumbers('js'));
            this.elements.jsEditor.addEventListener('keydown', (e) => this.handleTabKey(e, 'js'));
        }
        
        // Tab change events
        this.elements.tabInputs.forEach(tab => {
            tab.addEventListener('change', (e) => {
                this.state.currentTab = e.target.id.replace('tab', '').toLowerCase();
                this.updatePreview();
            });
        });
        
        // Keyboard shortcuts
        document.addEventListener('keydown', this.handleKeyboardShortcuts.bind(this));
        
        // Window events
        window.addEventListener('beforeunload', this.handleBeforeUnload.bind(this));
        window.addEventListener('resize', this.handleResize.bind(this));
        
        // CSRF token setup for AJAX requests
        this.setupCSRFToken();
    },
    
    // Handle scroll events for navbar
    handleScroll() {
        if (window.scrollY > 50) {
            this.elements.navbar.classList.add('scrolled');
        } else {
            this.elements.navbar.classList.remove('scrolled');
        }
    },
    
    // Handle editor content changes
    handleEditorChange(type) {
        this.state.hasUnsavedChanges = true;
        this.updateLineNumbers(type);
        this.updatePreview();
        this.showUnsavedIndicator();
    },
    
    // Sync line numbers with editor scroll
    syncLineNumbers(type) {
        const editor = this.elements[`${type}Editor`];
        const lineNumbers = this.elements[`${type}LineNumbers`];
        
        if (editor && lineNumbers) {
            lineNumbers.scrollTop = editor.scrollTop;
            lineNumbers.scrollLeft = editor.scrollLeft;
        }
    },
    
    // Handle tab key for proper indentation
    handleTabKey(event, type) {
        if (event.keyCode === 9) { // Tab key
            event.preventDefault();
            const editor = this.elements[`${type}Editor`];
            const { value, selectionStart, selectionEnd } = editor;
            const tabChar = ' '.repeat(this.config.tabSize);
            
            editor.value = value.slice(0, selectionStart) + tabChar + value.slice(selectionEnd);
            editor.setSelectionRange(selectionStart + this.config.tabSize, selectionStart + this.config.tabSize);
        }
    },
    
    // Update line numbers
    updateLineNumbers(type) {
        const editor = this.elements[`${type}Editor`];
        const lineNumbers = this.elements[`${type}LineNumbers`];
        
        if (!editor || !lineNumbers) return;
        
        const lineCount = editor.value.split('\n').length;
        const numbers = Array.from({ length: lineCount }, (_, i) => `${i + 1}.`);
        lineNumbers.value = numbers.join('\n');
    },
    
    // Initialize editor with default content and settings
    initializeEditor() {
        // Set default content if empty
        if (this.elements.htmlEditor && !this.elements.htmlEditor.value.trim()) {
            this.elements.htmlEditor.value = `<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Project</title>
</head>
<body>
    <h1>Welcome to HCJ Code Editor!</h1>
    <p>Start coding your amazing project here.</p>
</body>
</html>`;
        }
        
        if (this.elements.cssEditor && !this.elements.cssEditor.value.trim()) {
            this.elements.cssEditor.value = `/* Your CSS styles here */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 20px;
    background-color: #f5f5f5;
}

h1 {
    color: #333;
    text-align: center;
}

p {
    color: #666;
    line-height: 1.6;
}`;
        }
        
        if (this.elements.jsEditor && !this.elements.jsEditor.value.trim()) {
            this.elements.jsEditor.value = `// Your JavaScript code here
console.log('Welcome to HCJ Code Editor!');

// Example: Add interactivity to your page
document.addEventListener('DOMContentLoaded', function() {
    console.log('Page loaded successfully!');
});`;
        }
        
        // Initialize line numbers
        ['html', 'css', 'js'].forEach(type => {
            this.updateLineNumbers(type);
        });
        
        // Initial preview update
        this.updatePreview();
    },
    
    // Update live preview
    updatePreview() {
        if (!this.elements.previewFrame) return;
        
        const html = this.elements.htmlEditor?.value || '';
        const css = this.elements.cssEditor?.value || '';
        const js = this.elements.jsEditor?.value || '';
        
        const previewContent = `
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Preview</title>
                <style>${css}</style>
            </head>
            <body>
                ${html}
                <script>
                    try {
                        ${js}
                    } catch (error) {
                        console.error('JavaScript Error:', error);
                    }
                </script>
            </body>
            </html>
        `;
        
        const previewDoc = this.elements.previewFrame.contentDocument || this.elements.previewFrame.contentWindow.document;
        previewDoc.open();
        previewDoc.write(previewContent);
        previewDoc.close();
    },
    
    // Handle keyboard shortcuts
    handleKeyboardShortcuts(event) {
        // Ctrl/Cmd + S: Save (prevent default and show message)
        if ((event.ctrlKey || event.metaKey) && event.key === 's') {
            event.preventDefault();
            this.saveProject();
        }
        
        // Ctrl/Cmd + R: Refresh preview
        if ((event.ctrlKey || event.metaKey) && event.key === 'r') {
            event.preventDefault();
            this.refreshPreview();
        }
        
        // Ctrl/Cmd + /: Toggle comment (basic implementation)
        if ((event.ctrlKey || event.metaKey) && event.key === '/') {
            event.preventDefault();
            this.toggleComment();
        }
    },
    
// Removed saveProject function as per user request
    
    // Refresh preview
    refreshPreview() {
        this.showLoadingSpinner();
        setTimeout(() => {
            this.updatePreview();
            this.hideLoadingSpinner();
            this.showNotification('Preview refreshed!', 'info');
        }, 300);
    },
    
    // Toggle comment for current line (basic implementation)
    toggleComment() {
        const currentEditor = this.elements[`${this.state.currentTab}Editor`];
        if (!currentEditor) return;
        
        const { value, selectionStart, selectionEnd } = currentEditor;
        const lines = value.split('\n');
        const startLine = value.substring(0, selectionStart).split('\n').length - 1;
        const endLine = value.substring(0, selectionEnd).split('\n').length - 1;
        
        const commentChars = {
            html: ['<!--', '-->'],
            css: ['/*', '*/'],
            js: ['//', '']
        };
        
        const [commentStart, commentEnd] = commentChars[this.state.currentTab] || ['', ''];
        
        for (let i = startLine; i <= endLine; i++) {
            const line = lines[i];
            if (line.trim().startsWith(commentStart)) {
                // Remove comment
                lines[i] = line.replace(new RegExp(`^(\\s*)${commentStart}\\s?`), '$1')
                              .replace(new RegExp(`\\s?${commentEnd}$`), '');
            } else {
                // Add comment
                lines[i] = commentStart + ' ' + line + (commentEnd ? ' ' + commentEnd : '');
            }
        }
        
        currentEditor.value = lines.join('\n');
        this.handleEditorChange(this.state.currentTab);
    },
    
    // Setup auto-save functionality
    setupAutoSave() {
        if (this.config.autoSave) {
            setInterval(() => {
                if (this.state.hasUnsavedChanges) {
                    this.autoSave();
                }
            }, this.config.autoSaveInterval);
        }
    },
    
    // Auto-save to localStorage
    autoSave() {
        const projectData = {
            html: this.elements.htmlEditor?.value || '',
            css: this.elements.cssEditor?.value || '',
            js: this.elements.jsEditor?.value || '',
            timestamp: new Date().toISOString()
        };
        
        try {
            localStorage.setItem('hcj_editor_autosave', JSON.stringify(projectData));
            console.log('Auto-saved project data');
        } catch (error) {
            console.error('Auto-save failed:', error);
        }
    },
    
    // Load user preferences
    loadUserPreferences() {
        try {
            const preferences = localStorage.getItem('hcj_editor_preferences');
            if (preferences) {
                const prefs = JSON.parse(preferences);
                Object.assign(this.config, prefs);
            }
            
            // Load auto-saved content
            const autoSaved = localStorage.getItem('hcj_editor_autosave');
            if (autoSaved) {
                const data = JSON.parse(autoSaved);
                if (this.elements.htmlEditor) this.elements.htmlEditor.value = data.html || '';
                if (this.elements.cssEditor) this.elements.cssEditor.value = data.css || '';
                if (this.elements.jsEditor) this.elements.jsEditor.value = data.js || '';
                
                // Update line numbers and preview
                ['html', 'css', 'js'].forEach(type => this.updateLineNumbers(type));
                this.updatePreview();
            }
        } catch (error) {
            console.error('Failed to load user preferences:', error);
        }
    },
    
    // Save user preferences
    saveUserPreferences() {
        try {
            localStorage.setItem('hcj_editor_preferences', JSON.stringify(this.config));
        } catch (error) {
            console.error('Failed to save user preferences:', error);
        }
    },
    
    // Handle window resize
    handleResize() {
        // Update preview viewport size display
        const viewportSize = document.querySelector('.viewport-size');
        if (viewportSize && this.state.deviceMode === 'desktop') {
            viewportSize.textContent = `${window.innerWidth} x ${window.innerHeight}`;
        }
    },
    
    // Handle before unload
    handleBeforeUnload(event) {
        if (this.state.hasUnsavedChanges) {
            event.preventDefault();
            event.returnValue = 'You have unsaved changes. Are you sure you want to leave?';
            return event.returnValue;
        }
    },
    
    // Show loading spinner
    showLoadingSpinner() {
        const spinner = document.querySelector('.preview-loading');
        if (spinner) {
            spinner.classList.add('show');
        }
    },
    
    // Hide loading spinner
    hideLoadingSpinner() {
        const spinner = document.querySelector('.preview-loading');
        if (spinner) {
            spinner.classList.remove('show');
        }
    },
    
    // Show unsaved changes indicator
    showUnsavedIndicator() {
        const title = document.title;
        if (!title.startsWith('*')) {
            document.title = '*' + title;
        }
    },
    
    // Hide unsaved changes indicator
    hideUnsavedIndicator() {
        const title = document.title;
        if (title.startsWith('*')) {
            document.title = title.substring(1);
        }
    },
    
    // Show notification
    showNotification(message, type = 'info') {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: type === 'error' ? 'error' : type === 'success' ? 'success' : 'info',
                title: message,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        } else {
            console.log(`${type.toUpperCase()}: ${message}`);
        }
    },
    
    // Setup CSRF token for AJAX requests
    setupCSRFToken() {
        const token = document.querySelector('meta[name="csrf-token"]');
        if (token) {
            // Set up default headers for fetch requests
            const originalFetch = window.fetch;
            window.fetch = function(url, options = {}) {
                if (options.method && options.method.toUpperCase() !== 'GET') {
                    options.headers = options.headers || {};
                    options.headers['X-CSRF-Token'] = token.getAttribute('content');
                }
                return originalFetch(url, options);
            };
        }
    },
    
    // Utility function to debounce function calls
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
};

// Enhanced copy functionality
function copyToClipboard(text, successMessage = 'Copied to clipboard!') {
    if (!text.trim()) {
        HCJEditor.showNotification('Nothing to copy. Please enter some code.', 'warning');
        return;
    }
    
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(text)
            .then(() => HCJEditor.showNotification(successMessage, 'success'))
            .catch(err => {
                console.error('Clipboard error:', err);
                HCJEditor.showNotification('Clipboard access denied.', 'error');
            });
    } else {
        // Fallback for older browsers
        const textArea = document.createElement('textarea');
        textArea.value = text;
        textArea.style.position = 'fixed';
        textArea.style.opacity = '0';
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        
        try {
            const successful = document.execCommand('copy');
            if (successful) {
                HCJEditor.showNotification(successMessage, 'success');
            } else {
                HCJEditor.showNotification('Copy failed. Please try manually.', 'error');
            }
        } catch (err) {
            console.error('Copy error:', err);
            HCJEditor.showNotification('Copy not supported in this browser.', 'error');
        } finally {
            document.body.removeChild(textArea);
        }
    }
}

// Enhanced download functionality
function downloadFile(content, filename, mimeType = 'text/plain') {
    if (!content.trim()) {
        HCJEditor.showNotification('Nothing to download. Please enter some code.', 'warning');
        return;
    }
    
    try {
        const blob = new Blob([content], { type: mimeType });
        const url = window.URL.createObjectURL(blob);
        const link = document.createElement('a');
        
        link.href = url;
        link.download = filename;
        link.style.display = 'none';
        
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        window.URL.revokeObjectURL(url);
        HCJEditor.showNotification(`Downloaded ${filename}`, 'success');
    } catch (error) {
        console.error('Download error:', error);
        HCJEditor.showNotification('Download failed. Please try again.', 'error');
    }
}

// Initialize the application when DOM is loaded
// document.addEventListener('DOMContentLoaded', () => {
//     HCJEditor.init();

//     // Add shareProject function
//     window.shareProject = function() {
//         const html = HCJEditor.elements.htmlEditor?.value || '';
//         const css = HCJEditor.elements.cssEditor?.value || '';
//         const js = HCJEditor.elements.jsEditor?.value || '';
//         const projectName = prompt('Enter project name for sharing:', 'Untitled Project') || 'Untitled Project';

//         if (!html && !css && !js) {
//             HCJEditor.showNotification('Cannot share empty project.', 'warning');
//             return;
//         }

//         fetch('api/share_project.php', {
//             method: 'POST',
//             headers: {
//                 'Content-Type': 'application/json'
//             },
//             body: JSON.stringify({
//                 project_name: projectName,
//                 html: html,
//                 css: css,
//                 js: js
//             })
//         })
//         .then(response => {
//             if (!response.ok) {
//                 throw new Error('Network response was not ok: ' + response.statusText);
//             }
//             return response.json();
//         })
//         .then(data => {
//             console.log('Share project response:', data);
//             if (data.success && data.share_url) {
//                 // Show shareable link to user
//                 const shareLink = data.share_url;
//                 if (navigator.clipboard && window.isSecureContext) {
//                     navigator.clipboard.writeText(shareLink).then(() => {
//                         HCJEditor.showNotification('Share link copied to clipboard!', 'success');
//                         alert('Share your project using this link:\n' + shareLink);
//                     }).catch(() => {
//                         alert('Share your project using this link:\n' + shareLink);
//                     });
//                 } else {
//                     alert('Share your project using this link:\n' + shareLink);
//                 }
//             } else {
//                 HCJEditor.showNotification('Failed to share project.', 'error');
//             }
//         })
//         .catch(error => {
//             console.error('Share project error:', error);
//             HCJEditor.showNotification('Error sharing project.', 'error');
//         });
//     };
// });

// Export for global access
window.HCJEditor = HCJEditor;
window.copyToClipboard = copyToClipboard;
window.downloadFile = downloadFile;
