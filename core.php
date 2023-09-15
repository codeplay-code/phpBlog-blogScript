<?php
$configfile = 'config.php';
if (!file_exists($configfile)) {
    echo '<meta http-equiv="refresh" content="0; url=install" />';
    exit();
}

session_start();
include "config.php";

//Data Sanitization
$_GET  = filter_input_array(INPUT_GET, FILTER_SANITIZE_SPECIAL_CHARS);
$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

$query = mysqli_query($connect, "SELECT * FROM `settings` LIMIT 1");
$row   = mysqli_fetch_assoc($query);

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
<?php
    $run  = mysqli_query($connect, "SELECT * FROM `settings`");
    $site = mysqli_fetch_assoc($run);
?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

<?php
    // SEO Titles
    if (basename($_SERVER['SCRIPT_NAME']) == 'contact.php') {
        $pagetitle = 'Contact';
    } else if (basename($_SERVER['SCRIPT_NAME']) == 'gallery.php') {
        $pagetitle = 'Gallery';
    } else if (basename($_SERVER['SCRIPT_NAME']) == 'blog.php') {
        $pagetitle = 'Blog';
    } else if (basename($_SERVER['SCRIPT_NAME']) == 'profile.php') {
        $pagetitle = 'Profile';
    } else if (basename($_SERVER['SCRIPT_NAME']) == 'my-comments.php') {
        $pagetitle = 'My Comments';
    } else if (basename($_SERVER['SCRIPT_NAME']) == 'login.php') {
        $pagetitle = 'Sign In';
    } else if (basename($_SERVER['SCRIPT_NAME']) == 'unsubscribe.php') {
        $pagetitle = 'Unsubscribe';
    } else if (basename($_SERVER['SCRIPT_NAME']) == 'error404.php') {
        $pagetitle = 'Error 404';
    } else if (basename($_SERVER['SCRIPT_NAME']) == 'search.php') {
        $word      = $_GET['q'];
        $pagetitle = 'Search results for "' . $word . '"';
    } else if (basename($_SERVER['SCRIPT_NAME']) == 'post.php') {
        $id = (int) $_GET['id'];
        
        if (empty($id)) {
            echo '<meta http-equiv="refresh" content="0; url=blog.php">';
            exit;
        }
        
        $runpt = mysqli_query($connect, "SELECT * FROM `posts` WHERE id='$id'");
        if (mysqli_num_rows($runpt) == 0) {
            echo '<meta http-equiv="refresh" content="0; url=blog.php">';
            exit;
        }
        $rowpt = mysqli_fetch_assoc($runpt);
        
        $pagetitle = $rowpt['title'];
    } else if (basename($_SERVER['SCRIPT_NAME']) == 'page.php') {
        $id = (int) $_GET['id'];
        
        if (empty($id)) {
            echo '<meta http-equiv="refresh" content="0; url=index.php">';
            exit;
        }
        
        $runpp = mysqli_query($connect, "SELECT * FROM `pages` WHERE id='$id'");
        if (mysqli_num_rows($runpp) == 0) {
            echo '<meta http-equiv="refresh" content="0; url=index.php">';
            exit;
        }
        $rowpp = mysqli_fetch_assoc($runpp);
        
        $pagetitle = $rowpp['title'];
    } else if (basename($_SERVER['SCRIPT_NAME']) == 'category.php') {
        $id = (int) $_GET['id'];
        
        if (empty($id)) {
            echo '<meta http-equiv="refresh" content="0; url=blog.php">';
            exit;
        }
        
        $runct = mysqli_query($connect, "SELECT * FROM `categories` WHERE id='$id'");
        if (mysqli_num_rows($runct) == 0) {
            echo '<meta http-equiv="refresh" content="0; url=blog.php">';
            exit;
        }
        $rowct = mysqli_fetch_assoc($runct);
        
        $pagetitle = $rowct['category'];
    }
    
    if (basename($_SERVER['SCRIPT_NAME']) == 'index.php') {
        echo '<title>' . $site['sitename'] . '</title>';
        $mt3 = "mt-3";
    } else {
        $mt3 = "";
        echo '<title>' . $pagetitle . ' - ' . $site['sitename'] . '</title>';
    }
?>
        
        <meta name="description" content="<?php
    echo $site['description'];
?>" />
        <meta name="author" content="Antonov_WEB" />
		<meta name="generator" content="phpBlog" />
        <meta name="robots" content="index, follow, all" />
        <link rel="shortcut icon" href="assets/img/favicon.png" type="image/png" />

        <!-- Bootstrap 5 -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
		
		<!-- Font Awesome 5 -->
        <link href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" type="text/css" rel="stylesheet"/>
<?php
if($site['theme'] != "Bootstrap 5") {
    echo '
        <!-- Bootstrap 5 Theme -->
        <link href="assets/css/themes/'. strtolower($site['theme']) .'/bootstrap.min.css" type="text/css" rel="stylesheet"/>
';
}
?>
		<!-- jQuery -->
		<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
		
		<!-- phpBlog styles, scripts -->
        <link href="assets/css/phpblog.css" rel="stylesheet">
		<script src="assets/js/phpblog.js"></script>
        
<?php
if (basename($_SERVER['SCRIPT_NAME']) == 'post.php') {
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
if($site['background_image'] != "") {
    echo 'body {
        background: url("' . $site['background_image'] . '") no-repeat center center fixed;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
    }';
}
?>
        </style>
        
<?php
echo base64_decode($site['head_customcode']);
?>

</head>

<body <?php 
if ($site['rtl'] == "Yes") {
	echo 'dir="rtl"';
}
?>>

<header class="py-3 border-bottom bg-primary">
	<div class="container d-flex flex-wrap justify-content-center">
		<a href="index.php" class="d-flex align-items-center text-white mb-3 mb-md-0 me-md-auto text-decoration-none">
		<span class="fs-4"><b><i class="far fa-newspaper"></i> <?php
    echo $site['sitename'];
?></b></span></a>
		<form class="col-12 col-lg-auto mb-3 mb-lg-0" action="search.php" method="GET">
		<div class="input-group">
			<input type="search" class="form-control" placeholder="Search" name="q" required>
			<span class="input-group-btn">
				<button class="btn btn-dark" type="submit"><i class="fa fa-search"></i></button>
			</span>
		</div>
		</form>
	</div>
</header>
<nav class="navbar navbar-expand-lg py-2 bg-light border-bottom">
	<div class="container">
		<button class="navbar-toggler mx-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span> Navigation
		</button>
		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav me-auto">
<?php
    $runq = mysqli_query($connect, "SELECT * FROM `menu`");
    while ($row = mysqli_fetch_assoc($runq)) {
        if ($row['path'] == 'blog.php') {
            echo '<li class="nav-item dropdown';
            if (basename($_SERVER['SCRIPT_NAME']) == 'blog.php' || basename($_SERVER['SCRIPT_NAME']) == 'category.php') {
                echo ' active';
            }
            echo '">
                <a href="blog.php" class="nav-link link-dark dropdown-toggle px-2" data-bs-toggle="dropdown"><i class="fa ' . $row['fa_icon'] . '"></i> ' . $row['page'] . ' <span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a class="dropdown-item" href="blog.php"><i class="fas fa-th-list"></i> View All</a></li>';
            $run2 = mysqli_query($connect, "SELECT * FROM `categories`");
            while ($row2 = mysqli_fetch_array($run2)) {
                echo '<li><a class="dropdown-item" href="category.php?id=' . $row2['id'] . '"><i class="fas fa-chevron-right"></i> ' . $row2['category'] . '</a></li>';
            }
            echo '</ul></li>';
        } else {
            echo '<li class="nav-item"><a href="' . $row['path'] . '" class="nav-link link-dark px-2';
            if (basename($_SERVER['SCRIPT_NAME']) == $row['path']) {
                echo 'active px-2 text-secondary';
            }
            echo '"><i class="fa ' . $row['fa_icon'] . '"></i> ' . $row['page'] . '</a></li>';
        }
    }
?>
		</ul>
		<ul class="navbar-nav d-flex">
      
<?php
    if ($logged == 'No') {
?>
			<li class="nav-item">
				<a href="login.php" class="btn btn-primary px-2">
					<i class="fas fa-sign-in-alt"></i> Sign In
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
						<a class="dropdown-item" href="my-comments.php">
							<i class="fa fa-comments"></i> My Comments
						</a>
					</li>
					<li>
						<a class="dropdown-item" href="profile.php">
							<i class="fas fa-cog"></i> Settings
						</a>
					</li>
					<li role="separator" class="divider"></li>
					<li>
						<a class="dropdown-item" href="logout.php">
							<i class="fas fa-sign-out-alt"></i> Logout
						</a>
					</li>
				</ul>
			</li>
<?php
        if ($rowu['role'] == 'Admin' || $rowu['role'] == 'Editor') {
?>
			<li class="nav-item">
				<a href="admin" class="btn btn-secondary px-2">
					<i class="fas fa-toolbox"></i> Admin Panel
				</a>
			</li>
<?php
        }
    }
?>
		</ul>
		</div>
	</div>
</nav>
    
<?php
if ($site['latestposts_bar'] == 'Enabled') {
?>
    <div class="pt-2 bg-light">
        <div class="container d-flex justify-content-center">
            <div class="col-md-2">
                <h5>
                    <span class="badge bg-danger">
                        <i class="fa fa-info-circle"></i> Latest: 
                    </span>
                </h5>
            </div>
            <div class="col-md-10">
                <marquee behavior="scroll" direction="right" scrollamount="10">
                    
<?php
$run   = mysqli_query($connect, "SELECT * FROM `posts` WHERE active='Yes' ORDER BY id DESC LIMIT 6");
$count = mysqli_num_rows($run);
if ($count <= 0) {
    echo 'There are no published posts';
} else {
    while ($row = mysqli_fetch_assoc($run)) {
        echo '<a href="post.php?id=' . $row['id'] . '">' . $row['title'] . '</a>
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
	<br />
	
    <div class="container">
	
<?php
$run = mysqli_query($connect, "SELECT * FROM `widgets` WHERE position = 'header' ORDER BY id ASC");
while ($row = mysqli_fetch_assoc($run)) {
    echo '
    <div class="card">
        <div class="card-header">' . $row['title'] . '</div>
        <div class="card-body">
            ' . html_entity_decode($row['content']) . '
        </div>
    </div><br />
';
}
?>
	
        <div class="row">
<?php
}

function sidebar()
{
    include "config.php";
?>
                    <div id="sidebar" class="col-md-4">

                        <div class="card">
                          <div class="card-header"><i class="fas fa-list"></i> Categories</div>
                          <div class="card-body">
                            <div class="list-group">
<?php
    $runq = mysqli_query($connect, "SELECT * FROM `categories`");
    while ($row = mysqli_fetch_assoc($runq)) {
        $category_id = $row['id'];
        $queryac     = mysqli_query($connect, "SELECT * FROM `posts` WHERE category_id = '$category_id' and active='Yes'");
        echo '
            <a href="category.php?id=' . $row['id'] . '" class="list-group-item list-group-item-action"><i class="fa fa-arrow-right""></i>&nbsp; ' . $row['category'] . '</a>
		';
    }
?>
                            </div>
                          </div>
                        </div><br />

<?php
$run  = mysqli_query($connect, "SELECT * FROM `settings`");
$site = mysqli_fetch_assoc($run);
?>
                        <div class="card">
                          <div class="card-header"><i class="fas fa-envelope-open-text"></i> Subscribe to Newsletter</div>
                          <div class="card-body">
                            <p>Subscribe to <?php
    echo $site['sitename'];
?>'s newsletter to receive the latest news and exclusive offers.</p>
						    <form action="" method="POST">
					        <div class="input-group">
            		            <input type="email" class="form-control" placeholder="E-Mail Address" name="email" required>
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
                        </div><br />

                         <div class="card">
                             <div class="card-header">
                                <ul class="nav nav-tabs card-header-tabs nav-justified">
                                    <li class="nav-item active"><a class="nav-link active" href="#popular" data-bs-toggle="tab"><i class="fas fa-star"></i> Popular</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#commentss" data-bs-toggle="tab"><i class="fa fa-comments"></i> Comments</a></li>
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
									<a href="post.php?id=' . $row['id'] . '">
										' . $image . '
									</a>
									<div class="mt-2 mb-2">
										<h6 class="fw-bold text-primary mb-1">
											<a href="post.php?id=' . $row['id'] . '">' . $row['title'] . '</a>
										</h6>
										<p class="text-muted small mb-0">
											<i class="fas fa-calendar"></i> ' . date($site['date_format'], strtotime($row['date'])) . ', ' . $row['time'] . '<br />
                                            <i class="fa fa-comments"></i> Comments: <b>' . post_commentscount($row['id']) . '</b>
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
    $query = mysqli_query($connect, "SELECT * FROM `comments` WHERE approved='Yes' ORDER BY `id` DESC LIMIT 5");
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
									<a href="post.php?id=' . $row['post_id'] . '#comments">
										<img class="rounded-circle shadow-1-strong me-2"
										src="' . $acavatar . '" alt="' . $acuthor . '" 
										width="60" height="60" />
									</a>
									<div class="mt-1 mb-1">
										<h6 class="fw-bold text-primary mb-1">
											<a href="post.php?id=' . $row['post_id'] . '#comments">' . $acuthor . '</a>
										</h6>
										<p class="text-muted small mb-0">
											on <a href="post.php?id=' . $row['post_id'] . '#comments">' . $row2['title'] . '</a><br />
											<i class="fas fa-calendar"></i> ' . date($site['date_format'], strtotime($row['date'])) . ', ' . $row['time'] . '
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
                         </div><br />

<?php
    $run = mysqli_query($connect, "SELECT * FROM `widgets` WHERE position = 'sidebar' ORDER BY id ASC");
    while ($row = mysqli_fetch_assoc($run)) {
        echo '
                    <div class="card">
                          <div class="card-header">' . $row['title'] . '</div>
                          <div class="card-body">
                            ' . html_entity_decode($row['content']) . '
                          </div>
                    </div>
';
    }
?>
                    </aside>
					
                </div><br />
            </div>
			
<?php
$run = mysqli_query($connect, "SELECT * FROM `widgets` WHERE position = 'footer' ORDER BY id ASC");
while ($row = mysqli_fetch_assoc($run)) {
    echo '<br />
    <div class="card">
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
    include "config.php";
    
    $run  = mysqli_query($connect, "SELECT * FROM `settings`");
    $site = mysqli_fetch_assoc($run);
?>

<footer class="footer border-top bg-dark text-light px-4 py-3 mt-3">
    <div class="row">
      <div class="col-md-2">
        <p class="d-block mb-3">&copy; <?php
    echo date("Y") .' '. $site['sitename'];
?></p>
		<p><a href="rss.php" target="_blank"><i class="fas fa-rss-square"></i> RSS Feed</a></p>
		<p><a href="sitemap.php" target="_blank"><i class="fas fa-sitemap"></i> XML Sitemap</a></p>
		<p class="d-block mb-3 small">
			<a href="https://codecanyon.net/item/phpblog-powerful-blog-cms/5979801?ref=Antonov_WEB" target="_blank"><i>Powered by <b>phpBlog</b></i></a>
		</p>
      </div>
      <div class="col-md-6">
        <h5><i class="fa fa-info-circle"></i> About</h5>
<?php
    $runq = mysqli_query($connect, "SELECT * FROM `settings`");
    while ($row = mysqli_fetch_assoc($runq)) {
        echo $row['description'];
    }
?>
      </div>
      <div class="col-md-4">
        <h5><i class="fa fa-envelope"></i> Contact</h5>
            <div class="col-12">
                    <a href="mailto:<?php
    echo $site['email'];
?>" target="_blank" class="btn btn-secondary">
                    <strong><i class="fa fa-envelope"></i><span>&nbsp; <?php
    echo $site['email'];
?></span></strong></a>
<?php
    if ($site['facebook'] != '') {
?>
					<a href="<?php
        echo $site['facebook'];
?>" target="_blank" class="btn btn-primary">
                    <strong><i class="fab fa-facebook-square"></i>&nbsp; Facebook</strong></a>
<?php
    }
    if ($site['instagram'] != '') {
?>
					<a href="<?php
        echo $site['instagram'];
?>" target="_blank" class="btn btn-warning">
                    <strong><i class="fab fa-twitter-square"></i>&nbsp; Instagram</strong></a>
<?php
    }
    if ($site['twitter'] != '') {
?>
					<a href="<?php
        echo $site['twitter'];
?>" target="_blank" class="btn btn-info">
                    <strong><i class="fab fa-twitter-square"></i>&nbsp; Twitter</strong></a>
<?php
    }
    if ($site['youtube'] != '') {
?>	
					<a href="<?php
        echo $site['youtube'];
?>" target="_blank" class="btn btn-danger">
                    <strong><i class="fab fa-youtube-square"></i>&nbsp; YouTube</strong></a>
<?php
    }
	if ($site['linkedin'] != '') {
?>	
					<a href="<?php
        echo $site['linkedin'];
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