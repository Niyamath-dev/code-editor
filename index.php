<?php
require_once __DIR__ . '/config/app.php';

// Check if user is logged in
requireAuth();

$page_title = 'Code Editor';
$additional_css = ['assets/css/main.css'];
$additional_js = [
    'assets/js/app.js',
    'assets/js/editor.js',
    'assets/js/preview-controls.js',
    'assets/js/download-manager.js',
    'assets/js/main-page.js'
];

include __DIR__ . '/includes/header.php';
?>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg glassmorphism">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <i class="bi bi-code-slash"></i>
            <?php echo APP_NAME; ?>
        </a>

        <div class="navbar-nav ms-auto">
            <div class="nav-item dropdown">
                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle me-2"></i>
                    <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><h6 class="dropdown-header">Welcome back!</h6></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="#" onclick="EditorModule.showPreferences()">
                        <i class="bi bi-gear"></i> Preferences
                    </a></li>
                    <li><a class="dropdown-item" href="#" onclick="EditorModule.showKeyboardShortcuts()">
                        <i class="bi bi-keyboard"></i> Keyboard Shortcuts
                    </a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="auth/logout.php" method="POST" class="d-inline">
                            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                            <button type="submit" class="dropdown-item text-danger" 
                                    onclick="return confirm('Are you sure you want to logout?');">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<!-- Main Content -->
<main class="main-content">
    <div class="container">
        <!-- Welcome Section -->
        <div class="text-center mb-4">
            <h1 class="display-6 fw-bold">Professional Code Editor</h1>
            <p class="lead text-muted">Create, edit, and preview your HTML, CSS, and JavaScript projects in real-time</p>
        </div>

        <!-- Code Editor Section -->
        <div class="editor-container">
            <div class="row g-3">
                <!-- HTML Editor -->
                <div class="col-md-4">
                    <div class="editor-panel">
                        <div class="editor-header">
                            <div class="editor-title">
                                <img src="img/html.png" alt="HTML" width="20"> HTML
                            </div>
                            <div class="editor-controls">
                                <button class="editor-control-btn" onclick="copyToClipboard(document.getElementById('htmlMobile').value, 'HTML code copied!')" title="Copy HTML">
                                    <i class="bi bi-clipboard"></i>
                                </button>
                                <button class="editor-control-btn" onclick="EditorModule.formatCode('html')" title="Format HTML">
                                    <i class="bi bi-code"></i>
                                </button>
                                <button class="editor-control-btn" onclick="clearEditor('html')" title="Clear HTML">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                        <div class="code-editor-wrapper">
                            <textarea id="lineCounterHtmlMobi" class="line-numbers" readonly>1.</textarea>
                            <textarea id="htmlMobile" class="code-editor" placeholder="Enter your HTML code here..." spellcheck="false"></textarea>
                        </div>
                        <div class="download-section">
                            <button class="btn btn-primary btn-sm" onclick="downloadFile(document.getElementById('htmlMobile').value, 'index.html', 'text/html')">
                                <i class="bi bi-download"></i> Download HTML
                            </button>
                        </div>
                    </div>
                </div>

                <!-- CSS Editor -->
                <div class="col-md-4">
                    <div class="editor-panel">
                        <div class="editor-header">
                            <div class="editor-title">
                                <img src="img/css.png" alt="CSS" width="20"> CSS
                            </div>
                            <div class="editor-controls">
                                <button class="editor-control-btn" onclick="copyToClipboard(document.getElementById('cssMobile').value, 'CSS code copied!')" title="Copy CSS">
                                    <i class="bi bi-clipboard"></i>
                                </button>
                                <button class="editor-control-btn" onclick="EditorModule.formatCode('css')" title="Format CSS">
                                    <i class="bi bi-code"></i>
                                </button>
                                <button class="editor-control-btn" onclick="clearEditor('css')" title="Clear CSS">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                        <div class="code-editor-wrapper">
                            <textarea id="lineCounterCssMobi" class="line-numbers" readonly>1.</textarea>
                            <textarea id="cssMobile" class="code-editor" placeholder="Enter your CSS code here..." spellcheck="false"></textarea>
                        </div>
                        <div class="download-section">
                            <button class="btn btn-primary btn-sm" onclick="downloadFile(document.getElementById('cssMobile').value, 'style.css', 'text/css')">
                                <i class="bi bi-download"></i> Download CSS
                            </button>
                        </div>
                    </div>
                </div>

                <!-- JavaScript Editor -->
                <div class="col-md-4">
                    <div class="editor-panel">
                        <div class="editor-header">
                            <div class="editor-title">
                                <img src="img/js.png" alt="JavaScript" width="20"> JavaScript
                            </div>
                            <div class="editor-controls">
                                <button class="editor-control-btn" onclick="copyToClipboard(document.getElementById('jsMobile').value, 'JavaScript code copied!')" title="Copy JavaScript">
                                    <i class="bi bi-clipboard"></i>
                                </button>
                                <button class="editor-control-btn" onclick="EditorModule.formatCode('js')" title="Format JavaScript">
                                    <i class="bi bi-code"></i>
                                </button>
                                <button class="editor-control-btn" onclick="clearEditor('js')" title="Clear JavaScript">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                        <div class="code-editor-wrapper">
                            <textarea id="lineCounterJsMobi" class="line-numbers" readonly>1.</textarea>
                            <textarea id="jsMobile" class="code-editor" placeholder="Enter your JavaScript code here..." spellcheck="false"></textarea>
                        </div>
                        <div class="download-section">
                            <button class="btn btn-primary btn-sm" onclick="downloadFile(document.getElementById('jsMobile').value, 'script.js', 'text/javascript')">
                                <i class="bi bi-download"></i> Download JS
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Live Preview Section -->
        <div class="preview-container">
            <div class="preview-header">
                <div class="preview-header-left">
                    <div class="preview-controls">
                        <button class="preview-btn refresh-btn" title="Refresh Preview">
                            <i class="bi bi-arrow-clockwise"></i>
                        </button>
                        <div class="device-selector">
                            <button class="preview-btn device-btn active" data-device="desktop" title="Desktop View">
                                <i class="bi bi-display"></i>
                            </button>
                            <button class="preview-btn device-btn" data-device="ipad" title="iPad View">
                                <i class="bi bi-tablet"></i>
                            </button>
                            <button class="preview-btn device-btn" data-device="iphone-12" title="iPhone View">
                                <i class="bi bi-phone"></i>
                            </button>
                        </div>
                        
                        <!-- Orientation Toggle -->
                        <div class="orientation-controls">
                            <button class="preview-btn orientation-btn" title="Toggle Orientation">
                                <i class="bi bi-phone"></i>
                            </button>
                        </div>
                        
                        <!-- Zoom Controls -->
                        <div class="zoom-controls">
                            <button class="preview-btn zoom-out-btn" title="Zoom Out">
                                <i class="bi bi-zoom-out"></i>
                            </button>
                            <span class="zoom-display">100%</span>
                            <button class="preview-btn zoom-in-btn" title="Zoom In">
                                <i class="bi bi-zoom-in"></i>
                            </button>
                            <button class="preview-btn zoom-reset-btn" title="Reset Zoom">
                                <i class="bi bi-arrow-clockwise"></i>
                            </button>
                        </div>
                        
                        <!-- Custom Viewport -->
                        <div class="custom-viewport-controls">
                            <input type="number" class="custom-width-input" placeholder="W" min="200" max="4000" title="Custom Width">
                            <span style="color: rgba(255,255,255,0.6);">×</span>
                            <input type="number" class="custom-height-input" placeholder="H" min="200" max="3000" title="Custom Height">
                            <button class="preview-btn apply-custom-btn" title="Apply Custom Size">
                                <i class="bi bi-check"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="preview-header-center">
                    <span class="live-preview-text">Live Preview</span>
                </div>
                
                <div class="preview-header-right">
                    <div class="preview-info">
                        <span class="viewport-size">1920 x 1080</span>
                        <div class="breakpoint-indicator">
                            <span class="breakpoint-name">Large</span>
                        </div>
                    </div>
                    
                    <!-- Additional Controls -->
                    <div class="additional-controls">
                        <button class="preview-btn" onclick="PreviewControls.toggleFullscreen()" title="Fullscreen">
                            <i class="bi bi-fullscreen"></i>
                        </button>
                        <button class="preview-btn" onclick="PreviewControls.openInNewWindow()" title="Open in New Window">
                            <i class="bi bi-box-arrow-up-right"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="preview-content">
                <div class="preview-loading">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <iframe class="preview-frame desktop" id="codeMobile" title="Live Preview"></iframe>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-download display-6 text-primary mb-3"></i>
                        <h5 class="card-title">Download Project</h5>
                        <p class="card-text">Download all your files as a complete project package.</p>
                        <button class="btn btn-primary" onclick="DownloadManager.downloadProject('zip')">
                            <i class="bi bi-file-earmark-zip"></i> Download ZIP
                        </button>
                    </div>
                </div>
            </div>
            <!-- <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-share display-6 text-success mb-3"></i>
                        <h5 class="card-title">Share Project</h5>
                        <p class="card-text">Generate a shareable link for your project.</p>
                        <button class="btn btn-success" onclick="shareProject()">
                            <i class="bi bi-link-45deg"></i> Share
                        </button>
                    </div>
                </div>
            </div> -->
            <!-- Save Project feature removed as per request
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-save display-6 text-warning mb-3"></i>
                        <h5 class="card-title">Save Project</h5>
                        <p class="card-text">Save your project to continue working later.</p>
                        <button class="btn btn-warning" onclick="saveProject()">
                            <i class="bi bi-cloud-upload"></i> Save
                        </button>
                    </div>
                </div>
            </div>
            -->
        </div>

        <!-- <div class="mt-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-keyboard"></i> Keyboard Shortcuts
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <small class="text-muted">
                                <strong>Ctrl+S</strong> - Save project<br>
                                <strong>Ctrl+R</strong> - Refresh preview<br>
                                <strong>Ctrl+/</strong> - Toggle comment<br>
                                <strong>Ctrl+Z</strong> - Undo<br>
                                <strong>Ctrl+Y</strong> - Redo
                            </small>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">
                                <strong>Ctrl+F</strong> - Find<br>
                                <strong>Ctrl+H</strong> - Replace<br>
                                <strong>Ctrl+L</strong> - Go to line<br>
                                <strong>Ctrl+D</strong> - Duplicate line<br>
                                <strong>Tab</strong> - Indent
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
    </div>
</main>

<?php
include __DIR__ . '/includes/footer.php';
?>
