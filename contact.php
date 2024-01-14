<?php
include "core.php";
head();

if ($settings['sidebar_position'] == 'Left') {
	sidebar();
}
?>
        <div class="col-md-8 mb-3">
            <div class="card">
                <div class="card-header"><i class="fas fa-envelope"></i> Contact</div>
                    <div class="card-body">

                    <h5 class="mb-3">Social Profiles</h5>
                        <div class="list-group">
							<a class="list-group-item list-group-item-action" href="mailto:<?php
echo $settings['email'];
?>" target="_blank"><i class="fa fa-envelope"></i><span>&nbsp; E-Mail: <strong><?php
echo $settings['email'];
?></span></strong></a>
<?php
if ($settings['facebook'] != '') {
?>
							<a class="list-group-item list-group-item-primary list-group-item-action" href="<?php
echo $settings['facebook'];
?>" target="_blank"><strong><i class="fab fa-facebook-square"></i>&nbsp; Facebook</strong></a>
<?php
}
if ($settings['instagram'] != '') {
?>
							<a class="list-group-item list-group-item-warning list-group-item-action" href="<?php
echo $settings['instagram'];
?>" target="_blank"><strong><i class="fab fa-instagram"></i>&nbsp; Instagram</strong></a>
<?php
}
if ($settings['twitter'] != '') {
?>
							<a class="list-group-item list-group-item-info list-group-item-action" href="<?php
echo $settings['twitter'];
?>" target="_blank"><strong><i class="fab fa-twitter-square"></i>&nbsp; Twitter</strong></a>
<?php
}
if ($settings['youtube'] != '') {
?>	
							<a class="list-group-item list-group-item-danger list-group-item-action" href="<?php
echo $settings['youtube'];
?>" target="_blank"><strong><i class="fab fa-youtube-square"></i>&nbsp; YouTube</strong></a>
<?php
}
if ($settings['linkedin'] != '') {
?>	
							<a class="list-group-item list-group-item-primary list-group-item-action" href="<?php
echo $settings['linkedin'];
?>" target="_blank"><strong><i class="fab fa-linkedin"></i>&nbsp; LinkedIn</strong></a>
<?php
}
?>	        
                        </div>
            
                        <h5 class="mt-4 mb-2">Leave Your Message</h5>
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
        $url          = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($settings['gcaptcha_secretkey']) . '&response=' . urlencode($captcha);
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
                            <label for="name"><i class="fa fa-user"></i> Name:</label>
                            <input type="text" name="name" id="name" value="" class="form-control" required />
                            <br />
									
                            <label for="email"><i class="fa fa-envelope"></i> E-Mail Address:</label>
                            <input type="email" name="email" id="email" value="" class="form-control" required />
                            <br />
<?php
}
?>
                            <label for="input-message"><i class="far fa-file-alt"></i> Message:</label>
                            <textarea name="text" id="input-message" rows="8" class="form-control" required></textarea>

                            <br /><center><div class="g-recaptcha" data-sitekey="<?php
echo $settings['gcaptcha_sitekey'];
?>"></div></center>

                            <input type="submit" name="send" class="btn btn-primary col-12" value="Send" />
                        </form>
                    </div>
			</div>
        </div>
<?php
if ($settings['sidebar_position'] == 'Right') {
	sidebar();
}
footer();
?>