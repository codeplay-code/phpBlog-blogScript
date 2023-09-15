<?php
include "core.php";
head();
?>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><i class="fas fa-envelope"></i> Contact</div>
                    <div class="card-body">

                    <h5>Social Profiles</h5>
                        <div class="list-group">
<?php
$run  = mysqli_query($connect, "SELECT * FROM `settings`");
$site = mysqli_fetch_assoc($run);
?>
                    <a class="list-group-item list-group-item-action" href="mailto:<?php
echo $site['email'];
?>" target="_blank"><strong><i class="fa fa-envelope"></i><span>&nbsp; <?php
echo $site['email'];
?></span></strong></a>
<?php
if ($site['facebook'] != '') {
?>
                    <a class="list-group-item list-group-item-primary list-group-item-action" href="<?php
    echo $site['facebook'];
?>" target="_blank"><strong><i class="fab fa-facebook-square"></i>&nbsp; Facebook</strong></a>
<?php
}
if ($site['instagram'] != '') {
?>
					<a class="list-group-item list-group-item-warning list-group-item-action" href="<?php
    echo $site['instagram'];
?>" target="_blank"><strong><i class="fab fa-instagram"></i>&nbsp; Instagram</strong></a>
<?php
}
if ($site['twitter'] != '') {
?>
					<a class="list-group-item list-group-item-info list-group-item-action" href="<?php
    echo $site['twitter'];
?>" target="_blank"><strong><i class="fab fa-twitter-square"></i>&nbsp; Twitter</strong></a>
<?php
}
if ($site['youtube'] != '') {
?>	
					<a class="list-group-item list-group-item-danger list-group-item-action" href="<?php
    echo $site['youtube'];
?>" target="_blank"><strong><i class="fab fa-youtube-square"></i>&nbsp; YouTube</strong></a>
<?php
}
if ($site['linkedin'] != '') {
?>	
					<a class="list-group-item list-group-item-primary list-group-item-action" href="<?php
    echo $site['linkedin'];
?>" target="_blank"><strong><i class="fab fa-linkedin"></i>&nbsp; LinkedIn</strong></a>
<?php
}
?>	        
                        </div>
            
                        <br /><hr>
                        <h5>Leave Your Message</h5>
<?php
if (isset($_POST['send'])) {
    if ($logged == 'No') {
        $name    = $_POST['name'];
        $email   = $_POST['email'];
    } else {
        $name = $rowu['username'];
        $email = $rowu['email'];
    }
    $content = $_POST['text'];
    
    $date = date('d F Y');
    $time = date('H:i');
	
	$captcha = '';
    
    if (isset($_POST['g-recaptcha-response'])) {
        $captcha = $_POST['g-recaptcha-response'];
    }
    if ($captcha) {
        $url          = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($row['gcaptcha_secretkey']) . '&response=' . urlencode($captcha);
        $response     = file_get_contents($url);
        $responseKeys = json_decode($response, true);
        if ($responseKeys["success"]) {
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo '<div class="alert alert-danger">The entered E-Mail Address is invalid.</div>';
            } else {
                $query = mysqli_query($connect, "INSERT INTO messages (name, email, content, date, time) VALUES('$name','$email','$content','$date','$time')");
                echo '<div class="alert alert-success">Your message has been sent successfully.</div>';
            }
        }
    }
}
?>
                        <form method="post" action="">
<?php
if ($logged == 'No') {
?>
                            <label for="name"><i class="fa fa-user"></i> Your Name:</label>
                            <input type="text" name="name" id="name" value="" class="form-control" required />
                            <br />
									
                            <label for="email"><i class="fa fa-envelope"></i> Your E-Mail Address:</label>
                            <input type="email" name="email" id="email" value="" class="form-control" required />
                            <br />
<?php
}
?>
                            <label for="input-message"><i class="far fa-file-alt"></i> Your Message:</label>
                            <textarea name="text" id="input-message" rows="8" class="form-control" required></textarea>

                            <br /><center><div class="g-recaptcha" data-sitekey="<?php
echo $row['gcaptcha_sitekey'];
?>"></div></center><br />

                            <input type="submit" name="send" class="btn btn-primary col-12" value="Send" />
                        </form><br />
                    </div>
			</div>
        </div>
<?php
sidebar();
footer();
?>