<?php
// Simple test version without authentication
session_start();
$_SESSION['user_id'] = 1;
$_SESSION['user_name'] = 'Test User';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Code Editor - Test</title>
    <link rel="stylesheet" href="bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</head>

<body>
    <div class="container-fluid mt-70">
        <div class="row">
            <div class="col-md-12 pdr">
                <div class="mytabs">
                    <input type="radio" id="tabfree" name="mytabs" checked="checked">
                    <label for="tabfree">HTML</label>
                    <div class="tab">
                        <div class="row">
                            <div class="col-md-6 width-70 pr-0">
                                <div style="position: relative;">
                                    <textarea id="lineCounterHtmlMobi" class="h-txtarea" readonly>1.</textarea>
                                    <img src="img/html.png" width="30" height="30" class="img-cls1">
                                    <img src="img/189264.png" width="20" height="20" class="img-cls1-1" onclick="txtCopyMobile()">
                                    <textarea id="htmlMobile" class="html-txtareaMobi h-txtarea" placeholder="Write HTML code here..."></textarea>
                                </div>
                                <div class="d-no-mobile">
                                    <button class="btn-all mar-r" onclick="clearTextareaMobile()">Clear</button>
                                    <button class="btn-all mar-r" onclick="txtCopyMobile()">Copy</button>
                                    <button class="btn-all mar-r" id="btn-html-plainHtml">Download as Text</button>
                                    <button class="btn-all" id="btn-html-codeHtml">Download as HTML</button>
                                </div>
                            </div>
                            <div class="col-md-6 width-70">
                                <div class="preview-container">
                                    <div class="preview-header">
                                        <div class="preview-info">
                                            <span class="live-preview-text">Live Preview</span>
                                            <span class="viewport-size" id="viewport-size">900 x 600</span>
                                        </div>
                                        <div class="preview-controls">
                                            <div class="device-selector">
                                                <button class="preview-btn active" data-device="desktop" title="Desktop View">🖥️</button>
                                                <button class="preview-btn" data-device="tablet" title="Tablet View">📱</button>
                                                <button class="preview-btn" data-device="mobile" title="Mobile View">📱</button>
                                            </div>
                                            <button class="preview-btn" id="refresh-preview" title="Refresh Preview">🔄</button>
                                        </div>
                                    </div>
                                    <div class="preview-content">
                                        <div class="preview-loading">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                        <iframe id="codeMobile" class="preview-frame desktop"></iframe>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <input type="radio" id="tabsilver" name="mytabs">
                    <label for="tabsilver">CSS</label>
                    <div class="tab">
                        <div class="row">
                            <div class="col-md-6 width-70 pr-0">
                                <div style="position: relative;">
                                    <textarea id="lineCounterCssMobi" class="h-txtarea" readonly>1.</textarea>
                                    <img src="img/css.png" width="30" height="30" class="img-cls2">
                                    <img src="img/189264.png" width="20" height="20" class="img-cls2-2" onclick="txtCopyMobile2()">
                                    <textarea id="cssMobile" class="css-txtareaMobi h-txtarea" placeholder="Write CSS code here..."></textarea>
                                </div>
                                <div class="d-no-mobile">
                                    <button class="btn-all mar-r" onclick="clearTextMobilecss()">Clear</button>
                                    <button class="btn-all mar-r" onclick="txtCopyMobile2()">Copy</button>
                                    <button class="btn-all mar-r" id="btn-css-plainCss">Download as Text</button>
                                    <button class="btn-all" id="btn-css-codeCss">Download as CSS</button>
                                </div>
                            </div>
                            <div class="col-md-6 width-70">
                                <div class="preview-container">
                                    <div class="preview-header">
                                        <div class="preview-info">
                                            <span class="live-preview-text">Live Preview</span>
                                            <span class="viewport-size">900 x 600</span>
                                        </div>
                                        <div class="preview-controls">
                                            <div class="device-selector">
                                                <button class="preview-btn active" data-device="desktop">🖥️</button>
                                                <button class="preview-btn" data-device="tablet">📱</button>
                                                <button class="preview-btn" data-device="mobile">📱</button>
                                            </div>
                                            <button class="preview-btn" id="refresh-preview-css">🔄</button>
                                        </div>
                                    </div>
                                    <div class="preview-content">
                                        <iframe id="codeMobile" class="preview-frame desktop"></iframe>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <input type="radio" id="tabgold" name="mytabs">
                    <label for="tabgold">JavaScript</label>
                    <div class="tab">
                        <div class="row">
                            <div class="col-md-6 width-70 pr-0">
                                <div style="position: relative;">
                                    <textarea id="lineCounterJsMobi" class="h-txtarea" readonly>1.</textarea>
                                    <img src="img/js.png" width="30" height="30" class="img-cls3">
                                    <img src="img/189264.png" width="20" height="20" class="img-cls3-3" onclick="txtCopyMobile3()">
                                    <textarea id="jsMobile" class="js-txtareaMobi h-txtarea" placeholder="Write JavaScript code here..."></textarea>
                                </div>
                                <div class="d-no-mobile">
                                    <button class="btn-all mar-r" onclick="clearTextMobilejs()">Clear</button>
                                    <button class="btn-all mar-r" onclick="txtCopyMobile3()">Copy</button>
                                    <button class="btn-all mar-r" id="btn-js-plainJs">Download as Text</button>
                                    <button class="btn-all" id="btn-js-codeJs">Download as JS</button>
                                </div>
                            </div>
                            <div class="col-md-6 width-70">
                                <div class="preview-container">
                                    <div class="preview-header">
                                        <div class="preview-info">
                                            <span class="live-preview-text">Live Preview</span>
                                            <span class="viewport-size">900 x 600</span>
                                        </div>
                                        <div class="preview-controls">
                                            <div class="device-selector">
                                                <button class="preview-btn active" data-device="desktop">🖥️</button>
                                                <button class="preview-btn" data-device="tablet">📱</button>
                                                <button class="preview-btn" data-device="mobile">📱</button>
                                            </div>
                                            <button class="preview-btn" id="refresh-preview-js">🔄</button>
                                        </div>
                                    </div>
                                    <div class="preview-content">
                                        <iframe id="codeMobile" class="preview-frame desktop"></iframe>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <input type="radio" id="tabpython" name="mytabs">
                    <label for="tabpython">Python</label>
                    <div class="tab">
                        <div class="row">
                            <div class="col-md-6 width-70 pr-0">
                                <div style="position: relative;">
                                    <textarea id="lineCounterPythonMobi" class="h-txtarea" readonly>1.</textarea>
                                    <img src="img/python.png" width="30" height="30" class="img-cls4">
                                    <img src="img/189264.png" width="20" height="20" class="img-cls4-4" onclick="txtCopyMobile4()">
                                    <textarea id="pythonMobile" class="python-txtareaMobi h-txtarea" placeholder="Write Python code here..."></textarea>
                                </div>
                                <div class="d-no-mobile">
                                    <button class="btn-all mar-r" onclick="clearTextMobilePython()">Clear</button>
                                    <button class="btn-all mar-r" onclick="txtCopyMobile4()">Copy</button>
                                    <button class="btn-all mar-r" id="btn-python-plainPython">Download as Text</button>
                                    <button class="btn-all" id="btn-python-codePython">Download as Python</button>
                                </div>
                            </div>
                            <div class="col-md-6 width-70">
                                <div class="preview-container">
                                    <div class="preview-header">
                                        <div class="preview-info">
                                            <span class="live-preview-text">Python Code</span>
                                            <span class="viewport-size">Code Display</span>
                                        </div>
                                    </div>
                                    <div class="preview-content">
                                        <div class="python-code" id="pythonPreview">Your Python code will appear here...</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <input type="radio" id="tabphp" name="mytabs">
                    <label for="tabphp">PHP</label>
                    <div class="tab">
                        <div class="row">
                            <div class="col-md-6 width-70 pr-0">
                                <div style="position: relative;">
                                    <textarea id="lineCounterPhpMobi" class="h-txtarea" readonly>1.</textarea>
                                    <img src="img/php.png" width="30" height="30" class="img-cls5">
                                    <img src="img/189264.png" width="20" height="20" class="img-cls5-5" onclick="txtCopyMobile5()">
                                    <textarea id="phpMobile" class="php-txtareaMobi h-txtarea" placeholder="Write PHP code here..."></textarea>
                                </div>
                                <div class="d-no-mobile">
                                    <button class="btn-all mar-r" onclick="clearTextMobilePhp()">Clear</button>
                                    <button class="btn-all mar-r" onclick="txtCopyMobile5()">Copy</button>
                                    <button class="btn-all mar-r" id="btn-php-plainPhp">Download as Text</button>
                                    <button class="btn-all" id="btn-php-codePhp">Download as PHP</button>
                                </div>
                            </div>
                            <div class="col-md-6 width-70">
                                <div class="preview-container">
                                    <div class="preview-header">
                                        <div class="preview-info">
                                            <span class="live-preview-text">PHP Code</span>
                                            <span class="viewport-size">Code Display</span>
                                        </div>
                                    </div>
                                    <div class="preview-content">
                                        <div class="php-code" id="phpPreview">Your PHP code will appear here...</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="bootstrap-5.3.3-dist/js/bootstrap.min.js"></script>
    <script src="mobile-app.js"></script>
    <script src="lineMobi.js"></script>
    <script src="preview-controls.js"></script>
    <script>
        // Add Python and PHP preview functionality
        document.getElementById('pythonMobile').addEventListener('input', function() {
            document.getElementById('pythonPreview').textContent = this.value || 'Your Python code will appear here...';
        });
        
        document.getElementById('phpMobile').addEventListener('input', function() {
            document.getElementById('phpPreview').textContent = this.value || 'Your PHP code will appear here...';
        });
    </script>
</body>

</html>
