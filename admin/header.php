<?php
include '../config.php';

session_start();

if (isset($_SESSION['sec-username'])) {
    $uname = $_SESSION['sec-username'];
    $suser = mysqli_query($connect, "SELECT * FROM `users` WHERE username='$uname' AND (role='Admin' || role='Editor')");
    $count = mysqli_num_rows($suser);
    if ($count <= 0) {
        echo '<meta http-equiv="refresh" content="0; url=index.php" />';
        exit;
    }
    $user = mysqli_fetch_assoc($suser);
} else {
    echo '<meta http-equiv="refresh" content="0; url=../login.php" />';
    exit;
}

if (basename($_SERVER['SCRIPT_NAME']) != 'add_post.php' 
&& basename($_SERVER['SCRIPT_NAME']) != 'posts.php' 
&& basename($_SERVER['SCRIPT_NAME']) != 'add_page.php' 
&& basename($_SERVER['SCRIPT_NAME']) != 'pages.php' 
&& basename($_SERVER['SCRIPT_NAME']) != 'add_widget.php' 
&& basename($_SERVER['SCRIPT_NAME']) != 'widgets.php' 
&& basename($_SERVER['SCRIPT_NAME']) != 'add_image.php' 
&& basename($_SERVER['SCRIPT_NAME']) != 'gallery.php'
&& basename($_SERVER['SCRIPT_NAME']) != 'settings.php'
&& basename($_SERVER['SCRIPT_NAME']) != 'subscribers.php') {
    $_GET  = filter_input_array(INPUT_GET, FILTER_SANITIZE_SPECIAL_CHARS);
    $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
}

if ($user['role'] == "Editor" && (basename($_SERVER['SCRIPT_NAME']) != 'add_post.php' && basename($_SERVER['SCRIPT_NAME']) != 'posts.php' && basename($_SERVER['SCRIPT_NAME']) != 'add_image.php' && basename($_SERVER['SCRIPT_NAME']) != 'gallery.php' && basename($_SERVER['SCRIPT_NAME']) != 'dashboard.php')) {
    echo '<meta http-equiv="refresh" content="0; url=dashboard.php" />';
    exit;
}

function short_text($text, $length)
{
    $maxTextLenght = $length;
    $aspace        = " ";
    if (strlen($text) > $maxTextLenght) {
        $text = substr(trim($text), 0, $maxTextLenght);
        $text = substr($text, 0, strlen($text) - strpos(strrev($text), $aspace));
        $text = $text . '...';
    }
    return $text;
}

function byte_convert($size)
{
    if ($size < 1024)
        return $size . ' Byte';
    if ($size < 1048576)
        return sprintf("%4.2f KB", $size / 1024);
    if ($size < 1073741824)
        return sprintf("%4.2f MB", $size / 1048576);
    if ($size < 1099511627776)
        return sprintf("%4.2f GB", $size / 1073741824);
    else
        return sprintf("%4.2f TB", $size / 1073741824);
}

function post_author($author_id)
{
    include "../config.php";
    
    $author = '-';
    
    $queryauthp = mysqli_query($connect, "SELECT username FROM `users` WHERE id='$author_id' LIMIT 1");
    $countauthp = mysqli_num_rows($queryauthp);

    if ($countauthp > 0) {
    
        $rowauthp = mysqli_fetch_assoc($queryauthp);
        $author   = $rowauthp['username'];
    }
 
    return $author;
}

$queryst = mysqli_query($connect, "SELECT date_format FROM settings LIMIT 1");
$st      = mysqli_fetch_assoc($queryst);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
    <title>phpBlog - Admin Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
    <meta name="author" content="Antonov_WEB" />

    <link rel="shortcut icon" href="../assets/img/favicon.png" />

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
	
	<!-- Font Awesome -->
	<link href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" rel="stylesheet"/>
	
	<!--DataTables-->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/dt-1.13.4/r-2.4.1/datatables.min.css"/>
 
	<!-- jQuery --> 
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
	
	<!-- CK Editor -->
	<script src="https://cdn.ckeditor.com/4.21.0/full/ckeditor.js"></script>
	
	<style>
    a:link {
      text-decoration: none;
    }

    a:visited {
      text-decoration: none;
    }
    
	body {
	  font-size: .875rem;
	}

	.feather {
	  width: 16px;
	  height: 16px;
	  vertical-align: text-bottom;
	}

	.sidebar {
	  position: fixed;
	  top: 0;
	  right: 0;
	  bottom: 0;
	  left: 0;
	  z-index: 100;
	  padding: 48px 0 0;
	  box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
	}

	@media (max-width: 767.98px) {
	  .sidebar {
		top: 5rem;
	  }
	}

	.sidebar-sticky {
	  position: relative;
	  top: 0;
	  height: calc(100vh - 48px);
	  padding-top: .5rem;
	  overflow-x: hidden;
	  overflow-y: auto;
	}

	.sidebar .nav-link {
	  font-weight: 500;
	  color: #333;
	}

	.sidebar .nav-link .feather {
	  margin-right: 4px;
	  color: #727272;
	}

	.sidebar .nav-link.active {
	  color: #007bff;
	}

	.sidebar .nav-link:hover .feather,
	.sidebar .nav-link.active .feather {
	  color: inherit;
	}

	.sidebar-heading {
	  font-size: .75rem;
	  text-transform: uppercase;
	}

	.navbar-brand {
	  padding-top: .75rem;
	  padding-bottom: .75rem;
	  font-size: 1rem;
	  background-color: rgba(0, 0, 0, .25);
	  box-shadow: inset -1px 0 0 rgba(0, 0, 0, .25);
	}

	.navbar .navbar-toggler {
	  top: .25rem;
	  right: 1rem;
	}
    </style>
	
<body>

<header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
  <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6" href="dashboard.php"><b><i class="fas fa-toolbox"></i> phpBlog</b></a>
  <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Menu">
    <span class="navbar-toggler-icon"></span>
  </button>
  <ul class="navbar-nav px-3 w-100">
    <li class="nav-item text-nowrap">
      <a class="nav-link" href="../index.php"><i class="fas fa-columns"></i> Visit Site</a>
    </li>
  </ul>
  <ul class="navbar-nav px-3">
    <li class="nav-item text-nowrap">
      <a class="nav-link" href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </li>
  </ul>
</header>

<div class="container-fluid">
  <div class="row">
    <nav id="sidebarMenu" class="col-lg-2 d-md-block bg-dark text-white sidebar collapse" style="overflow-y: scroll;">
      <div class="position-sticky pt-3">
        <ul class="nav nav-pills flex-column mb-auto">
          <li <?php
if (basename($_SERVER['SCRIPT_NAME']) == 'dashboard.php') {
    echo 'class="nav-item"';
}
?>>
            <a class="nav-link text-white <?php
if (basename($_SERVER['SCRIPT_NAME']) == 'dashboard.php') {
    echo 'active';
}
?>" href="dashboard.php">
              <i class="fas fa-columns"></i> Dashboard
            </a>
          </li>
<?php
if ($user['role'] == "Admin") {
?>
          <li <?php
if (basename($_SERVER['SCRIPT_NAME']) == 'settings.php') {
    echo 'class="nav-item"';
}
?>>
            <a class="nav-link text-white <?php
if (basename($_SERVER['SCRIPT_NAME']) == 'settings.php') {
    echo 'active';
}
?>" href="settings.php">
              <i class="fas fa-cogs"></i> Settings
            </a>
          </li>
          <li <?php
if (basename($_SERVER['SCRIPT_NAME']) == 'menu_editor.php' || basename($_SERVER['SCRIPT_NAME']) == 'add_menu.php') {
    echo 'class="nav-item"';
}
?>>
            <a class="nav-link text-white <?php
if (basename($_SERVER['SCRIPT_NAME']) == 'menu_editor.php' || basename($_SERVER['SCRIPT_NAME']) == 'add_menu.php') {
    echo 'active';
}
?>" href="menu_editor.php">
              <i class="fas fa-bars"></i> Menu
            </a>
          </li>
          <li <?php
if (basename($_SERVER['SCRIPT_NAME']) == 'files.php' || basename($_SERVER['SCRIPT_NAME']) == 'upload_file.php') {
    echo 'class="nav-item"';
}
?>>
            <a class="nav-link text-white <?php
if (basename($_SERVER['SCRIPT_NAME']) == 'files.php' || basename($_SERVER['SCRIPT_NAME']) == 'upload_file.php') {
    echo 'active';
}
?>" href="files.php">
              <i class="fas fa-folder-open"></i> Files
            </a>
          </li>
          <li <?php
if (basename($_SERVER['SCRIPT_NAME']) == 'messages.php' || basename($_SERVER['SCRIPT_NAME']) == 'read_message.php') {
    echo 'class="nav-item"';
}
?>>
            <a class="nav-link text-white <?php
if (basename($_SERVER['SCRIPT_NAME']) == 'messages.php' || basename($_SERVER['SCRIPT_NAME']) == 'read_message.php') {
    echo 'active';
}
?>" href="messages.php">
              <i class="fas fa-envelope"></i> Messages
            </a>
          </li>
          <li <?php
if (basename($_SERVER['SCRIPT_NAME']) == 'users.php') {
    echo 'class="nav-item"';
}
?>>
            <a class="nav-link text-white <?php
if (basename($_SERVER['SCRIPT_NAME']) == 'users.php') {
    echo 'active';
}
?>" href="users.php">
              <i class="fas fa-users"></i> Users
            </a>
          </li>
		  <li <?php
if (basename($_SERVER['SCRIPT_NAME']) == 'subscribers.php') {
    echo 'class="nav-item"';
}
?>>
            <a class="nav-link text-white <?php
if (basename($_SERVER['SCRIPT_NAME']) == 'subscribers.php') {
    echo 'active';
}
?>" href="subscribers.php">
              <i class="far fa-envelope"></i> Subscribers
            </a>
          </li>
<?php
}
?>
        </ul>
        
        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
          <span>Posts</span>
        </h6>
        <ul class="nav nav-pills flex-column mb-auto">
          <li <?php
if (basename($_SERVER['SCRIPT_NAME']) == 'add_post.php') {
    echo 'class="nav-item"';
}
?>>
            <a class="nav-link text-white <?php
if (basename($_SERVER['SCRIPT_NAME']) == 'add_post.php') {
    echo 'active';
}
?>" href="add_post.php">
              <i class="fas fa-edit"></i> Add Post
            </a>
          </li>
          <li <?php
if (basename($_SERVER['SCRIPT_NAME']) == 'posts.php') {
    echo 'class="nav-item"';
}
?>>
            <a class="nav-link text-white <?php
if (basename($_SERVER['SCRIPT_NAME']) == 'posts.php') {
    echo 'active';
}
?>" href="posts.php">
              <i class="fas fa-list"></i> Posts
            </a>
          </li>
<?php
if ($user['role'] == "Admin") {
?>
          <li <?php
if (basename($_SERVER['SCRIPT_NAME']) == 'categories.php' || basename($_SERVER['SCRIPT_NAME']) == 'add_category.php') {
    echo 'class="nav-item"';
}
?>>
            <a class="nav-link text-white <?php
if (basename($_SERVER['SCRIPT_NAME']) == 'categories.php' || basename($_SERVER['SCRIPT_NAME']) == 'add_category.php') {
    echo 'active';
}
?>" href="categories.php">
              <i class="fas fa-list-ol"></i> Categories
            </a>
          </li>
          <li <?php
if (basename($_SERVER['SCRIPT_NAME']) == 'comments.php') {
    echo 'class="nav-item"';
}
?>>
            <a class="nav-link text-white <?php
if (basename($_SERVER['SCRIPT_NAME']) == 'comments.php') {
    echo 'active';
}
?>" href="comments.php">
              <i class="fas fa-comments"></i> Comments
            </a>
          </li>
<?php
}
?>
        </ul>
<?php
if ($user['role'] == "Admin") {
?>
        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
          <span>Pages</span>
        </h6>
        <ul class="nav nav-pills flex-column mb-auto">
          <li <?php
if (basename($_SERVER['SCRIPT_NAME']) == 'add_page.php') {
    echo 'class="nav-item"';
}
?>>
            <a class="nav-link text-white <?php
if (basename($_SERVER['SCRIPT_NAME']) == 'add_page.php') {
    echo 'active';
}
?>" href="add_page.php">
              <i class="fas fa-edit"></i> Add Page
            </a>
          </li>
          <li <?php
if (basename($_SERVER['SCRIPT_NAME']) == 'pages.php') {
    echo 'class="nav-item"';
}
?>>
            <a class="nav-link text-white <?php
if (basename($_SERVER['SCRIPT_NAME']) == 'pages.php') {
    echo 'active';
}
?>" href="pages.php">
              <i class="fas fa-file-alt"></i> Pages
            </a>
          </li>
        </ul>
<?php
}
?>
        
        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
          <span>Gallery</span>
        </h6>
        <ul class="nav nav-pills flex-column mb-auto">
          <li <?php
if (basename($_SERVER['SCRIPT_NAME']) == 'add_image.php') {
    echo 'class="nav-item"';
}
?>>
            <a class="nav-link text-white <?php
if (basename($_SERVER['SCRIPT_NAME']) == 'add_image.php') {
    echo 'active';
}
?>" href="add_image.php">
              <i class="fas fa-camera-retro"></i> Add Image
            </a>
          </li>
          <li <?php
if (basename($_SERVER['SCRIPT_NAME']) == 'gallery.php') {
    echo 'class="nav-item"';
}
?>>
            <a class="nav-link text-white <?php
if (basename($_SERVER['SCRIPT_NAME']) == 'gallery.php') {
    echo 'active';
}
?>" href="gallery.php">
              <i class="fas fa-images"></i> Gallery
            </a>
          </li>
<?php
if ($user['role'] == "Admin") {
?>
		  <li <?php
if (basename($_SERVER['SCRIPT_NAME']) == 'albums.php' || basename($_SERVER['SCRIPT_NAME']) == 'add_album.php') {
    echo 'class="nav-item"';
}
?>>
            <a class="nav-link text-white <?php
if (basename($_SERVER['SCRIPT_NAME']) == 'albums.php' || basename($_SERVER['SCRIPT_NAME']) == 'add_album.php') {
    echo 'active';
}
?>" href="albums.php">
              <i class="fas fa-list-ol"></i> Albums
            </a>
          </li>
        </ul>
        
        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
          <span>Widgets</span>
        </h6>
        <ul class="nav nav-pills flex-column mb-auto">
          <li <?php
if (basename($_SERVER['SCRIPT_NAME']) == 'add_widget.php') {
    echo 'class="nav-item"';
}
?>>
            <a class="nav-link text-white <?php
if (basename($_SERVER['SCRIPT_NAME']) == 'add_widget.php') {
    echo 'active';
}
?>" href="add_widget.php">
              <i class="fas fa-edit"></i> Add Widget
            </a>
          </li>
          <li <?php
if (basename($_SERVER['SCRIPT_NAME']) == 'widgets.php') {
    echo 'class="nav-item"';
}
?>>
            <a class="nav-link text-white <?php
if (basename($_SERVER['SCRIPT_NAME']) == 'widgets.php') {
    echo 'active';
}
?>" href="widgets.php">
              <i class="fas fa-archive"></i> Widgets
            </a>
          </li>
        </ul>
<?php
}
?>
        <br /><br />
      </div>
    </nav>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mb-4">