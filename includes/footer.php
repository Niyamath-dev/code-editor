<?php
// Check if we're on an authentication page
$current_file = basename($_SERVER['PHP_SELF']);
$is_auth_page = in_array($current_file, ['login.php', 'signup.php', 'forgot-password.php']);

// Only show footer if not on auth pages
if (!$is_auth_page): ?>
<!-- Footer -->
    <footer class="app-footer">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="footer-copyright">
                        &copy; <?php echo date('Y'); ?> <?php echo APP_NAME; ?> v<?php echo APP_VERSION; ?>
                        <!-- Developed with <i class="bi bi-heart-fill text-danger"></i> by 
                        <a href="#" class="footer-link">HCJ Team</a> -->
                    </div>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="footer-links">
                        <a href="<?php echo $base_path; ?>privacy-policy.php" class="footer-link">Privacy Policy</a>
                        <span class="separator">|</span>
                        <a href="<?php echo $base_path; ?>terms-conditions.php" class="footer-link">Terms & Conditions</a>
                        <span class="separator">|</span>
                        <a href="mailto:support@hcjcodeeditor.com" class="footer-link">Support</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
<?php endif; ?>

    <!-- Scripts -->
    <?php
    $current_file = basename($_SERVER['PHP_SELF']);
    $base_path = (in_array($current_file, ['index.php', 'landing.php', 'privacy-policy.php', 'terms-conditions.php']) && dirname($_SERVER['PHP_SELF']) === '/code-editor') ? '' : '../';
    ?>
    <script src="<?php echo $base_path; ?>bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo $base_path; ?>cdnjs/sweetalert.min.js"></script>
    
    <!-- Global JavaScript -->
    <script src="<?php echo $base_path; ?>assets/js/app.js"></script>
    
    <!-- Additional JavaScript -->
    <?php if (isset($additional_js)): ?>
        <?php foreach ($additional_js as $js): ?>
            <script src="<?php echo $js; ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <!-- Inline JavaScript -->
    <?php if (isset($inline_js)): ?>
        <script>
            <?php echo $inline_js; ?>
        </script>
    <?php endif; ?>
</body>
</html>
