<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
  header("Location: login.html"); // Redirect to login if not logged in
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description"
    content="HTML, CSS, and JavaScript free code editor for developers. Create, edit, and download your code files effortlessly.">
  <meta name="keywords" content="HTML editor, CSS editor, JavaScript editor, free code editor, download code files">
  <meta name="author" content="MN Shariff">
  <title>HTML, CSS, and JavaScript Free Code Tester</title>

  <!-- Stylesheets -->
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="bootstrap-5.3.3-dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="cdnjs/sweetalert.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css">

  <!-- Font Style -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,100..900;1,100..900&display=swap"
    rel="stylesheet">
    
</head>

<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg bg-body-white glassmorphism">
    <div class="container">
      <a class="navbar-brand" href="#">HCJ Code Tester</a>

      <div class="justify-content-end" id="navbarSupportedContent">
        <form action="logout.php" method="POST">
          <button type="submit" class="btn btn-danger custom-violetbtn"
            onclick="return confirm('Are you sure you want to logout?');">Logout</button>
        </form>
      </div>
    </div>
  </nav>
  <!-- Main Section -->
  <section class="row justify-content-center mt-70">

    <div class="container text-center my-2">
      <div class="row align-items-center">
        <div class="col">
        </div>
        <div class="col">
          <h1>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h1>
          <p>You are now logged in.</p>
        </div>
        <div class="col">
        </div>
      </div>
    </div>

    <div class="pb-4 pr-0">
      <h1 class="text-center">HTML, CSS, JavaScript Free Code Tester</h1>
    </div>

    <div class="mytabs width-70">
      <!-- HTML Tab -->
      <input type="radio" id="tabHtml" name="mytabs" checked>
      <label for="tabHtml">
        <img src="img/html.png" alt="HTML Icon" width="20px"> HTML
      </label>
      <div class="tab">
        <div class="text-end">
          <img class="img-cls1" onclick="txtCopyMobile()" src="img/1621635.png" alt="Copy Button" width="20px">
        </div>
        <div>
          <textarea id="lineCounterHtmlMobi" wrap="off" readonly>1.</textarea>
          <div>
            <textarea class="html-txtareaMobi form-control h-txtarea" id="htmlMobile" placeholder="HTML" draggable="false"></textarea>
          </div>
        </div>
        <div class="col text-end">
          <img id="click-me" class="img-cls1-1" onclick="clearTextareaMobile()" src="img/189264.png"
            alt="Refresh Button" width="30px">
        </div>
        <div class="justify-content-around d-no-mobile">
          <button class="btn-all custom-violetbtn" id="btn-html-codeHtml">Download as HTML File</button>
          <p class="mb-0">OR</p>
          <button class="btn-p-t1 btn-all custom-violetbtn" id="btn-html-plainHtml">Download as Plain Text</button>
        </div>
      </div>

      <!-- CSS Tab -->
      <input type="radio" id="tabCss" name="mytabs">
      <label for="tabCss">
        <img src="img/css.png" alt="CSS Icon" width="20px"> CSS
      </label>
      <div class="tab">
        <div class="text-end">
          <img class="img-cls2" onclick="txtCopyMobile2()" src="img/1621635.png" alt="Copy Button" width="20px">
        </div>
        <div>
          <textarea id="lineCounterCssMobi" wrap="off" readonly>1.</textarea>
          <div>
            <textarea class="css-txtareaMobi form-control h-txtarea" id="cssMobile" placeholder="CSS" draggable="false"></textarea>
          </div>
        </div>
        <div class="col text-end">
          <img id="btn2" class="img-cls2-2" onclick="clearTextMobilecss()" src="img/189264.png" alt="Refresh Button"
            width="30px">
        </div>
        <div class="justify-content-around d-no-mobile">
          <button class="btn-all custom-violetbtn" id="btn-css-codeCss">Download as CSS File</button>
          <p class="mb-0">OR</p>
          <button class="btn-p-t2 btn-all custom-violetbtn" id="btn-css-plainCss">Download as Plain Text</button>
        </div>
      </div>

      <!-- JavaScript Tab -->
      <input type="radio" id="tabJs" name="mytabs">
      <label for="tabJs">
        <img src="img/js.png" alt="JavaScript Icon" width="20px"> JS
      </label>
      <div class="tab">
        <div class="text-end">
          <img class="img-cls3" onclick="txtCopyMobile3()" src="img/1621635.png" alt="Copy Button" width="20px">
        </div>
        <div>
          <textarea id="lineCounterJsMobi" wrap="off" readonly>1.</textarea>
          <div>
            <textarea id="jsMobile" class="js-txtareaMobi form-control h-txtarea" placeholder="JavaScript" draggable="false"></textarea>
          </div>
        </div>
        <div class="col text-end">
          <img id="btn3" class="img-cls3-3" onclick="clearTextMobilejs()" src="img/189264.png" alt="Refresh Button"
            width="30px">
        </div>
        <div class="justify-content-around d-no-mobile">
          <button class="btn-all custom-violetbtn" id="btn-js-codeJs">Download as JS File</button>
          <p class="mb-0">OR</p>
          <button class="btn-p-t btn-all custom-violetbtn" id="btn-js-plainJs">Download as Plain Text</button>
        </div>
      </div>
    </div>

    <!-- Preview Section -->
    <div class="preview-container text-center">
      <!-- Preview Header -->
      <div class="preview-header">
        <div class="preview-controls">
          <button class="preview-btn refresh-btn" title="Refresh Preview">
            <i class="bi bi-arrow-clockwise"></i>
          </button>
          <div class="device-selector">
            <button class="preview-btn device-btn" data-device="desktop" title="Desktop View">
              <i class="bi bi-display"></i>
            </button>
            <button class="preview-btn device-btn" data-device="tablet" title="Tablet View">
              <i class="bi bi-tablet"></i>
            </button>
            <button class="preview-btn device-btn" data-device="mobile" title="Mobile View">
              <i class="bi bi-phone"></i>
            </button>
          </div>
        </div>
      <div>
      <span class="live-preview-text">Live Preview</span>
      </div>
        <div class="preview-info">
          <span class="viewport-size">1920 x 1080</span>
        </div>
      </div>
      
      <!-- Preview Content -->
      <div class="preview-content">
        <div class="preview-loading">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
        </div>
        <iframe class="preview-frame" id="codeMobile"></iframe>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer>
    <div class="footer-copyright text-center">
      &copy; Developed with ❤️ by <a href="#" class="white-text">MN Shariff</a>.
    </div>
  </footer>

  <!-- Scripts -->
  <script src="bootstrap-5.3.3-dist/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"
    integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="lineMobi.js"></script>
  <script src="mobile-app.js"></script>
  <script src="preview-controls.js"></script>
  <script>
    // Check if the user has scrolled and add the `scrolled` class to the navbar
    window.addEventListener('scroll', function () {
      const navbar = document.querySelector('.glassmorphism');

      if (window.scrollY > 50) {  // Adjust the scroll threshold as needed
        navbar.classList.add('scrolled');
      } else {
        navbar.classList.remove('scrolled');
      }
    });
  </script>
</body>

</html>
