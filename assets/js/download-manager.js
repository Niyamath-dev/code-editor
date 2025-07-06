/**
 * HCJ Code Editor - Download Manager
 * Enhanced file download and project management
 */

const DownloadManager = {
    // File type configurations
    fileTypes: {
        html: {
            extension: 'html',
            mimeType: 'text/html',
            icon: 'bi-filetype-html'
        },
        css: {
            extension: 'css',
            mimeType: 'text/css',
            icon: 'bi-filetype-css'
        },
        js: {
            extension: 'js',
            mimeType: 'text/javascript',
            icon: 'bi-filetype-js'
        },
        txt: {
            extension: 'txt',
            mimeType: 'text/plain',
            icon: 'bi-file-text'
        }
    },
    
    // Initialize download manager
    init() {
        this.bindEvents();
        console.log('Download manager initialized');
    },
    
    // Bind event handlers
    bindEvents() {
        // Legacy download button support
        this.bindLegacyButtons();
    },
    
    // Bind legacy download buttons for backward compatibility
    bindLegacyButtons() {
        const buttonMappings = [
            { id: 'btn-html-codeHtml', type: 'html', format: 'code' },
            { id: 'btn-html-plainHtml', type: 'html', format: 'plain' },
            { id: 'btn-css-codeCss', type: 'css', format: 'code' },
            { id: 'btn-css-plainCss', type: 'css', format: 'plain' },
            { id: 'btn-js-codeJs', type: 'js', format: 'code' },
            { id: 'btn-js-plainJs', type: 'js', format: 'plain' }
        ];
        
        buttonMappings.forEach(mapping => {
            const button = document.getElementById(mapping.id);
            if (button) {
                button.addEventListener('click', () => {
                    this.downloadFile(mapping.type, mapping.format);
                });
            }
        });
    },
    
    // Download individual file
    downloadFile(type, format = 'code') {
        const editor = document.getElementById(`${type}Mobile`);
        if (!editor) {
            HCJEditor.showNotification(`${type.toUpperCase()} editor not found`, 'error');
            return;
        }
        
        const content = editor.value.trim();
        if (!content) {
            HCJEditor.showNotification(`No ${type.toUpperCase()} content to download`, 'warning');
            return;
        }
        
        const fileConfig = this.fileTypes[type];
        if (!fileConfig) {
            HCJEditor.showNotification(`Unknown file type: ${type}`, 'error');
            return;
        }
        
        const extension = format === 'plain' ? 'txt' : fileConfig.extension;
        const mimeType = format === 'plain' ? 'text/plain' : fileConfig.mimeType;
        const filename = this.generateFilename(type, extension);
        
        try {
            this.createDownload(content, filename, mimeType);
            HCJEditor.showNotification(`Downloaded ${filename}`, 'success');
        } catch (error) {
            console.error('Download error:', error);
            HCJEditor.showNotification('Download failed', 'error');
        }
    },
    
    // Download complete project
    downloadProject(format = 'separate') {
        const htmlContent = document.getElementById('htmlMobile')?.value || '';
        const cssContent = document.getElementById('cssMobile')?.value || '';
        const jsContent = document.getElementById('jsMobile')?.value || '';
        
        if (!htmlContent.trim() && !cssContent.trim() && !jsContent.trim()) {
            HCJEditor.showNotification('No content to download', 'warning');
            return;
        }
        
        switch (format) {
            case 'separate':
                this.downloadSeparateFiles(htmlContent, cssContent, jsContent);
                break;
            case 'combined':
                this.downloadCombinedFile(htmlContent, cssContent, jsContent);
                break;
            case 'zip':
                this.downloadZipFile(htmlContent, cssContent, jsContent);
                break;
            default:
                this.downloadCombinedFile(htmlContent, cssContent, jsContent);
        }
    },
    
    // Download separate files
    downloadSeparateFiles(html, css, js) {
        const files = [];
        
        if (html.trim()) {
            files.push({ content: html, filename: 'index.html', type: 'html' });
        }
        if (css.trim()) {
            files.push({ content: css, filename: 'style.css', type: 'css' });
        }
        if (js.trim()) {
            files.push({ content: js, filename: 'script.js', type: 'js' });
        }
        
        if (files.length === 0) {
            HCJEditor.showNotification('No content to download', 'warning');
            return;
        }
        
        // Download files with delay to prevent browser blocking
        files.forEach((file, index) => {
            setTimeout(() => {
                const fileConfig = this.fileTypes[file.type];
                this.createDownload(file.content, file.filename, fileConfig.mimeType);
            }, index * 500);
        });
        
        HCJEditor.showNotification(`Downloading ${files.length} files...`, 'success');
    },
    
    // Download combined HTML file
    downloadCombinedFile(html, css, js) {
        const combinedContent = this.generateCombinedHTML(html, css, js);
        const filename = this.generateFilename('project', 'html');
        
        try {
            this.createDownload(combinedContent, filename, 'text/html');
            HCJEditor.showNotification(`Downloaded ${filename}`, 'success');
        } catch (error) {
            console.error('Download error:', error);
            HCJEditor.showNotification('Download failed', 'error');
        }
    },
    
    // Generate combined HTML content
    generateCombinedHTML(html, css, js) {
        const timestamp = new Date().toISOString();
        
        return `<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Project</title>
    <!-- Generated by HCJ Code Editor on ${timestamp} -->
    ${css.trim() ? `<style>\n${css}\n    </style>` : ''}
</head>
<body>
${html}
${js.trim() ? `    <script>\n${js}\n    </script>` : ''}
</body>
</html>`;
    },
    
    // Download ZIP file
    downloadZipFile(html, css, js) {
        if (typeof JSZip === 'undefined') {
            HCJEditor.showNotification('JSZip library not loaded. Please refresh the page.', 'error');
            return;
        }
        
        const zip = new JSZip();
        const timestamp = new Date().toISOString().slice(0, 19).replace(/[:.]/g, '-');
        
        // Add files to ZIP if they have content
        if (html.trim()) {
            zip.file('index.html', html);
        }
        
        if (css.trim()) {
            zip.file('style.css', css);
        }
        
        if (js.trim()) {
            zip.file('script.js', js);
        }
        
        // Add a README file
        const readmeContent = `# My Project

Generated by HCJ Code Editor on ${new Date().toLocaleString()}

## Files included:
${html.trim() ? '- index.html - Your HTML code\n' : ''}${css.trim() ? '- style.css - Your CSS styles\n' : ''}${js.trim() ? '- script.js - Your JavaScript code\n' : ''}
## How to use:
1. Open index.html in your browser to view your project
2. Edit the individual files as needed
3. Link your CSS and JS files to your HTML

Happy coding!
`;
        zip.file('README.md', readmeContent);
        
        // Generate and download the ZIP file
        zip.generateAsync({ type: 'blob' })
            .then(content => {
                const filename = `my-project-${timestamp}.zip`;
                this.createDownload(content, filename, 'application/zip');
                HCJEditor.showNotification(`Downloaded ${filename}`, 'success');
            })
            .catch(error => {
                console.error('ZIP generation error:', error);
                HCJEditor.showNotification('Failed to create ZIP file', 'error');
            });
    },
    
    // Create and trigger download
    createDownload(content, filename, mimeType) {
        const blob = new Blob([content], { type: mimeType });
        const url = window.URL.createObjectURL(blob);
        const link = document.createElement('a');
        
        link.href = url;
        link.download = filename;
        link.style.display = 'none';
        
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        // Clean up the URL object
        setTimeout(() => {
            window.URL.revokeObjectURL(url);
        }, 100);
    },
    
    // Generate filename with timestamp
    generateFilename(type, extension) {
        const timestamp = new Date().toISOString().slice(0, 19).replace(/[:.]/g, '-');
        const baseName = type === 'project' ? 'my-project' : type;
        return `${baseName}-${timestamp}.${extension}`;
    },
    
    // Show download options modal
    showDownloadOptions() {
        const modal = this.createDownloadModal();
        document.body.appendChild(modal);
        
        // Show modal using Bootstrap
        if (typeof bootstrap !== 'undefined') {
            const bsModal = new bootstrap.Modal(modal);
            bsModal.show();
            
            // Clean up when modal is hidden
            modal.addEventListener('hidden.bs.modal', () => {
                document.body.removeChild(modal);
            });
        }
    },
    
    // Create download options modal
    createDownloadModal() {
        const modal = document.createElement('div');
        modal.className = 'modal fade';
        modal.id = 'downloadModal';
        modal.innerHTML = `
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="bi bi-download"></i> Download Options
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="card h-100">
                                    <div class="card-body text-center">
                                        <i class="bi bi-file-earmark-code display-6 text-primary mb-3"></i>
                                        <h6 class="card-title">Individual Files</h6>
                                        <p class="card-text small">Download HTML, CSS, and JS as separate files</p>
                                        <button class="btn btn-primary btn-sm" onclick="DownloadManager.downloadProject('separate'); bootstrap.Modal.getInstance(document.getElementById('downloadModal')).hide();">
                                            Download Separate
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card h-100">
                                    <div class="card-body text-center">
                                        <i class="bi bi-file-earmark-text display-6 text-success mb-3"></i>
                                        <h6 class="card-title">Combined HTML</h6>
                                        <p class="card-text small">Download as single HTML file with embedded CSS/JS</p>
                                        <button class="btn btn-success btn-sm" onclick="DownloadManager.downloadProject('combined'); bootstrap.Modal.getInstance(document.getElementById('downloadModal')).hide();">
                                            Download Combined
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <hr class="my-4">
                        
                        <h6 class="mb-3">Individual File Downloads</h6>
                        <div class="row g-2">
                            <div class="col-4">
                                <button class="btn btn-outline-primary btn-sm w-100" onclick="DownloadManager.downloadFile('html')">
                                    <i class="bi bi-filetype-html"></i> HTML
                                </button>
                            </div>
                            <div class="col-4">
                                <button class="btn btn-outline-primary btn-sm w-100" onclick="DownloadManager.downloadFile('css')">
                                    <i class="bi bi-filetype-css"></i> CSS
                                </button>
                            </div>
                            <div class="col-4">
                                <button class="btn btn-outline-primary btn-sm w-100" onclick="DownloadManager.downloadFile('js')">
                                    <i class="bi bi-filetype-js"></i> JS
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        `;
        
        return modal;
    },
    
    // Export project data for sharing
    exportProjectData() {
        const htmlContent = document.getElementById('htmlMobile')?.value || '';
        const cssContent = document.getElementById('cssMobile')?.value || '';
        const jsContent = document.getElementById('jsMobile')?.value || '';
        
        const projectData = {
            version: '1.0',
            timestamp: new Date().toISOString(),
            files: {
                html: htmlContent,
                css: cssContent,
                js: jsContent
            },
            metadata: {
                title: 'My Project',
                description: 'Created with HCJ Code Editor',
                author: 'Anonymous'
            }
        };
        
        return projectData;
    },
    
    // Import project data
    importProjectData(projectData) {
        try {
            if (!projectData.files) {
                throw new Error('Invalid project data format');
            }
            
            const htmlEditor = document.getElementById('htmlMobile');
            const cssEditor = document.getElementById('cssMobile');
            const jsEditor = document.getElementById('jsMobile');
            
            if (htmlEditor && projectData.files.html) {
                htmlEditor.value = projectData.files.html;
                htmlEditor.dispatchEvent(new Event('input'));
            }
            
            if (cssEditor && projectData.files.css) {
                cssEditor.value = projectData.files.css;
                cssEditor.dispatchEvent(new Event('input'));
            }
            
            if (jsEditor && projectData.files.js) {
                jsEditor.value = projectData.files.js;
                jsEditor.dispatchEvent(new Event('input'));
            }
            
            HCJEditor.showNotification('Project imported successfully', 'success');
            
        } catch (error) {
            console.error('Import error:', error);
            HCJEditor.showNotification('Failed to import project', 'error');
        }
    },
    
    // Get file statistics
    getFileStats() {
        const htmlContent = document.getElementById('htmlMobile')?.value || '';
        const cssContent = document.getElementById('cssMobile')?.value || '';
        const jsContent = document.getElementById('jsMobile')?.value || '';
        
        return {
            html: {
                lines: htmlContent.split('\n').length,
                characters: htmlContent.length,
                size: new Blob([htmlContent]).size
            },
            css: {
                lines: cssContent.split('\n').length,
                characters: cssContent.length,
                size: new Blob([cssContent]).size
            },
            js: {
                lines: jsContent.split('\n').length,
                characters: jsContent.length,
                size: new Blob([jsContent]).size
            }
        };
    }
};

// Global download functions for backward compatibility
function downloadCompleteProject() {
    DownloadManager.downloadProject('combined');
}

function downloadProjectFiles() {
    DownloadManager.downloadProject('separate');
}

function showDownloadOptions() {
    DownloadManager.showDownloadOptions();
}

// Initialize download manager when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    DownloadManager.init();
});

// Export for global access
window.DownloadManager = DownloadManager;
