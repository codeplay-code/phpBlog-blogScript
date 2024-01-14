<?php
// phpBlog version
$phpblog_version = "2.2";

$configfile = 'config.php';
if (!file_exists($configfile)) {
    echo '<meta http-equiv="refresh" content="0; url=install/index.php" />';
    exit();
}

// Set longer maxlifetime of the session (7 days)
@ini_set( "session.gc_maxlifetime", '604800');

// Set longer cookie lifetime of the session (7 days)
@ini_set( "session.cookie_lifetime", '604800');

session_start();
include "config.php";

// Data Sanitization
$_GET  = filter_input_array(INPUT_GET, FILTER_SANITIZE_SPECIAL_CHARS);
$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

if (!isset($_SESSION['sec-username'])) {
    $logged = 'No';
} else {
    
    $username = $_SESSION['sec-username'];
    
    $querych = mysqli_query($connect, "SELECT * FROM `users` WHERE username='$username' LIMIT 1");
    if (mysqli_num_rows($querych) == 0) {
        $logged = 'No';
    } else {
        $rowu   = $querych->fetch_array();
        $logged = 'Yes';
    }
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

function emoticons($text)
{
    $icons = array(
        ':)' => '🙂',
        ':-)' => '🙂',
        ':}' => '🙂',
        ':D' => '😀',
        ':d' => '😁',
        ':-D ' => '😂',
        ';D' => '😂',
        ';d' => '😂',
        ';)' => '😉',
        ';-)' => '😉',
        ':P' => '😛',
        ':-P' => '😛',
        ':-p' => '😛',
        ':p' => '😛',
        ':-b' => '😛',
        ':-Þ' => '😛',
        ':(' => '🙁',
        ';(' => '😓',
        ':\'(' => '😓',
        ':o' => '😮',
        ':O' => '😮',
        ':0' => '😮',
        ':-O' => '😮',
        ':|' => '😐',
        ':-|' => '😐',
        ' :/' => ' 😕',
        ':-/' => '😕',
        ':X' => '😷',
        ':x' => '😷',
        ':-X' => '😷',
        ':-x' => '😷',
        '8)' => '😎',
        '8-)' => '😎',
        'B-)' => '😎',
        ':3' => '😊',
        '^^' => '😊',
        '^_^' => '😊',
        '<3' => '😍',
        ':*' => '😘',
        'O:)' => '😇',
        '3:)' => '😈',
        'o.O' => '😵',
        'O_o' => '😵',
        'O_O' => '😵',
        'o_o' => '😵',
        '0_o' => '😵',
        'T_T' => '😵',
        '-_-' => '😑',
        '>:O' => '😆',
        '><' => '😆',
        '>:(' => '😣',
        ':v' => '🙃',
        '(y)' => '👍',
        ':poop:' => '💩',
        ':|]' => '🤖'
    );
    return strtr($text, $icons);
}

function post_author($author_id)
{
    include "config.php";
    
    $author = '-';
    
    $queryauthp = mysqli_query($connect, "SELECT username FROM `users` WHERE id='$author_id' LIMIT 1");
    $countauthp = mysqli_num_rows($queryauthp);

    if ($countauthp > 0) {
    
        $rowauthp = mysqli_fetch_assoc($queryauthp);
        $author   = $rowauthp['username'];
    }
 
    return $author;
}

function post_title($post_id)
{
    include "config.php";
    
    $title = '-';
    
    $querytitlep = mysqli_query($connect, "SELECT title FROM `posts` WHERE id='$post_id' LIMIT 1");
    $counttitlep = mysqli_num_rows($querytitlep);

    if ($counttitlep > 0) {
    
        $rowtitlep = mysqli_fetch_assoc($querytitlep);
        $title     = $rowtitlep['title'];
    }
 
    return $title;
}

function post_category($category_id)
{
    include "config.php";
    
    $category = '-';

    $querycat = mysqli_query($connect, "SELECT category FROM `categories` WHERE id='$category_id' LIMIT 1");
    $countcat = mysqli_num_rows($querycat);

    if ($countcat > 0) {
        $rowcat   = mysqli_fetch_assoc($querycat);
        $category = $rowcat['category'];
    }
 
    return $category;
}

function post_slug($post_id)
{
    include "config.php";
    
    $post_slug = '';

    $querypost = mysqli_query($connect, "SELECT slug FROM `posts` WHERE id='$post_id' LIMIT 1");
    $countpost = mysqli_num_rows($querypost);

    if ($countpost > 0) {
        $rowpost   = mysqli_fetch_assoc($querypost);
        $post_slug = $rowpost['slug'];
    }
 
    return $post_slug;
}

function post_categoryslug($category_id)
{
    include "config.php";
    
    $category_slug = '';

    $querycat = mysqli_query($connect, "SELECT slug FROM `categories` WHERE id='$category_id' LIMIT 1");
    $countcat = mysqli_num_rows($querycat);

    if ($countcat > 0) {
        $rowcat   = mysqli_fetch_assoc($querycat);
        $category_slug = $rowcat['slug'];
    }
 
    return $category_slug;
}

function post_commentscount($post_id)
{
    include "config.php";
    
    $comments_count = '0';

    $querycc = mysqli_query($connect, "SELECT id FROM `comments` WHERE post_id='$post_id'");
    $countc  = mysqli_num_rows($querycc);

    $comments_count = $countc;
 
    return $comments_count;
}

function head()
{
    include "config.php";
    
    if (!isset($_SESSION['sec-username'])) {
        $logged = 'No';
    } else {
        
        $username = $_SESSION['sec-username'];
        
        $querych = mysqli_query($connect, "SELECT * FROM `users` WHERE username='$username' LIMIT 1");
        if (mysqli_num_rows($querych) == 0) {
            $logged = 'No';
        } else {
            $rowu   = $querych->fetch_array();
            $logged = 'Yes';
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<?php
	$current_page = basename($_SERVER['SCRIPT_NAME']);

    // SEO Titles, Descriptions and Sharing Tags
    if ($current_page == 'contact.php') {
        $pagetitle   = 'Contact';
		$description = 'If you have any questions do not hestitate to send us a message.';
		
    } else if ($current_page == 'gallery.php') {
        $pagetitle   = 'Gallery';
		$description = 'View all images from the Gallery.';
		
    } else if ($current_page == 'blog.php') {
        $pagetitle   = 'Blog';
		$description = 'View all blog posts.';
        
    } else if ($current_page == 'profile.php') {
        $pagetitle   = 'Profile';
		$description = 'Manage your account settings.';
		
    } else if ($current_page == 'my-comments.php') {
        $pagetitle   = 'My Comments';
		$description = 'Manage your comments.';
		
    } else if ($current_page == 'login.php') {
        $pagetitle   = 'Sign In';
		$description = 'Login into your account.';
		
    } else if ($current_page == 'unsubscribe.php') {
        $pagetitle   = 'Unsubscribe';
		$description = 'Unsubscribe from Newsletter.';
		
    } else if ($current_page == 'error404.php') {
        $pagetitle   = 'Error 404';
		$description = 'Page is not found.';
		
    } else if ($current_page == 'search.php') {
		
		if (!isset($_GET['q'])) {
			echo '<meta http-equiv="refresh" content="0; url=blog">';
            exit;
		}
		
		$word        = $_GET['q'];
        $pagetitle   = 'Search';
		$description = 'Search results for ' . $word . '.';
		
    } else if ($current_page == 'post.php') {
        $slug = $_GET['name'];
        
        if (empty($slug)) {
            echo '<meta http-equiv="refresh" content="0; url=blog">';
            exit;
        }
        
        $runpt = mysqli_query($connect, "SELECT title, slug, image, content FROM `posts` WHERE slug='$slug'");
        if (mysqli_num_rows($runpt) == 0) {
            echo '<meta http-equiv="refresh" content="0; url=blog">';
            exit;
        }
        $rowpt = mysqli_fetch_assoc($runpt);
        
        $pagetitle   = $rowpt['title'];
		$description = short_text(strip_tags(html_entity_decode($rowpt['content'])), 150);
		
		echo '
		<meta property="og:title" content="' . $rowpt['title'] . '" />
		<meta property="og:description" content="' . short_text(strip_tags(html_entity_decode($rowpt['content'])), 150) . '" />
		<meta property="og:image" content="' . $rowpt['image'] . '" />
		<meta property="og:type" content="article"/>
		<meta property="og:url" content="' . $settings['site_url'] . '/post?name=' . $rowpt['slug'] . '" />
		<meta name="twitter:card" content="summary_large_image"></meta>
		<meta name="twitter:title" content="' . $rowpt['title'] . '" />
		<meta name="twitter:description" content="' . short_text(strip_tags(html_entity_decode($rowpt['content'])), 150) . '" />
		<meta name="twitter:image" content="' . $rowpt['image'] . '" />
		<meta name="twitter:url" content="' . $settings['site_url'] . '/post?name=' . $rowpt['slug'] . '" />
		';
		
    } else if ($current_page == 'page.php') {
        $slug = $_GET['name'];
        
        if (empty($slug)) {
            echo '<meta http-equiv="refresh" content="0; url=' . $settings['site_url'] . '">';
            exit;
        }
        
        $runpp = mysqli_query($connect, "SELECT title, content FROM `pages` WHERE slug='$slug'");
        if (mysqli_num_rows($runpp) == 0) {
            echo '<meta http-equiv="refresh" content="0; url=' . $settings['site_url'] . '">';
            exit;
        }
        $rowpp = mysqli_fetch_assoc($runpp);
        
        $pagetitle   = $rowpp['title'];
		$description = short_text(strip_tags(html_entity_decode($rowpp['content'])), 150);
		
    } else if ($current_page == 'category.php') {
        $slug = $_GET['name'];
        
        if (empty($slug)) {
            echo '<meta http-equiv="refresh" content="0; url=blog">';
            exit;
        }
        
        $runct = mysqli_query($connect, "SELECT category FROM `categories` WHERE slug='$slug'");
        if (mysqli_num_rows($runct) == 0) {
            echo '<meta http-equiv="refresh" content="0; url=blog">';
            exit;
        }
        $rowct = mysqli_fetch_assoc($runct);
        
        $pagetitle   = $rowct['category'];
		$description = 'View all blog posts from ' . $rowct['category'] . ' category.';
    }
    
    if ($current_page == 'index.php') {
        echo '
		<title>' . $settings['sitename'] . '</title>
		<meta name="description" content="' . $settings['description'] . '" />';
    } else {
        echo '
		<title>' . $pagetitle . ' - ' . $settings['sitename'] . '</title>
		<meta name="description" content="' . $description . '" />';
    }
?>
        
        <meta name="author" content="Antonov_WEB" />
		<meta name="generator" content="phpBlog" />
        <meta name="robots" content="index, follow, all" />
        <link rel="shortcut icon" href="assets/img/favicon.png" type="image/png" />

        <!-- Bootstrap 5 -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

		<!-- Font Awesome 5 -->
        <link href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" type="text/css" rel="stylesheet"/>
<?php
if($settings['theme'] != "Bootstrap 5") {
    echo '
        <!-- Bootstrap 5 Theme -->
        <link href="https://bootswatch.com/5/'. strtolower($settings['theme']) .'/bootstrap.min.css" type="text/css" rel="stylesheet"/>
	';
}
?>
		<!-- jQuery -->
		<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
		
		<!-- phpBlog styles, scripts -->
        <link href="assets/css/phpblog.css" rel="stylesheet">
		<script src="assets/js/phpblog.js"></script>
<?php
if ($current_page == 'post.php') {
?>
        <!-- jsSocials -->
        <link type="text/css" rel="stylesheet" href="https://cdn.jsdelivr.net/jquery.jssocials/1.5.0/jssocials.css" />
        <link type="text/css" rel="stylesheet" href="https://cdn.jsdelivr.net/jquery.jssocials/1.5.0/jssocials-theme-classic.css" />
        <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery.jssocials/1.5.0/jssocials.min.js"></script>
<?php
}
?>
	
        <style>
<?php
if($settings['background_image'] != "") {
    echo 'body {
        background: url("' . $settings['background_image'] . '") no-repeat center center fixed;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
    }';
}
?>
        </style>
        
<?php
echo base64_decode($settings['head_customcode']);
?>

</head>

<body <?php 
if ($settings['rtl'] == "Yes") {
	echo 'dir="rtl"';
}
?>>

<?php
if ($logged == 'Yes' && ($rowu['role'] == 'Admin' || $rowu['role'] == 'Editor')) {
?>
	<div class="nav-scroller bg-dark shadow-sm">
		<nav class="nav" aria-label="Secondary navigation">
<?php
if ($rowu['role'] == 'Admin') {
?>
			<a class="nav-link text-white" href="admin/dashboard.php">ADMIN MENU</a>
<?php
} else {
?>
			<a class="nav-link text-white" href="admin/dashboard.php">EDITOR MENU</a>
<?php
}
?>
			<a class="nav-link text-secondary" href="admin/dashboard.php">
				<i class="fas fa-columns"></i> Dashboard
			</a>
			<a class="nav-link text-secondary" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
				<i class="fas fa-tasks"></i> Manage
			</a>
				<ul class="dropdown-menu bg-dark">
<?php
if ($rowu['role'] == 'Admin') {
?>
					<li>
						<a class="dropdown-item text-white" href="admin/settings.php">
							Site Settings
						</a>
					</li>
					<li>
						<a class="dropdown-item text-white" href="admin/menu_editor.php">
							Menu
						</a>
					</li>
					<li>
						<a class="dropdown-item text-white" href="admin/widgets.php">
							Widgets
						</a>
					</li>
					<li>
						<a class="dropdown-item text-white" href="admin/users.php">
							Users
						</a>
					</li>
					<li>
						<a class="dropdown-item text-white" href="admin/newsletter.php">
							Newsletter
						</a>
					</li>
<?php
}
?>
					<li>
						<a class="dropdown-item text-white" href="admin/file.php">
							Files
						</a>
					</li>
					<li>
						<a class="dropdown-item text-white" href="admin/posts.php">
							Posts
						</a>
					</li>
					<li>
						<a class="dropdown-item text-white" href="admin/gallery.php">
							Gallery
						</a>
					</li>
<?php
if ($rowu['role'] == 'Admin') {
?>
					<li>
						<a class="dropdown-item text-white" href="admin/pages.php">
							Pages
						</a>
					</li>
<?php
}
?>
				</ul>
<?php
if ($rowu['role'] == 'Admin') {
	$msgcount_query  = mysqli_query($connect, "SELECT id FROM messages WHERE viewed = 'No'");
	$unread_messages = mysqli_num_rows($msgcount_query);
?>
			
			<a class="nav-link text-secondary" href="admin/messages.php">
				<i class="fas fa-envelope"></i> Messages
				<span class="badge text-bg-light rounded-pill align-text-bottom"><?php
	echo $unread_messages; 
?> </span>
			</a>
			<a class="nav-link text-secondary" href="admin/comments.php">
				<i class="fas fa-comments"></i> Comments
			</a>
<?php
}
?>
			<a class="nav-link text-secondary" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
				<i class="far fa-plus-square"></i> New
			</a>
				<ul class="dropdown-menu bg-dark">
					<li>
						<a class="dropdown-item text-white" href="admin/add_post.php">
							Add Post
						</a>
					</li>
					<li>
						<a class="dropdown-item text-white" href="admin/add_image.php">
							Add Image
						</a>
					</li>
					<li>
						<a class="dropdown-item text-white" href="admin/upload_file.php">
							Upload File
						</a>
					</li>
<?php
if ($rowu['role'] == 'Admin') {
?>
					<li>
						<a class="dropdown-item text-white" href="admin/add_page.php">
							Add Page
						</a>
					</li>
<?php
}
?>
				</ul>
		</nav>
	</div>
<?php
}
?>
	
	<header class="py-3 border-bottom bg-primary">
		<div class="<?php
if ($settings['layout'] == 'Wide') {
	echo 'container-fluid';
} else {
	echo 'container';
}
?> d-flex flex-wrap justify-content-center">
			<a href="<?php echo $settings['site_url']; ?>" class="d-flex align-items-center text-white mb-3 mb-md-0 me-md-auto text-decoration-none">
				<span class="fs-4"><b><i class="far fa-newspaper"></i> <?php
		echo $settings['sitename'];
?></b></span>
			</a>
			
			<form class="col-12 col-lg-auto mb-3 mb-lg-0" action="search" method="GET">
				<div class="input-group">
					<input type="search" class="form-control" placeholder="Search" name="q" value="<?php
if (isset($_GET['q'])) {
	echo $_GET['q'];
}
?>" required />
					<span class="input-group-btn">
						<button class="btn btn-dark" type="submit"><i class="fa fa-search"></i></button>
					</span>
				</div>
			</form>
		</div>
	</header>
	
	<nav class="navbar nav-underline navbar-expand-lg py-2 bg-light border-bottom">
		<div class="<?php
if ($settings['layout'] == 'Wide') {
	echo 'container-fluid';
} else {
	echo 'container';
}
?>">
			<button class="navbar-toggler mx-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span> Navigation
			</button>
			<div class="collapse navbar-collapse" id="navbarSupportedContent">
				<ul class="navbar-nav me-auto">
<?php
	$runq       = mysqli_query($connect, "SELECT * FROM `menu`");
    while ($row = mysqli_fetch_assoc($runq)) {

        if ($row['path'] == 'blog') {
			
            echo '	<li class="nav-item link-body-emphasis dropdown">
						<a href="blog" class="nav-link link-dark dropdown-toggle px-2';
            if ($current_page == 'blog.php' || $current_page == 'category.php') {
                echo ' active';
            }
            echo '" data-bs-toggle="dropdown">
							<i class="fa ' . $row['fa_icon'] . '"></i> ' . $row['page'] . ' 
							<span class="caret"></span>
						</a>
						<ul class="dropdown-menu">
							<li><a class="dropdown-item" href="blog">View all posts</a></li>';
            $run2 = mysqli_query($connect, "SELECT * FROM `categories` ORDER BY category ASC");
            while ($row2 = mysqli_fetch_array($run2)) {
                echo '		<li><a class="dropdown-item" href="category?name=' . $row2['slug'] . '"><i class="fas fa-chevron-right"></i> ' . $row2['category'] . '</a></li>';
            }
            echo '		</ul>
					</li>';
		
        } else {

			echo '	<li class="nav-item link-body-emphasis">
						<a href="' . $row['path'] . '" class="nav-link link-dark px-2';
            if ($current_page == 'page.php'
				&& (($_GET['name'] ?? '') == ltrim(strstr($row['path'], '='), '='))
			) {
                echo ' active';
			
            } else if ($current_page != 'page.php' && $current_page == $row['path'] . '.php') {
                echo ' active';
            }
            echo '">
							<i class="fa ' . $row['fa_icon'] . '"></i> ' . $row['page'] . '
						</a>
					</li>';
        }
    }
?>
				</ul>
				<ul class="navbar-nav d-flex">
      
<?php
    if ($logged == 'No') {
?>
					<li class="nav-item">
						<a href="login" class="btn btn-primary px-2">
							<i class="fas fa-sign-in-alt"></i> Sign In &nbsp;|&nbsp; Register
						</a>
					</li>
<?php
    } else {
?>
					<li class="nav-item dropdown">
						<a href="#" class="nav-link link-dark dropdown-toggle" data-bs-toggle="dropdown">
							<i class="fas fa-user"></i> Profile <span class="caret"></span>
						</a>
						<ul class="dropdown-menu">
							<li>
								<a class="dropdown-item <?php
if ($current_page == 'my-comments.php') {
	echo ' active';
}
?>" href="my-comments">
									<i class="fa fa-comments"></i> My Comments
								</a>
							</li>
							<li>
								<a class="dropdown-item<?php
if ($current_page == 'profile.php') {
	echo ' active';
}
?>" href="profile">
									<i class="fas fa-cog"></i> Settings
								</a>
							</li>
							<li role="separator" class="divider"></li>
							<li>
								<a class="dropdown-item" href="logout">
									<i class="fas fa-sign-out-alt"></i> Logout
								</a>
							</li>
						</ul>
					</li>
<?php
    }
?>
				</ul>
			</div>
		</div>
	</nav>
    
<?php
if ($settings['latestposts_bar'] == 'Enabled') {
?>
    <div class="pt-2 bg-light">
        <div class="<?php
if ($settings['layout'] == 'Wide') {
	echo 'container-fluid';
} else {
	echo 'container';
}
?> d-flex justify-content-center">
            <div class="col-md-2">
                <h5>
                    <span class="badge bg-danger">
                        <i class="fa fa-info-circle"></i> Latest: 
                    </span>
                </h5>
            </div>
            <div class="col-md-10">
                <marquee behavior="scroll" direction="right" scrollamount="6">
                    
<?php
$run   = mysqli_query($connect, "SELECT * FROM `posts` WHERE active='Yes' ORDER BY id DESC LIMIT 6");
$count = mysqli_num_rows($run);
if ($count <= 0) {
    echo 'There are no published posts';
} else {
    while ($row = mysqli_fetch_assoc($run)) {
        echo '<a href="post?name=' . $row['slug'] . '">' . $row['title'] . '</a>
        &nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;';
    }
}
?>
                </marquee>
            </div>
        </div>
    </div>
<?php
}
?>
	
    <div class="<?php
if ($settings['layout'] == 'Wide') {
	echo 'container-fluid';
} else {
	echo 'container';
}
?> mt-3">
	
<?php
$run = mysqli_query($connect, "SELECT * FROM `widgets` WHERE position = 'header' ORDER BY id ASC");
while ($row = mysqli_fetch_assoc($run)) {
    echo '
		<div class="card mb-3">
			<div class="card-header">' . $row['title'] . '</div>
			<div class="card-body">
				' . html_entity_decode($row['content']) . '
			</div>
		</div>
	';
}
?>
	
        <div class="row">
<?php
}

function sidebar() {
	
    include "config.php";
?>
			<div id="sidebar" class="col-md-4">

				<div class="card">
					<div class="card-header"><i class="fas fa-list"></i> Categories</div>
					<div class="card-body">
						<ul class="list-group">
<?php
    $runq = mysqli_query($connect, "SELECT * FROM `categories` ORDER BY category ASC");
    while ($row = mysqli_fetch_assoc($runq)) {
        $category_id = $row['id'];
        $postc_query = mysqli_query($connect, "SELECT id FROM `posts` WHERE category_id = '$category_id' AND active = 'Yes'");
		$posts_count = mysqli_num_rows($postc_query);
        echo '
							<a href="category?name=' . $row['slug'] . '">
								<li class="list-group-item d-flex justify-content-between align-items-center">
									' . $row['category'] . '
									<span class="badge bg-secondary rounded-pill">' . $posts_count . '</span>
								</li>
							</a>
		';
    }
?>
						</ul>
					</div>
				</div>
				
				<div class="card mt-3">
					<div class="card-header">
						<ul class="nav nav-tabs card-header-tabs nav-justified">
							<li class="nav-item active">
								<a class="nav-link active" href="#popular" data-bs-toggle="tab">
									Popular Posts
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#commentss" data-bs-toggle="tab">
									Recent Comments
								</a>
							</li>
						</ul>
					</div>
					<div class="card-body">
						<div class="tab-content">
							<div id="popular" class="tab-pane fade show active">
<?php
    $run   = mysqli_query($connect, "SELECT * FROM `posts` WHERE active='Yes' ORDER BY views, id DESC LIMIT 4");
    $count = mysqli_num_rows($run);
    if ($count <= 0) {
        echo '<div class="alert alert-info">There are no published posts</div>';
    } else {
        while ($row = mysqli_fetch_assoc($run)) {
            
            $image = "";
            if($row['image'] != "") {
                $image = '<img class="rounded shadow-1-strong me-1"
							src="' . $row['image'] . '" alt="' . $row['title'] . '" width="70"
							height="70" />';
			} else {
                $image = '<svg class="bd-placeholder-img rounded shadow-1-strong me-1" width="70" height="70" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="No Image" preserveAspectRatio="xMidYMid slice" focusable="false">
                <title>Image</title><rect width="70" height="70" fill="#55595c"/>
                <text x="0%" y="50%" fill="#eceeef" dy=".1em">No Image</text></svg>';
            }
            echo '       
								<div class="mb-2 d-flex flex-start align-items-center bg-light rounded">
									<a href="post?name=' . $row['slug'] . '" class="ms-1">
										' . $image . '
									</a>
									<div class="mt-2 mb-2 ms-1 me-1">
										<h6 class="text-primary mb-1">
											<a href="post?name=' . $row['slug'] . '">' . $row['title'] . '</a>
										</h6>
										<p class="text-muted small mb-0">
											<i class="fas fa-calendar"></i> ' . date($settings['date_format'], strtotime($row['date'])) . ', ' . $row['time'] . '<br />
                                            <i class="fa fa-comments"></i> Comments: 
												<a href="post?name=' . $row['slug'] . '#comments">
													<b>' . post_commentscount($row['id']) . '</b>
												</a>
										</p>
									</div>
								</div>
';
        }
    }
?>
							</div>
							<div id="commentss" class="tab-pane fade">
<?php
    $query = mysqli_query($connect, "SELECT * FROM `comments` WHERE approved='Yes' ORDER BY `id` DESC LIMIT 4");
    $cmnts = mysqli_num_rows($query);
    if ($cmnts == "0") {
        echo "There are no comments";
    } else {
        while ($row = mysqli_fetch_array($query)) {
			
			$badge = '';
			$acuthor = $row['user_id'];
            if ($row['guest'] == 'Yes') {
                $acavatar = 'assets/img/avatar.png';
				$badge = ' <span class="badge bg-secondary">Guest</span>';
            } else {
                $querych = mysqli_query($connect, "SELECT * FROM `users` WHERE id='$acuthor' LIMIT 1");
                if (mysqli_num_rows($querych) > 0) {
                    $rowch = mysqli_fetch_assoc($querych);
                    
                    $acavatar = $rowch['avatar'];
                    $acuthor = $rowch['username'];
                }
            }
			
            $query2 = mysqli_query($connect, "SELECT * FROM `posts` WHERE active='Yes' AND id='$row[post_id]'");
            while ($row2 = mysqli_fetch_array($query2)) {
				echo '
								<div class="mb-2 d-flex flex-start align-items-center bg-light rounded border">
									<a href="post?name=' . $row2['slug'] . '#comments" class="ms-2">
										<img class="rounded-circle shadow-1-strong me-2"
										src="' . $acavatar . '" alt="' . $acuthor . '" 
										width="60" height="60" />
									</a>
									<div class="mt-1 mb-1 ms-1 me-1">
										<h6 class="text-primary mb-1">
											<a href="post?name=' . $row2['slug'] . '#comments">' . $acuthor . '</a>
										</h6>
										<p class="text-muted small mb-0">
											on <a href="post?name=' . $row2['slug'] . '#comments">' . $row2['title'] . '</a><br />
											<i class="fas fa-calendar"></i> ' . date($settings['date_format'], strtotime($row['date'])) . ', ' . $row['time'] . '
										</p>
									</div>
								</div>
';
            }
        }
    }
?>
                            </div>
                        </div>
                    </div>
                </div>
				
				<div class="p-4 mt-3 bg-body-tertiary rounded text-dark">
					<h6><i class="fas fa-envelope-open-text"></i> Subscribe</h6><hr />
					
					<p class="mb-3">Get the latest news and exclusive offers</p>
					
					<form action="" method="POST">
						<div class="input-group">
							<input type="email" class="form-control" placeholder="E-Mail Address" name="email" required />
							<span class="input-group-btn">
								<button class="btn btn-primary" type="submit" name="subscribe">Subscribe</button>
							</span>
						</div>
					</form>
<?php
    if (isset($_POST['subscribe'])) {
        $email = $_POST['email'];
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo '<div class="alert alert-danger">The entered E-Mail Address is invalid</div>';
        } else {
            $queryvalid = mysqli_query($connect, "SELECT * FROM `newsletter` WHERE email='$email' LIMIT 1");
            $validator  = mysqli_num_rows($queryvalid);
            if ($validator > 0) {
                echo '<div class="alert alert-warning">This E-Mail Address is already subscribed.</div>';
            } else {
                $run = mysqli_query($connect, "INSERT INTO `newsletter` (email) VALUES ('$email')");
                echo '<div class="alert alert-success">You have successfully subscribed to our newsletter.</div>';
            }
        }
    }
?>
				</div>

<?php
    $run = mysqli_query($connect, "SELECT * FROM `widgets` WHERE position = 'sidebar' ORDER BY id ASC");
    while ($row = mysqli_fetch_assoc($run)) {
        echo '	
				<div class="card mt-3">
					  <div class="card-header">' . $row['title'] . '</div>
					  <div class="card-body">
						' . html_entity_decode($row['content']) . '
					  </div>
				</div>
';
    }
?>
			</div>
		
<?php
}

function footer()
{
    global $phpblog_version;
	include "config.php";
?>
		</div>
<?php
$run = mysqli_query($connect, "SELECT * FROM `widgets` WHERE position = 'footer' ORDER BY id ASC");
while ($row = mysqli_fetch_assoc($run)) {
	echo '		
				<div class="card mt-3">
					<div class="card-header">' . $row['title'] . '</div>
					<div class="card-body">
						' . html_entity_decode($row['content']) . '
					</div>
				</div>
	';
}
?>
	</div>
	
	<footer class="footer border-top bg-dark text-light px-4 py-3 mt-3">
		<div class="row">
			<div class="col-md-2 mb-3">
				<p class="d-block">&copy; <?php
		echo date("Y") .' '. $settings['sitename'];
?></p>
				<p><a href="rss" target="_blank"><i class="fas fa-rss-square"></i> RSS Feed</a></p>
				<p><a href="sitemap" target="_blank"><i class="fas fa-sitemap"></i> XML Sitemap</a></p>
				<p class="d-block small">
					<a href="https://codecanyon.net/item/phpblog-powerful-blog-cms/5979801?ref=Antonov_WEB" target="_blank"><i>Powered by <b>phpBlog v<?php echo $phpblog_version; ?></b></i></a>
				</p>
			</div>
			<div class="col-md-6 mb-3">
				<h5><i class="fa fa-info-circle"></i> About</h5>
<?php
	echo $settings['description'];
?>
			</div>
			<div class="col-md-4 mb-3">
				<h5><i class="fa fa-envelope"></i> Contact</h5>
					<div class="col-12">
						<a href="mailto:<?php
    echo $settings['email'];
?>" target="_blank" class="btn btn-secondary">
							<strong><i class="fa fa-envelope"></i><span>&nbsp; <?php
    echo $settings['email'];
?></span></strong></a>
<?php
    if ($settings['facebook'] != '') {
?>
						<a href="<?php
        echo $settings['facebook'];
?>" target="_blank" class="btn btn-primary">
							<strong><i class="fab fa-facebook-square"></i>&nbsp; Facebook</strong></a>
<?php
    }
    if ($settings['instagram'] != '') {
?>
						<a href="<?php
        echo $settings['instagram'];
?>" target="_blank" class="btn btn-warning">
							<strong><i class="fab fa-instagram"></i>&nbsp; Instagram</strong></a>
<?php
    }
    if ($settings['twitter'] != '') {
?>
						<a href="<?php
        echo $settings['twitter'];
?>" target="_blank" class="btn btn-info">
							<strong><i class="fab fa-twitter-square"></i>&nbsp; Twitter</strong></a>
<?php
    }
    if ($settings['youtube'] != '') {
?>	
						<a href="<?php
        echo $settings['youtube'];
?>" target="_blank" class="btn btn-danger">
							<strong><i class="fab fa-youtube-square"></i>&nbsp; YouTube</strong></a>
<?php
    }
	if ($settings['linkedin'] != '') {
?>	
						<a href="<?php
        echo $settings['linkedin'];
?>" target="_blank" class="btn btn-primary">
							<strong><i class="fab fa-linkedin"></i>&nbsp; LinkedIn</strong></a>
<?php
    }
?>    
					</div>
					<div class="scroll-btn"><div class="scroll-btn-arrow"></div></div>
			</div>
		</div>
	</footer>
</body>

</html>
<?php
}
?>