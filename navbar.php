<head>
    <link rel="stylesheet" href="/css/style.css">
</head>

<header class="header"></header>
<a href="#" class="logo"> <img src="./images/logo.png" alt=""> <strong>Trust</strong>care </a>

<nav class="navbar">

    <a href="#home" class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>">home</a>
    <a href="./pages/About.html" class="<?= basename($_SERVER['PHP_SELF']) == 'About.html' ? 'active' : '' ?>">about</a>
    <a href="./pages/services.html" class="<?= basename($_SERVER['PHP_SELF']) == 'services.html' ? 'active' : '' ?>">services</a>
    <a href="./doctors.php" class="<?= basename($_SERVER['PHP_SELF']) == 'doctors.php' ? 'active' : '' ?>">doctors</a>
    <a href="./appoinment.php" class="<?= basename($_SERVER['PHP_SELF']) == 'appoinment.php' ? 'active' : '' ?>">appointment</a>
    <a href="#review">review</a>
    <a href="./pages/blogs.html" class="<?= basename($_SERVER['PHP_SELF']) == 'blogs.html' ? 'active' : '' ?>">blogs</a>
</nav>
<div id="menu-btn" class="fas fa-bars"></div>
</header>
