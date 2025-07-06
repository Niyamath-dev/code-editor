/**
 * HCJ Code Editor - Enhanced Editor Functionality
 * Advanced code editor features and utilities
 */

// Enhanced Editor Module
const EditorModule = {
    // Editor instances
    editors: {},
    
    // Syntax highlighting patterns
    syntaxPatterns: {
        html: {
            tag: /<\/?[\w\s="/.':;#-\/\?]+>/gi,
            attribute: /[\w-]+(?=\s*=)/gi,
            string: /"[^"]*"|'[^']*'/gi,
            comment: /<!--[\s\S]*?-->/gi
        },
        css: {
            selector: /[.#]?[\w-]+(?=\s*{)/gi,
            property: /[\w-]+(?=\s*:)/gi,
            value: /:\s*[^;]+/gi,
            comment: /\/\*[\s\S]*?\*\//gi
        },
        js: {
            keyword: /\b(var|let|const|function|if|else|for|while|return|class|import|export)\b/gi,
            string: /"[^"]*"|'[^']*'|`[^`]*`/gi,
            comment: /\/\/.*$|\/\*[\s\S]*?\*\//gm,
            number: /\b\d+\.?\d*\b/gi
        }
    },
    
    // Initialize enhanced editor features
    init() {
        this.setupEditorInstances();
        this.setupAutoComplete();
        this.setupErrorDetection();
        this.setupCodeFormatting();
        this.setupSearchAndReplace();
        console.log('Enhanced editor features initialized');
    },
    
    // Setup editor instances with enhanced features
    setupEditorInstances() {
        const editorTypes = ['html', 'css', 'js'];
        
        editorTypes.forEach(type => {
            const editor = document.getElementById(`${type}Mobile`);
            if (editor) {
                this.editors[type] = {
                    element: editor,
                    type: type,
                    history: [],
                    historyIndex: -1,
                    bookmarks: [],
                    foldedRegions: []
                };
                
                this.enhanceEditor(type);
            }
        });
    },
    
    // Enhance individual editor with advanced features
    enhanceEditor(type) {
        const editor = this.editors[type];
        if (!editor) return;
        
        const element = editor.element;
        
        // Add enhanced event listeners
        element.addEventListener('input', (e) => this.handleInput(type, e));
        element.addEventListener('keydown', (e) => this.handleKeyDown(type, e));
        element.addEventListener('keyup', (e) => this.handleKeyUp(type, e));
        element.addEventListener('paste', (e) => this.handlePaste(type, e));
        element.addEventListener('contextmenu', (e) => this.handleContextMenu(type, e));
        
        // Setup undo/redo
        this.setupUndoRedo(type);
        
        // Setup bracket matching
        this.setupBracketMatching(type);
        
        // Setup auto-indentation
        this.setupAutoIndentation(type);
    },
    
    // Handle input events with enhanced features
    handleInput(type, event) {
        const editor = this.editors[type];
        if (!editor) return;
        
        // Save to history for undo/redo
        this.saveToHistory(type);
        
        // Auto-complete
        this.handleAutoComplete(type, event);
        
        // Syntax validation
        this.validateSyntax(type);
        
        // Update line numbers with syntax highlighting
        this.updateLineNumbersWithHighlighting(type);
    },
    
    // Enhanced key handling
    handleKeyDown(type, event) {
        const editor = this.editors[type];
        if (!editor) return;
        
        const { ctrlKey, metaKey, shiftKey, key, keyCode } = event;
        const isCtrlOrCmd = ctrlKey || metaKey;
        
        // Enhanced keyboard shortcuts
        if (isCtrlOrCmd) {
            switch (key.toLowerCase()) {
                case 'z':
                    event.preventDefault();
                    if (shiftKey) {
                        this.redo(type);
                    } else {
                        this.undo(type);
                    }
                    break;
                    
                case 'y':
                    event.preventDefault();
                    this.redo(type);
                    break;
                    
                case 'f':
                    event.preventDefault();
                    this.showSearchDialog(type);
                    break;
                    
                case 'h':
                    event.preventDefault();
                    this.showReplaceDialog(type);
                    break;
                    
                case 'd':
                    event.preventDefault();
                    this.duplicateLine(type);
                    break;
                    
                case 'l':
                    event.preventDefault();
                    this.goToLine(type);
                    break;
                    
                case '/':
                    event.preventDefault();
                    this.toggleComment(type);
                    break;
                    
                case 'k':
                    if (shiftKey) {
                        event.preventDefault();
                        this.deleteLine(type);
                    }
                    break;
            }
        }
        
        // Auto-indentation on Enter
        if (keyCode === 13) { // Enter key
            this.handleEnterKey(type, event);
        }
        
        // Auto-closing brackets and quotes
        if (this.shouldAutoClose(key)) {
            this.handleAutoClose(type, event, key);
        }
    },
    
    // Handle Enter key for auto-indentation
    handleEnterKey(type, event) {
        const element = this.editors[type].element;
        const { value, selectionStart } = element;
        
        // Get current line
        const lines = value.substring(0, selectionStart).split('\n');
        const currentLine = lines[lines.length - 1];
        
        // Calculate indentation
        const indentMatch = currentLine.match(/^(\s*)/);
        const currentIndent = indentMatch ? indentMatch[1] : '';
        
        // Check if we need extra indentation
        let extraIndent = '';
        if (type === 'html' && currentLine.includes('<') && !currentLine.includes('</')) {
            extraIndent = '  '; // 2 spaces
        } else if (type === 'css' && currentLine.includes('{')) {
            extraIndent = '  ';
        } else if (type === 'js' && (currentLine.includes('{') || currentLine.includes('('))) {
            extraIndent = '  ';
        }
        
        // Insert new line with proper indentation
        event.preventDefault();
        const newIndent = currentIndent + extraIndent;
        const newValue = value.substring(0, selectionStart) + '\n' + newIndent + value.substring(selectionStart);
        
        element.value = newValue;
        element.setSelectionRange(selectionStart + 1 + newIndent.length, selectionStart + 1 + newIndent.length);
        
        // Trigger input event
        element.dispatchEvent(new Event('input'));
    },
    
    // Auto-close brackets and quotes
    shouldAutoClose(key) {
        return ['(', '[', '{', '"', "'", '<'].includes(key);
    },
    
    handleAutoClose(type, event, key) {
        const element = this.editors[type].element;
        const { value, selectionStart, selectionEnd } = element;
        
        const closingChars = {
            '(': ')',
            '[': ']',
            '{': '}',
            '"': '"',
            "'": "'",
            '<': '>'
        };
        
        const closingChar = closingChars[key];
        if (!closingChar) return;
        
        // Don't auto-close if there's selected text or if the next character is the same
        if (selectionStart !== selectionEnd || value[selectionStart] === closingChar) return;
        
        // Special handling for HTML tags
        if (key === '<' && type === 'html') {
            // Don't auto-close if it looks like a closing tag
            const beforeCursor = value.substring(0, selectionStart);
            if (beforeCursor.endsWith('</')) return;
        }
        
        event.preventDefault();
        
        const newValue = value.substring(0, selectionStart) + key + closingChar + value.substring(selectionEnd);
        element.value = newValue;
        element.setSelectionRange(selectionStart + 1, selectionStart + 1);
        
        // Trigger input event
        element.dispatchEvent(new Event('input'));
    },
    
    // Setup undo/redo functionality
    setupUndoRedo(type) {
        const editor = this.editors[type];
        if (!editor) return;
        
        // Initialize history with current content
        this.saveToHistory(type);
    },
    
    // Save current state to history
    saveToHistory(type) {
        const editor = this.editors[type];
        if (!editor) return;
        
        const currentValue = editor.element.value;
        const currentCursor = editor.element.selectionStart;
        
        // Don't save if it's the same as the last entry
        const lastEntry = editor.history[editor.historyIndex];
        if (lastEntry && lastEntry.value === currentValue) return;
        
        // Remove any history after current index
        editor.history = editor.history.slice(0, editor.historyIndex + 1);
        
        // Add new entry
        editor.history.push({
            value: currentValue,
            cursor: currentCursor,
            timestamp: Date.now()
        });
        
        // Limit history size
        if (editor.history.length > 100) {
            editor.history.shift();
        } else {
            editor.historyIndex++;
        }
    },
    
    // Undo functionality
    undo(type) {
        const editor = this.editors[type];
        if (!editor || editor.historyIndex <= 0) return;
        
        editor.historyIndex--;
        const entry = editor.history[editor.historyIndex];
        
        editor.element.value = entry.value;
        editor.element.setSelectionRange(entry.cursor, entry.cursor);
        
        // Trigger input event
        editor.element.dispatchEvent(new Event('input'));
        
        HCJEditor.showNotification('Undo', 'info');
    },
    
    // Redo functionality
    redo(type) {
        const editor = this.editors[type];
        if (!editor || editor.historyIndex >= editor.history.length - 1) return;
        
        editor.historyIndex++;
        const entry = editor.history[editor.historyIndex];
        
        editor.element.value = entry.value;
        editor.element.setSelectionRange(entry.cursor, entry.cursor);
        
        // Trigger input event
        editor.element.dispatchEvent(new Event('input'));
        
        HCJEditor.showNotification('Redo', 'info');
    },
    
    // Duplicate current line
    duplicateLine(type) {
        const editor = this.editors[type];
        if (!editor) return;
        
        const element = editor.element;
        const { value, selectionStart } = element;
        
        // Find current line boundaries
        const lines = value.split('\n');
        const beforeCursor = value.substring(0, selectionStart);
        const lineIndex = beforeCursor.split('\n').length - 1;
        const currentLine = lines[lineIndex];
        
        // Insert duplicated line
        lines.splice(lineIndex + 1, 0, currentLine);
        element.value = lines.join('\n');
        
        // Move cursor to the duplicated line
        const newCursorPos = selectionStart + currentLine.length + 1;
        element.setSelectionRange(newCursorPos, newCursorPos);
        
        // Trigger input event
        element.dispatchEvent(new Event('input'));
        
        this.saveToHistory(type);
    },
    
    // Delete current line
    deleteLine(type) {
        const editor = this.editors[type];
        if (!editor) return;
        
        const element = editor.element;
        const { value, selectionStart } = element;
        
        // Find current line boundaries
        const lines = value.split('\n');
        const beforeCursor = value.substring(0, selectionStart);
        const lineIndex = beforeCursor.split('\n').length - 1;
        
        // Remove current line
        lines.splice(lineIndex, 1);
        element.value = lines.join('\n');
        
        // Adjust cursor position
        const newLines = element.value.split('\n');
        const newLineIndex = Math.min(lineIndex, newLines.length - 1);
        const newCursorPos = newLines.slice(0, newLineIndex).join('\n').length + (newLineIndex > 0 ? 1 : 0);
        element.setSelectionRange(newCursorPos, newCursorPos);
        
        // Trigger input event
        element.dispatchEvent(new Event('input'));
        
        this.saveToHistory(type);
    },
    
    // Go to specific line
    goToLine(type) {
        const editor = this.editors[type];
        if (!editor) return;
        
        const lineNumber = prompt('Go to line number:');
        if (!lineNumber || isNaN(lineNumber)) return;
        
        const element = editor.element;
        const lines = element.value.split('\n');
        const targetLine = Math.max(1, Math.min(parseInt(lineNumber), lines.length)) - 1;
        
        // Calculate cursor position
        const position = lines.slice(0, targetLine).join('\n').length + (targetLine > 0 ? 1 : 0);
        element.setSelectionRange(position, position);
        element.focus();
        
        // Scroll to line (approximate)
        const lineHeight = 20; // Approximate line height
        element.scrollTop = targetLine * lineHeight;
    },
    
    // Toggle comment for current line/selection
    toggleComment(type) {
        const editor = this.editors[type];
        if (!editor) return;
        
        const element = editor.element;
        const { value, selectionStart, selectionEnd } = element;
        
        const commentPatterns = {
            html: { start: '<!-- ', end: ' -->' },
            css: { start: '/* ', end: ' */' },
            js: { start: '// ', end: '' }
        };
        
        const pattern = commentPatterns[type];
        if (!pattern) return;
        
        // Get selected text or current line
        let selectedText = value.substring(selectionStart, selectionEnd);
        let isSelection = selectionStart !== selectionEnd;
        
        if (!isSelection) {
            // Get current line
            const lines = value.split('\n');
            const beforeCursor = value.substring(0, selectionStart);
            const lineIndex = beforeCursor.split('\n').length - 1;
            const lineStart = beforeCursor.lastIndexOf('\n') + 1;
            const lineEnd = value.indexOf('\n', selectionStart);
            
            selectedText = lines[lineIndex];
            selectionStart = lineStart;
            selectionEnd = lineEnd === -1 ? value.length : lineEnd;
        }
        
        // Check if already commented
        const isCommented = selectedText.trim().startsWith(pattern.start.trim()) && 
                           (pattern.end === '' || selectedText.trim().endsWith(pattern.end.trim()));
        
        let newText;
        if (isCommented) {
            // Remove comment
            newText = selectedText.replace(new RegExp(`^(\\s*)${pattern.start.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')}`), '$1')
                                 .replace(new RegExp(`${pattern.end.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')}(\\s*)$`), '$1');
        } else {
            // Add comment
            if (pattern.end === '') {
                // Line comment
                newText = selectedText.replace(/^(\s*)/, `$1${pattern.start}`);
            } else {
                // Block comment
                newText = pattern.start + selectedText + pattern.end;
            }
        }
        
        // Replace text
        element.value = value.substring(0, selectionStart) + newText + value.substring(selectionEnd);
        
        // Adjust selection
        const newSelectionEnd = selectionStart + newText.length;
        element.setSelectionRange(selectionStart, isSelection ? newSelectionEnd : selectionStart);
        
        // Trigger input event
        element.dispatchEvent(new Event('input'));
        
        this.saveToHistory(type);
    },
    
    // Setup auto-complete functionality
    setupAutoComplete() {
        // Auto-complete suggestions
        this.autoCompleteData = {
            html: [
                'div', 'span', 'p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
                'a', 'img', 'ul', 'ol', 'li', 'table', 'tr', 'td', 'th',
                'form', 'input', 'button', 'textarea', 'select', 'option',
                'header', 'nav', 'main', 'section', 'article', 'aside', 'footer'
            ],
            css: [
                'color', 'background-color', 'font-size', 'font-family', 'font-weight',
                'margin', 'padding', 'border', 'width', 'height', 'display',
                'position', 'top', 'left', 'right', 'bottom', 'z-index',
                'text-align', 'line-height', 'letter-spacing', 'text-decoration'
            ],
            js: [
                'function', 'var', 'let', 'const', 'if', 'else', 'for', 'while',
                'return', 'class', 'import', 'export', 'console.log', 'document',
                'window', 'addEventListener', 'querySelector', 'getElementById'
            ]
        };
    },
    
    // Handle auto-complete
    handleAutoComplete(type, event) {
        // Implementation for auto-complete dropdown
        // This would show suggestions based on current context
    },
    
    // Setup error detection
    setupErrorDetection() {
        // Real-time syntax error detection
    },
    
    // Validate syntax
    validateSyntax(type) {
        const editor = this.editors[type];
        if (!editor) return;
        
        const content = editor.element.value;
        const errors = [];
        
        try {
            switch (type) {
                case 'html':
                    this.validateHTML(content, errors);
                    break;
                case 'css':
                    this.validateCSS(content, errors);
                    break;
                case 'js':
                    this.validateJS(content, errors);
                    break;
            }
        } catch (error) {
            console.error(`Syntax validation error for ${type}:`, error);
        }
        
        this.displayErrors(type, errors);
    },
    
    // Validate HTML
    validateHTML(content, errors) {
        // Basic HTML validation
        const openTags = [];
        const tagRegex = /<\/?([a-zA-Z][a-zA-Z0-9]*)\b[^>]*>/g;
        let match;
        
        while ((match = tagRegex.exec(content)) !== null) {
            const tagName = match[1].toLowerCase();
            const isClosing = match[0].startsWith('</');
            const isSelfClosing = match[0].endsWith('/>') || ['img', 'br', 'hr', 'input', 'meta', 'link'].includes(tagName);
            
            if (isClosing) {
                if (openTags.length === 0 || openTags[openTags.length - 1] !== tagName) {
                    errors.push({
                        line: this.getLineNumber(content, match.index),
                        message: `Unexpected closing tag: ${tagName}`,
                        type: 'error'
                    });
                } else {
                    openTags.pop();
                }
            } else if (!isSelfClosing) {
                openTags.push(tagName);
            }
        }
        
        // Check for unclosed tags
        openTags.forEach(tag => {
            errors.push({
                line: -1,
                message: `Unclosed tag: ${tag}`,
                type: 'warning'
            });
        });
    },
    
    // Validate CSS
    validateCSS(content, errors) {
        // Basic CSS validation
        const braceCount = (content.match(/{/g) || []).length - (content.match(/}/g) || []).length;
        if (braceCount !== 0) {
            errors.push({
                line: -1,
                message: 'Mismatched braces in CSS',
                type: 'error'
            });
        }
    },
    
    // Validate JavaScript
    validateJS(content, errors) {
        // Basic JS validation using try/catch
        try {
            new Function(content);
        } catch (error) {
            errors.push({
                line: error.lineNumber || -1,
                message: error.message,
                type: 'error'
            });
        }
    },
    
    // Get line number from character index
    getLineNumber(content, index) {
        return content.substring(0, index).split('\n').length;
    },
    
    // Display errors
    displayErrors(type, errors) {
        // Remove existing error indicators
        this.clearErrorIndicators(type);
        
        if (errors.length === 0) return;
        
        // Add error indicators to line numbers
        errors.forEach(error => {
            if (error.line > 0) {
                this.addErrorIndicator(type, error.line, error.message, error.type);
            }
        });
        
        // Show error summary
        const errorCount = errors.filter(e => e.type === 'error').length;
        const warningCount = errors.filter(e => e.type === 'warning').length;
        
        if (errorCount > 0 || warningCount > 0) {
            console.log(`${type.toUpperCase()}: ${errorCount} errors, ${warningCount} warnings`);
        }
    },
    
    // Clear error indicators
    clearErrorIndicators(type) {
        // Implementation to clear visual error indicators
    },
    
    // Add error indicator
    addErrorIndicator(type, line, message, errorType) {
        // Implementation to add visual error indicators
    },
    
    // Setup code formatting
    setupCodeFormatting() {
        // Code formatting utilities
    },
    
    // Format code
    formatCode(type) {
        const editor = this.editors[type];
        if (!editor) return;
        
        const content = editor.element.value;
        let formattedContent;
        
        try {
            switch (type) {
                case 'html':
                    formattedContent = this.formatHTML(content);
                    break;
                case 'css':
                    formattedContent = this.formatCSS(content);
                    break;
                case 'js':
                    formattedContent = this.formatJS(content);
                    break;
                default:
                    return;
            }
            
            editor.element.value = formattedContent;
            editor.element.dispatchEvent(new Event('input'));
            this.saveToHistory(type);
            
            HCJEditor.showNotification(`${type.toUpperCase()} code formatted`, 'success');
        } catch (error) {
            console.error(`Formatting error for ${type}:`, error);
            HCJEditor.showNotification(`Failed to format ${type.toUpperCase()} code`, 'error');
        }
    },
    
    // Format HTML
    formatHTML(content) {
        // Basic HTML formatting
        let formatted = content;
        let indent = 0;
        const indentSize = 2;
        
        // Remove extra whitespace
        formatted = formatted.replace(/>\s+</g, '><');
        
        // Add line breaks and indentation
        formatted = formatted.replace(/></g, '>\n<');
        
        const lines = formatted.split('\n');
        const result = [];
        
        lines.forEach(line => {
            const trimmed = line.trim();
            if (!trimmed) return;
            
            if (trimmed.startsWith('</')) {
                indent = Math.max(0, indent - indentSize);
            }
            
            result.push(' '.repeat(indent) + trimmed);
            
            if (trimmed.startsWith('<') && !trimmed.startsWith('</') && !trimmed.endsWith('/>')) {
                const tagName = trimmed.match(/<([a-zA-Z][a-zA-Z0-9]*)/);
                if (tagName && !['img', 'br', 'hr', 'input', 'meta', 'link'].includes(tagName[1].toLowerCase())) {
                    indent += indentSize;
                }
            }
        });
        
        return result.join('\n');
    },
    
    // Format CSS
    formatCSS(content) {
        // Basic CSS formatting
        let formatted = content;
        
        // Add line breaks after braces and semicolons
        formatted = formatted.replace(/\{/g, ' {\n  ');
        formatted = formatted.replace(/\}/g, '\n}\n');
        formatted = formatted.replace(/;/g, ';\n  ');
        
        // Clean up extra whitespace
        formatted = formatted.replace(/\n\s*\n/g, '\n');
        formatted = formatted.replace(/^\s+|\s+$/g, '');
        
        return formatted;
    },
    
    // Format JavaScript
    formatJS(content) {
        // Basic JS formatting (simplified)
        let formatted = content;
        let indent = 0;
        const indentSize = 2;
        
        // Add line breaks after braces and semicolons
        formatted = formatted.replace(/\{/g, ' {\n');
        formatted = formatted.replace(/\}/g, '\n}\n');
        formatted = formatted.replace(/;/g, ';\n');
        
        const lines = formatted.split('\n');
        const result = [];
        
        lines.forEach(line => {
            const trimmed = line.trim();
            if (!trimmed) return;
            
            if (trimmed === '}') {
                indent = Math.max(0, indent - indentSize);
            }
            
            result.push(' '.repeat(indent) + trimmed);
            
            if (trimmed.endsWith('{')) {
                indent += indentSize;
            }
        });
        
        return result.join('\n');
    },
    
    // Setup search and replace
    setupSearchAndReplace() {
        // Search and replace functionality
    },
    
    // Show search dialog
    showSearchDialog(type) {
        const searchTerm = prompt('Search for:');
        if (!searchTerm) return;
        
        this.searchInEditor(type, searchTerm);
    },
    
    // Show replace dialog
    showReplaceDialog(type) {
        const searchTerm = prompt('Search for:');
        if (!searchTerm) return;
        
        const replaceTerm = prompt('Replace with:');
        if (replaceTerm === null) return;
        
        this.replaceInEditor(type, searchTerm, replaceTerm);
    },
    
    // Search in editor
    searchInEditor(type, searchTerm) {
        const editor = this.editors[type];
        if (!editor) return;
        
        const content = editor.element.value;
        const index = content.toLowerCase().indexOf(searchTerm.toLowerCase());
        
        if (index !== -1) {
            editor.element.setSelectionRange(index, index + searchTerm.length);
            editor.element.focus();
            HCJEditor.showNotification(`Found: ${searchTerm}`, 'success');
        } else {
            HCJEditor.showNotification(`Not found: ${searchTerm}`, 'warning');
        }
    },
    
    // Replace in editor
    replaceInEditor(type, searchTerm, replaceTerm) {
        const editor = this.editors[type];
        if (!editor) return;
        
        const content = editor.element.value;
        const regex = new RegExp(searchTerm.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'), 'gi');
        const matches = content.match(regex);
        
        if (matches) {
            const newContent = content.replace(regex, replaceTerm);
            editor.element.value = newContent;
            editor.element.dispatchEvent(new Event('input'));
            this.saveToHistory(type);
            
            HCJEditor.showNotification(`Replaced ${matches.length} occurrence(s)`, 'success');
        } else {
            HCJEditor.showNotification(`Not found: ${searchTerm}`, 'warning');
        }
    },
    
    // Setup bracket matching
    setupBracketMatching(type) {
        // Bracket matching functionality
    },
    
    // Setup auto-indentation
    setupAutoIndentation(type) {
        // Auto-indentation functionality
    },
    
    // Update line numbers with syntax highlighting
    updateLineNumbersWithHighlighting(type) {
        // Enhanced line numbers with syntax highlighting
        HCJEditor.updateLineNumbers(type);
    }
};

// Initialize enhanced editor when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    EditorModule.init();
});

// Export for global access
window.EditorModule = EditorModule;
