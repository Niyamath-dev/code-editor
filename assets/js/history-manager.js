/**
 * History Manager - Handles saving and loading code history
 */

const HistoryManager = {
    // Initialize history manager
    init() {
        this.loadHistory();
        console.log('History Manager initialized');
    },

    // Save current code to history
    saveToHistory() {
        const title = prompt('Enter a title for this code version:');
        if (!title || title.trim() === '') {
            alert('Title is required to save to history.');
            return;
        }

        const htmlCode = document.getElementById('htmlMobile').value;
        const cssCode = document.getElementById('cssMobile').value;
        const jsCode = document.getElementById('jsMobile').value;

        const data = {
            title: title.trim(),
            html: htmlCode,
            css: cssCode,
            js: jsCode
        };

        fetch('api/history.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                this.showNotification('Code saved to history!', 'success');
                this.loadHistory();
            } else {
                this.showNotification('Failed to save: ' + result.error, 'error');
            }
        })
        .catch(error => {
            console.error('Save history error:', error);
            this.showNotification('Failed to save code history', 'error');
        });
    },

    // Load user's history
    loadHistory() {
        fetch('api/history.php')
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                this.renderHistoryTabs(result.history);
            } else {
                this.showNotification('Failed to load history: ' + result.error, 'error');
            }
        })
        .catch(error => {
            console.error('Load history error:', error);
            this.showNotification('Failed to load code history', 'error');
        });
    },

    // Render history as tabs
    renderHistoryTabs(history) {
        const container = document.getElementById('history-tabs');

        if (!history || history.length === 0) {
            container.innerHTML = '<div class="text-center text-muted"><i class="bi bi-info-circle"></i> No history items yet. Save your code to create history.</div>';
            return;
        }

        let html = '<ul class="nav nav-tabs" id="historyTab" role="tablist">';

        history.forEach((item, index) => {
            const activeClass = index === 0 ? 'active' : '';
            const ariaSelected = index === 0 ? 'true' : 'false';
            const tabId = `history-tab-${item.id}`;
            const contentId = `history-content-${item.id}`;

            html += `
                <li class="nav-item" role="presentation">
                    <button class="nav-link ${activeClass}" id="${tabId}" data-bs-toggle="tab" data-bs-target="#${contentId}"
                            type="button" role="tab" aria-controls="${contentId}" aria-selected="${ariaSelected}">
                        ${this.escapeHtml(item.title)}
                        <small class="text-muted ms-1">${this.formatDate(item.created_at)}</small>
                        <button class="btn btn-sm btn-outline-danger ms-2" onclick="HistoryManager.deleteHistory(${item.id})" title="Delete">
                            <i class="bi bi-trash"></i>
                        </button>
                    </button>
                </li>
            `;
        });

        html += '</ul><div class="tab-content mt-3" id="historyTabContent">';

        history.forEach((item, index) => {
            const activeClass = index === 0 ? 'show active' : '';
            const contentId = `history-content-${item.id}`;

            html += `
                <div class="tab-pane fade ${activeClass}" id="${contentId}" role="tabpanel" aria-labelledby="history-tab-${item.id}">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <small class="text-muted">Created: ${this.formatDateTime(item.created_at)}</small>
                        <button class="btn btn-sm btn-primary" onclick="HistoryManager.loadHistoryItem(${item.id})">
                            <i class="bi bi-upload"></i> Load Code
                        </button>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <h6>HTML (${item.html_code ? item.html_code.length : 0} chars)</h6>
                            <pre class="bg-light p-2 rounded small"><code>${this.escapeHtml(item.html_code || 'Empty')}</code></pre>
                        </div>
                        <div class="col-md-4">
                            <h6>CSS (${item.css_code ? item.css_code.length : 0} chars)</h6>
                            <pre class="bg-light p-2 rounded small"><code>${this.escapeHtml(item.css_code || 'Empty')}</code></pre>
                        </div>
                        <div class="col-md-4">
                            <h6>JavaScript (${item.js_code ? item.js_code.length : 0} chars)</h6>
                            <pre class="bg-light p-2 rounded small"><code>${this.escapeHtml(item.js_code || 'Empty')}</code></pre>
                        </div>
                    </div>
                </div>
            `;
        });

        html += '</div>';
        container.innerHTML = html;
    },

    // Load a specific history item into editors
    loadHistoryItem(id) {
        // For now, we'll need to fetch the full item details
        // Since the list doesn't include code, we need another endpoint or modify the existing one
        // For simplicity, let's add a GET with id parameter

        fetch(`api/history.php?id=${id}`)
        .then(response => response.json())
        .then(result => {
            if (result.success && result.history.length > 0) {
                const item = result.history[0];
                document.getElementById('htmlMobile').value = item.html_code || '';
                document.getElementById('cssMobile').value = item.css_code || '';
                document.getElementById('jsMobile').value = item.js_code || '';

                // Trigger input events to update preview and line numbers
                ['htmlMobile', 'cssMobile', 'jsMobile'].forEach(id => {
                    document.getElementById(id).dispatchEvent(new Event('input'));
                });

                this.showNotification('History item loaded!', 'success');
            } else {
                this.showNotification('Failed to load history item', 'error');
            }
        })
        .catch(error => {
            console.error('Load history item error:', error);
            this.showNotification('Failed to load history item', 'error');
        });
    },

    // Delete a history item
    deleteHistory(id) {
        if (!confirm('Are you sure you want to delete this history item?')) {
            return;
        }

        fetch(`api/history.php?id=${id}`, {
            method: 'DELETE'
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                this.showNotification('History item deleted!', 'success');
                this.loadHistory();
            } else {
                this.showNotification('Failed to delete: ' + result.error, 'error');
            }
        })
        .catch(error => {
            console.error('Delete history error:', error);
            this.showNotification('Failed to delete history item', 'error');
        });
    },

    // Utility functions
    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    },

    formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString();
    },

    formatDateTime(dateString) {
        const date = new Date(dateString);
        return date.toLocaleString();
    },

    showNotification(message, type = 'info') {
        // Use existing notification system if available
        if (typeof HCJEditor !== 'undefined' && HCJEditor.showNotification) {
            HCJEditor.showNotification(message, type);
        } else {
            alert(message);
        }
    }
};

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    HistoryManager.init();
});

// Export for global access
window.HistoryManager = HistoryManager;
