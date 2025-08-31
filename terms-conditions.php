<?php
require_once __DIR__ . '/config/app.php';

$page_title = 'Terms & Conditions';
include __DIR__ . '/includes/header.php';
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-success text-white text-center py-4">
                    <h1 class="h2 mb-0">
                        <i class="bi bi-file-text me-2"></i>
                        Terms & Conditions
                    </h1>
                    <p class="mb-0 mt-2 opacity-75">Terms of use for <?php echo APP_NAME; ?></p>
                </div>
                
                <div class="card-body p-4 p-md-5">
                    <div class="mb-4">
                        <p class="text-muted">
                            <strong>Last updated:</strong> <?php echo date('F j, Y'); ?>
                        </p>
                    </div>

                    <div class="mb-5">
                        <h2 class="h4 text-success mb-3">1. Acceptance of Terms</h2>
                        <p>
                            By accessing and using <?php echo APP_NAME; ?> ("the Service"), you accept and agree to be bound by the terms and provision of this agreement. If you do not agree to abide by the above, please do not use this service.
                        </p>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Important:</strong> These terms constitute a legally binding agreement between you and <?php echo APP_NAME; ?>.
                        </div>
                    </div>

                    <div class="mb-5">
                        <h2 class="h4 text-success mb-3">2. Description of Service</h2>
                        <p><?php echo APP_NAME; ?> is a web-based code editor that allows users to:</p>
                        <ul class="list-unstyled ms-3">
                            <li><i class="bi bi-code-slash text-primary me-2"></i>Write and edit HTML, CSS, and JavaScript code</li>
                            <li><i class="bi bi-eye text-primary me-2"></i>Preview code in real-time</li>
                            <li><i class="bi bi-download text-primary me-2"></i>Download code files and projects</li>
                            <li><i class="bi bi-cloud text-primary me-2"></i>Save and manage code projects online</li>
                            <li><i class="bi bi-share text-primary me-2"></i>Share code snippets and projects</li>
                        </ul>
                    </div>

                    <div class="mb-5">
                        <h2 class="h4 text-success mb-3">3. User Accounts</h2>
                        
                        <h3 class="h5 mb-3">Account Registration</h3>
                        <p>To access certain features of the Service, you must register for an account. You agree to:</p>
                        <ul class="list-unstyled ms-3">
                            <li><i class="bi bi-check-circle text-success me-2"></i>Provide accurate and complete information</li>
                            <li><i class="bi bi-check-circle text-success me-2"></i>Maintain the security of your account credentials</li>
                            <li><i class="bi bi-check-circle text-success me-2"></i>Update your information as necessary</li>
                            <li><i class="bi bi-check-circle text-success me-2"></i>Be responsible for all activities under your account</li>
                        </ul>

                        <h3 class="h5 mb-3 mt-4">Account Termination</h3>
                        <p>We reserve the right to terminate or suspend your account at any time for violations of these terms or for any other reason at our sole discretion.</p>
                    </div>

                    <div class="mb-5">
                        <h2 class="h4 text-success mb-3">4. Acceptable Use Policy</h2>
                        <p>You agree not to use the Service to:</p>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <h3 class="h6 text-danger mb-2">Prohibited Activities</h3>
                                <ul class="list-unstyled ms-2">
                                    <li><i class="bi bi-x-circle text-danger me-2"></i>Upload malicious code or viruses</li>
                                    <li><i class="bi bi-x-circle text-danger me-2"></i>Violate any laws or regulations</li>
                                    <li><i class="bi bi-x-circle text-danger me-2"></i>Infringe on intellectual property rights</li>
                                    <li><i class="bi bi-x-circle text-danger me-2"></i>Harass or harm other users</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h3 class="h6 text-danger mb-2">Technical Restrictions</h3>
                                <ul class="list-unstyled ms-2">
                                    <li><i class="bi bi-x-circle text-danger me-2"></i>Attempt to hack or breach security</li>
                                    <li><i class="bi bi-x-circle text-danger me-2"></i>Overload or disrupt our servers</li>
                                    <li><i class="bi bi-x-circle text-danger me-2"></i>Reverse engineer the Service</li>
                                    <li><i class="bi bi-x-circle text-danger me-2"></i>Use automated tools to access the Service</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="mb-5">
                        <h2 class="h4 text-success mb-3">5. Intellectual Property Rights</h2>
                        
                        <h3 class="h5 mb-3">Your Content</h3>
                        <p>You retain ownership of any code, text, or other content you create using our Service. By using the Service, you grant us a limited license to:</p>
                        <ul class="list-unstyled ms-3">
                            <li><i class="bi bi-arrow-right text-primary me-2"></i>Store and display your content</li>
                            <li><i class="bi bi-arrow-right text-primary me-2"></i>Provide the Service to you</li>
                            <li><i class="bi bi-arrow-right text-primary me-2"></i>Make backups for security purposes</li>
                        </ul>

                        <h3 class="h5 mb-3 mt-4">Our Service</h3>
                        <p>The Service, including its design, functionality, and underlying technology, is owned by us and protected by intellectual property laws. You may not copy, modify, or distribute our Service without permission.</p>
                    </div>

                    <div class="mb-5">
                        <h2 class="h4 text-success mb-3">6. Privacy and Data Protection</h2>
                        <p>
                            Your privacy is important to us. Our collection and use of personal information is governed by our Privacy Policy, which is incorporated into these Terms by reference.
                        </p>
                        <div class="card bg-light border-0 p-3">
                            <p class="mb-0">
                                <i class="bi bi-shield-check text-success me-2"></i>
                                Please review our <a href="privacy-policy.php" class="text-decoration-none">Privacy Policy</a> to understand how we collect, use, and protect your information.
                            </p>
                        </div>
                    </div>

                    <div class="mb-5">
                        <h2 class="h4 text-success mb-3">7. Service Availability</h2>
                        <p>We strive to provide reliable service, but we cannot guarantee:</p>
                        <ul class="list-unstyled ms-3">
                            <li><i class="bi bi-exclamation-triangle text-warning me-2"></i>100% uptime or availability</li>
                            <li><i class="bi bi-exclamation-triangle text-warning me-2"></i>Error-free operation</li>
                            <li><i class="bi bi-exclamation-triangle text-warning me-2"></i>Compatibility with all devices or browsers</li>
                            <li><i class="bi bi-exclamation-triangle text-warning me-2"></i>Permanent storage of your content</li>
                        </ul>
                        <p class="mt-3">We recommend regularly backing up your important code and projects.</p>
                    </div>

                    <div class="mb-5">
                        <h2 class="h4 text-success mb-3">8. Limitation of Liability</h2>
                        <p>
                            To the maximum extent permitted by law, <?php echo APP_NAME; ?> and its operators shall not be liable for any indirect, incidental, special, consequential, or punitive damages, including but not limited to:
                        </p>
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li><i class="bi bi-dot"></i>Loss of data or code</li>
                                    <li><i class="bi bi-dot"></i>Loss of profits or revenue</li>
                                    <li><i class="bi bi-dot"></i>Business interruption</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li><i class="bi bi-dot"></i>System downtime</li>
                                    <li><i class="bi bi-dot"></i>Security breaches</li>
                                    <li><i class="bi bi-dot"></i>Third-party actions</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="mb-5">
                        <h2 class="h4 text-success mb-3">9. Indemnification</h2>
                        <p>
                            You agree to indemnify and hold harmless <?php echo APP_NAME; ?>, its operators, and affiliates from any claims, damages, or expenses arising from your use of the Service or violation of these Terms.
                        </p>
                    </div>

                    <div class="mb-5">
                        <h2 class="h4 text-success mb-3">10. Modifications to Terms</h2>
                        <p>
                            We reserve the right to modify these Terms at any time. We will notify users of significant changes by:
                        </p>
                        <ul class="list-unstyled ms-3">
                            <li><i class="bi bi-bell text-info me-2"></i>Posting a notice on our website</li>
                            <li><i class="bi bi-envelope text-info me-2"></i>Sending an email notification</li>
                            <li><i class="bi bi-calendar text-info me-2"></i>Updating the "Last updated" date</li>
                        </ul>
                        <p>Continued use of the Service after changes constitutes acceptance of the new Terms.</p>
                    </div>

                    <div class="mb-5">
                        <h2 class="h4 text-success mb-3">11. Termination</h2>
                        <p>
                            Either party may terminate this agreement at any time. Upon termination:
                        </p>
                        <ul class="list-unstyled ms-3">
                            <li><i class="bi bi-arrow-right text-primary me-2"></i>Your access to the Service will be discontinued</li>
                            <li><i class="bi bi-arrow-right text-primary me-2"></i>You may download your content before termination</li>
                            <li><i class="bi bi-arrow-right text-primary me-2"></i>We may delete your account and content after a reasonable period</li>
                        </ul>
                    </div>

                    <div class="mb-5">
                        <h2 class="h4 text-success mb-3">12. Governing Law</h2>
                        <p>
                            These Terms shall be governed by and construed in accordance with the laws of the jurisdiction where <?php echo APP_NAME; ?> operates, without regard to conflict of law principles.
                        </p>
                    </div>

                    <div class="mb-5">
                        <h2 class="h4 text-success mb-3">13. Severability</h2>
                        <p>
                            If any provision of these Terms is found to be unenforceable or invalid, the remaining provisions will continue to be valid and enforceable to the fullest extent permitted by law.
                        </p>
                    </div>

                    <div class="mb-4">
                        <h2 class="h4 text-success mb-3">14. Contact Information</h2>
                        <p>If you have any questions about these Terms & Conditions, please contact us:</p>
                        <div class="card bg-light border-0 p-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Email:</strong> legal@hcjcodeeditor.com</p>
                                    <p class="mb-1"><strong>Support:</strong> support@hcjcodeeditor.com</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Website:</strong> <?php echo APP_URL; ?></p>
                                    <p class="mb-1"><strong>Version:</strong> <?php echo APP_VERSION; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Important:</strong> By using <?php echo APP_NAME; ?>, you acknowledge that you have read, understood, and agree to be bound by these Terms & Conditions.
                    </div>

                    <div class="text-center mt-5">
                        <a href="<?php echo APP_URL; ?>" class="btn btn-success">
                            <i class="bi bi-house me-2"></i>
                            Back to Home
                        </a>
                        <a href="privacy-policy.php" class="btn btn-outline-primary ms-2">
                            <i class="bi bi-shield-check me-2"></i>
                            Privacy Policy
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
