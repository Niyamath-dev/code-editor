<?php
require_once __DIR__ . '/../config/app.php';
$flash = getFlashMessage();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Professional HTML, CSS, and JavaScript code editor with live preview. Create, edit, and download your code files effortlessly.">
    <meta name="keywords" content="HTML editor, CSS editor, JavaScript editor, free code editor, download code files, live preview">
    <meta name="author" content="HCJ Code Editor Team">
    <title><?php echo isset($page_title) ? $page_title . ' - ' . APP_NAME : APP_NAME; ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo APP_URL; ?>/assets/img/favicon.ico">
    
    <!-- Stylesheets -->
    <?php
    $current_file = basename($_SERVER['PHP_SELF']);
    $base_path = (in_array($current_file, ['index.php', 'privacy-policy.php', 'terms-conditions.php']) && dirname($_SERVER['PHP_SELF']) === '/code-editor') ? '' : '../';
    ?>
    <link rel="stylesheet" href="<?php echo $base_path; ?>bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo $base_path; ?>cdnjs/sweetalert.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?php echo $base_path; ?>assets/css/main.css">
    
    <!-- Font Style -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Additional CSS -->
    <?php if (isset($additional_css)): ?>
        <?php foreach ($additional_css as $css): ?>
            <link rel="stylesheet" href="<?php echo $css; ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>
    <!-- Flash Messages -->
    <?php if ($flash): ?>
        <div class="alert alert-<?php echo $flash['type'] === 'error' ? 'danger' : $flash['type']; ?> alert-dismissible fade show flash-message" role="alert">
            <i class="bi bi-<?php echo $flash['type'] === 'error' ? 'exclamation-triangle' : ($flash['type'] === 'success' ? 'check-circle' : 'info-circle'); ?>"></i>
            <?php echo htmlspecialchars($flash['message']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <!-- CSRF Token for JavaScript -->
    <meta name="csrf-token" content="<?php echo generateCSRFToken(); ?>">
    
    <!-- JSZip for ZIP file creation -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
