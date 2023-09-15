<?php
include "core.php";
head();

$queryst = mysqli_query($connect, "SELECT * FROM `settings` LIMIT 1");
$rowst   = mysqli_fetch_assoc($queryst);
?>
    <div class="col-md-8">
<?php
$id = (int) $_GET['id'];

if (empty($id)) {
    echo '<meta http-equiv="refresh" content="0; url=blog.php">';
    exit;
}

$runq = mysqli_query($connect, "SELECT * FROM `posts` WHERE active='Yes' AND id='$id'");
if (mysqli_num_rows($runq) == 0) {
    echo '<meta http-equiv="refresh" content="0; url=blog.php">';
    exit;
}

mysqli_query($connect, "UPDATE `posts` SET views = views + 1 WHERE active='Yes' and id='$id'");
$row         = mysqli_fetch_assoc($runq);
$post_id     = $row['id'];
echo '
                    <div class="card shadow-sm bg-light">
                        <div class="col-md-12">
							';
if ($row['image'] != '') {
    echo '
        <img src="' . $row['image'] . '" width="100%" height="260" alt="' . $row['title'] . '"/>
';
}
echo '
            <div class="card-body">
                <h5 class="card-title">' . $row['title'] . '</h5>
                ' . html_entity_decode($row['content']) . '
				<hr />
				<div class="row d-flex justify-content-center">
					<div class="col-lg-4">
						<i class="fas fa-user"></i> Author: <b>' . post_author($row['author_id']) . '</b>
					</div>
					<div class="col-lg-4"> 
						<i class="far fa-calendar-alt"></i> <b>' . date($rowst['date_format'], strtotime($row['date'])) . '</b>, <b>' . $row['time'] . '</b>
					</div>
					<div class="col-lg-4"> 	
						<i class="fas fa-list"></i> Category: <a href="category.php?id=' . $row['category_id'] . '"><b>' . post_category($row['category_id']) . '</b></a>
					</div>
				</div>
				<div class="row d-flex justify-content-center">
					<div class="col-lg-4">    
						<i class="fa fa-comments"></i> Comments: <a href="#comments"><b>' . post_commentscount($row['id']) . '</b></a>
					</div>
					<div class="col-lg-8"> 	
						<i class="fa fa-eye"></i> Views: <b>' . $row['views'] . '</b>
					</div>
				</div>
				<hr />
            
				<h6><i class="fas fa-share-alt-square"></i> Share</h6>
				<div id="share" style="font-size: 14px;"></div><br />

				<h6 id="comments">
					<i class="fa fa-comments"></i> Comments (' . post_commentscount($row['id']) . ')
				</h6>
';
?>

<?php
$q     = mysqli_query($connect, "SELECT * FROM comments WHERE post_id='$row[id]' AND approved='Yes' ORDER BY id DESC");
$count = mysqli_num_rows($q);
if ($count <= 0) {
    echo '<div class="alert alert-info">There are no comments yet.</div>';
} else {
    while ($comment = mysqli_fetch_array($q)) {
        $aauthor = $comment['user_id'];
        
        if ($comment['guest'] == 'Yes') {
            $aavatar = 'assets/img/avatar.png';
            $arole   = '<span class="badge bg-secondary">Guest</span>';
        } else {
            
            $querych = mysqli_query($connect, "SELECT * FROM `users` WHERE id='$aauthor' LIMIT 1");
            if (mysqli_num_rows($querych) > 0) {
                $rowch = mysqli_fetch_assoc($querych);
                
                $aavatar = $rowch['avatar'];
                $aauthor = $rowch['username'];
                if ($rowch['role'] == 'Admin') {
                    $arole = '<span class="badge bg-danger">Administrator</span>';
                } elseif ($rowch['role'] == 'Editor') {
                    $arole = '<span class="badge bg-warning">Editor</span>';
                } else {
                    $arole = '<span class="badge bg-info">User</span>';
                }
            }
        }
        
        echo '
		<div class="row d-flex justify-content-center bg-white rounded border mb-2">
			<div class="mb-2 d-flex flex-start align-items-center">
				<img class="rounded-circle shadow-1-strong mt-1 me-3"
					src="' . $aavatar . '" alt="' . $aauthor . '" 
					width="60" height="60" />
				<div class="mt-1 mb-1">
					<h6 class="fw-bold mb-1">
						<i class="fa fa-user"></i> ' . $aauthor . ' ' . $arole . '
					</h6>
					<p class="text-muted small mb-0">
						<i><i class="fas fa-calendar"></i> ' . date($rowst['date_format'], strtotime($comment['date'])) . ', ' . $comment['time'] . '</i>
					</p>
				</div>
			</div>
			<hr class="my-0" />
			<p class="mt-1 mb-1 pb-1">
				' . emoticons($comment['comment']) . '
			</p>
		</div>
	';
    }
}
?>                                  
                    <br />
                    <h6>Leave A Comment</h6>


<?php
$guest = 'No';

if ($logged == 'No' AND $rowst['comments'] == 'guests') {
    $cancomment = 'Yes';
} else {
    $cancomment = 'No';
}
if ($logged == 'Yes') {
    $cancomment = 'Yes';
}

if ($cancomment == 'Yes') {
?>
                        <form action="post.php?id=<?php
    echo $id;
?>" method="post">
<?php
    if ($logged == 'No') {
        $guest = 'Yes';
?>
                        <label for="name"><i class="fa fa-user"></i> Your Name:</label>
                        <input type="text" name="author" value="" class="form-control" required />
                        <br />
<?php
    }
?>
                        <label for="input-message"><i class="fa fa-comment"></i> Comment:</label>
                        <textarea name="message" rows="5" class="form-control" required></textarea>
                        <br />
<?php
    if ($logged == 'No') {
        $guest = 'Yes';
?>
						<center><div class="g-recaptcha" data-sitekey="<?php
        echo $rowst['gcaptcha_sitekey'];
?>"></div></center><br />
<?php
    }
?>
                        <input type="submit" name="post" class="btn btn-primary col-12" value="Post" />
            </form>
<?php
} else {
    echo '<div class="alert alert-info">Please <strong><a href="login.php"><i class="fas fa-sign-in-alt"></i> Sign In</a></strong> to be able to post a comment.</div>';
}

if ($cancomment == 'Yes') {
    if (isset($_POST['post'])) {
        
        $authname_problem = 'No';
        $date             = date($rowst['date_format']);
        $time             = date('H:i');
        
		$captcha = '';
		
        $comment = $_POST['message'];
        if ($logged == 'No') {
            $author = $_POST['author'];
            
            $bot = 'Yes';
            if (isset($_POST['g-recaptcha-response'])) {
                $captcha = $_POST['g-recaptcha-response'];
            }
            if ($captcha) {
                $url          = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($rowst['gcaptcha_secretkey']) . '&response=' . urlencode($captcha);
                $response     = file_get_contents($url);
                $responseKeys = json_decode($response, true);
                if ($responseKeys["success"]) {
                    $bot = 'No';
                }
            }
            
            if (strlen($author) < 2) {
                $authname_problem = 'Yes';
                echo '<div class="alert alert-warning">Your name is too short.</div>';
            }
        } else {
            $bot    = 'No';
            $author = $rowu['id'];
        }
        
        if (strlen($comment) < 2) {
            echo '<div class="alert alert-danger">Your comment is too short.</div>';
        } else {
            if ($authname_problem == 'No' AND $bot == 'No') {
                $runq = mysqli_query($connect, "INSERT INTO `comments` (`post_id`, `comment`, `user_id`, `date`, `time`, `guest`) VALUES ('$row[id]', '$comment', '$author', '$date', '$time', '$guest')");
                echo '<div class="alert alert-success">Your comment has been successfully posted</div>';
                echo '<meta http-equiv="refresh" content="0;url=post.php?id=' . $row['id'] . '#comments">';
            }
        }
    }
}
?>
                    </div>
                </div>
            </div>
        </div>
<script>
$("#share").jsSocials({
    showCount: false,
    showLabel: true,
    shares: [
        { share: "facebook", logo: "fab fa-facebook-square", label: "Share" },
        { share: "twitter", logo: "fab fa-twitter-square", label: "Tweet" },
        { share: "email", logo: "fas fa-envelope", label: "E-Mail" },
        { share: "linkedin", logo: "fab fa-linkedin", label: "Share" },
        { share: "vkontakte", logo: "fab fa-vk", label: " Share" }
    ]
});
</script>
<?php
sidebar();
footer();
?>