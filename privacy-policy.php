<?php
require_once __DIR__ . '/config/app.php';

$page_title = 'Privacy Policy';
include __DIR__ . '/includes/header.php';
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white text-center py-4">
                    <h1 class="h2 mb-0">
                        <i class="bi bi-shield-check me-2"></i>
                        Privacy Policy
                    </h1>
                    <p class="mb-0 mt-2 opacity-75">How we collect, use, and protect your information</p>
                </div>
                
                <div class="card-body p-4 p-md-5">
                    <div class="mb-4">
                        <p class="text-muted">
                            <strong>Last updated:</strong> <?php echo date('F j, Y'); ?>
                        </p>
                    </div>

                    <div class="mb-5">
                        <h2 class="h4 text-primary mb-3">1. Introduction</h2>
                        <p>
                            Welcome to <?php echo APP_NAME; ?> ("we," "our," or "us"). This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you use our online code editor service. Please read this privacy policy carefully. If you do not agree with the terms of this privacy policy, please do not access the application.
                        </p>
                    </div>

                    <div class="mb-5">
                        <h2 class="h4 text-primary mb-3">2. Information We Collect</h2>
                        
                        <h3 class="h5 mb-3">Personal Information</h3>
                        <p>We may collect personal information that you voluntarily provide to us when you:</p>
                        <ul class="list-unstyled ms-3">
                            <li><i class="bi bi-check-circle text-success me-2"></i>Register for an account</li>
                            <li><i class="bi bi-check-circle text-success me-2"></i>Use our code editor features</li>
                            <li><i class="bi bi-check-circle text-success me-2"></i>Contact us for support</li>
                        </ul>
                        
                        <p>This information may include:</p>
                        <ul class="list-unstyled ms-3">
                            <li><i class="bi bi-dot"></i>Name and email address</li>
                            <li><i class="bi bi-dot"></i>Account credentials</li>
                            <li><i class="bi bi-dot"></i>Code files and projects you create</li>
                        </ul>

                        <h3 class="h5 mb-3 mt-4">Automatically Collected Information</h3>
                        <p>When you access our service, we may automatically collect certain information, including:</p>
                        <ul class="list-unstyled ms-3">
                            <li><i class="bi bi-dot"></i>IP address and browser information</li>
                            <li><i class="bi bi-dot"></i>Usage data and session information</li>
                            <li><i class="bi bi-dot"></i>Device and operating system information</li>
                        </ul>
                    </div>

                    <div class="mb-5">
                        <h2 class="h4 text-primary mb-3">3. How We Use Your Information</h2>
                        <p>We use the information we collect to:</p>
                        <ul class="list-unstyled ms-3">
                            <li><i class="bi bi-arrow-right text-primary me-2"></i>Provide and maintain our code editor service</li>
                            <li><i class="bi bi-arrow-right text-primary me-2"></i>Process your account registration and authentication</li>
                            <li><i class="bi bi-arrow-right text-primary me-2"></i>Save and manage your code projects</li>
                            <li><i class="bi bi-arrow-right text-primary me-2"></i>Improve our service and user experience</li>
                            <li><i class="bi bi-arrow-right text-primary me-2"></i>Respond to your inquiries and provide customer support</li>
                            <li><i class="bi bi-arrow-right text-primary me-2"></i>Send you technical notices and security alerts</li>
                        </ul>
                    </div>

                    <div class="mb-5">
                        <h2 class="h4 text-primary mb-3">4. Information Sharing and Disclosure</h2>
                        <p>We do not sell, trade, or otherwise transfer your personal information to third parties except in the following circumstances:</p>
                        <ul class="list-unstyled ms-3">
                            <li><i class="bi bi-exclamation-triangle text-warning me-2"></i>With your explicit consent</li>
                            <li><i class="bi bi-exclamation-triangle text-warning me-2"></i>To comply with legal obligations</li>
                            <li><i class="bi bi-exclamation-triangle text-warning me-2"></i>To protect our rights and safety</li>
                            <li><i class="bi bi-exclamation-triangle text-warning me-2"></i>In connection with a business transfer</li>
                        </ul>
                    </div>

                    <div class="mb-5">
                        <h2 class="h4 text-primary mb-3">5. Data Security</h2>
                        <p>
                            We implement appropriate technical and organizational security measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction. However, no method of transmission over the internet or electronic storage is 100% secure.
                        </p>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Security Measures:</strong> We use encryption, secure servers, and regular security audits to protect your data.
                        </div>
                    </div>

                    <div class="mb-5">
                        <h2 class="h4 text-primary mb-3">6. Data Retention</h2>
                        <p>
                            We retain your personal information only for as long as necessary to fulfill the purposes outlined in this Privacy Policy, unless a longer retention period is required or permitted by law. Your code projects are stored securely and can be deleted at any time through your account settings.
                        </p>
                    </div>

                    <div class="mb-5">
                        <h2 class="h4 text-primary mb-3">7. Your Rights</h2>
                        <p>Depending on your location, you may have the following rights regarding your personal information:</p>
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li><i class="bi bi-person-check text-success me-2"></i>Access your data</li>
                                    <li><i class="bi bi-pencil-square text-primary me-2"></i>Correct inaccurate data</li>
                                    <li><i class="bi bi-trash text-danger me-2"></i>Delete your data</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li><i class="bi bi-download text-info me-2"></i>Export your data</li>
                                    <li><i class="bi bi-pause-circle text-warning me-2"></i>Restrict processing</li>
                                    <li><i class="bi bi-x-circle text-secondary me-2"></i>Object to processing</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="mb-5">
                        <h2 class="h4 text-primary mb-3">8. Cookies and Tracking</h2>
                        <p>
                            We use cookies and similar tracking technologies to enhance your experience on our platform. Cookies help us remember your preferences, maintain your session, and analyze how you use our service.
                        </p>
                        <p>You can control cookies through your browser settings, but disabling cookies may affect the functionality of our service.</p>
                    </div>

                    <div class="mb-5">
                        <h2 class="h4 text-primary mb-3">9. Third-Party Services</h2>
                        <p>
                            Our service may contain links to third-party websites or integrate with third-party services. We are not responsible for the privacy practices of these third parties. We encourage you to review their privacy policies before providing any personal information.
                        </p>
                    </div>

                    <div class="mb-5">
                        <h2 class="h4 text-primary mb-3">10. Children's Privacy</h2>
                        <p>
                            Our service is not intended for children under the age of 13. We do not knowingly collect personal information from children under 13. If we become aware that we have collected personal information from a child under 13, we will take steps to delete such information.
                        </p>
                    </div>

                    <div class="mb-5">
                        <h2 class="h4 text-primary mb-3">11. Changes to This Privacy Policy</h2>
                        <p>
                            We may update this Privacy Policy from time to time. We will notify you of any changes by posting the new Privacy Policy on this page and updating the "Last updated" date. You are advised to review this Privacy Policy periodically for any changes.
                        </p>
                    </div>

                    <div class="mb-4">
                        <h2 class="h4 text-primary mb-3">12. Contact Us</h2>
                        <p>If you have any questions about this Privacy Policy, please contact us:</p>
                        <div class="card bg-light border-0 p-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Email:</strong> privacy@hcjcodeeditor.com</p>
                                    <p class="mb-1"><strong>Website:</strong> <?php echo APP_URL; ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Application:</strong> <?php echo APP_NAME; ?></p>
                                    <p class="mb-1"><strong>Version:</strong> <?php echo APP_VERSION; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-5">
                        <a href="<?php echo APP_URL; ?>" class="btn btn-primary">
                            <i class="bi bi-house me-2"></i>
                            Back to Home
                        </a>
                        <a href="terms-conditions.php" class="btn btn-outline-secondary ms-2">
                            <i class="bi bi-file-text me-2"></i>
                            Terms & Conditions
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
