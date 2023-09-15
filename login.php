<?php
include "core.php";
head();

if ($logged == 'Yes') {
    echo '<meta http-equiv="refresh" content="0;url=index.php">';
    exit;
}

$error = 0;
?>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header"><i class="fas fa-user-plus"></i> Membership</div>
                <div class="card-body">

                    <div class="row">
						<div class="col-md-6">
							<h5><i class="fas fa-sign-in-alt"></i> Sign In</h5><hr />
<?php
if (isset($_POST['signin'])) {
    $username = mysqli_real_escape_string($connect, $_POST['username']);
    $password = hash('sha256', $_POST['password']);
    $check    = mysqli_query($connect, "SELECT username, password FROM `users` WHERE `username`='$username' AND password='$password'");
    if (mysqli_num_rows($check) > 0) {
        $_SESSION['sec-username'] = $username;
        echo '<meta http-equiv="refresh" content="0;url=index.php">';
    } else {
        echo '
		<div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i> The entered <strong>Username</strong> or <strong>Password</strong> is incorrect.
        </div>';
        $error = 1;
    }
}
?> 
		<form action="" method="post">
            <div class="input-group mb-3 needs-validation <?php
if ($error == 1) {
    echo 'is-invalid';
}
?>">
                <span class="input-group-text"><i class="fas fa-user"></i></span>
                <input type="username" name="username" class="form-control" placeholder="Username" <?php
if ($error == 1) {
    echo 'autofocus';
}
?> required>
            </div>
            <div class="input-group mb-3 needs-validation">
                <span class="input-group-text"><i class="fas fa-key"></i></span>
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>

            <button type="submit" name="signin" class="btn btn-primary col-12"><i class="fas fa-sign-in-alt"></i>
&nbsp;Sign In</button>

        </form> 
					</div>
					
					<div class="col-md-6">
						<h5><i class="fas fa-user-plus"></i> Registration</h5><hr />
                <?php
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = hash('sha256', $_POST['password']);
    $email    = $_POST['email'];
    $captcha  = '';
    if (isset($_POST['g-recaptcha-response'])) {
        $captcha = $_POST['g-recaptcha-response'];
    }
    if ($captcha) {
        $url          = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($row['gcaptcha_secretkey']) . '&response=' . urlencode($captcha);
        $response     = file_get_contents($url);
        $responseKeys = json_decode($response, true);
        if ($responseKeys["success"]) {
            
            $sql = mysqli_query($connect, "SELECT username FROM `users` WHERE username='$username'");
            if (mysqli_num_rows($sql) > 0) {
                echo '<div class="alert alert-warning"><i class="fa fa-exclamation-circle"></i> The username is taken.</div>';
            } else {
                
                $sql2 = mysqli_query($connect, "SELECT email FROM `users` WHERE email='$email'");
                if (mysqli_num_rows($sql2) > 0) {
                    echo '<div class="alert alert-warning"><i class="fa fa-exclamation-circle"></i> The E-Mail Address is taken</div>';
                } else {
                    $insert  = mysqli_query($connect, "INSERT INTO `users` (`username`, `password`, `email`) VALUES ('$username', '$password', '$email')");
                    $insert2 = mysqli_query($connect, "INSERT INTO `newsletter` (`email`) VALUES ('$email')");
                    
                    $subject = 'Welcome at ' . $row['sitename'] . '';
                    $message = '<a href="' . $site_url . '" title="Visit ' . $row['sitename'] . '" target="_blank">
                                    <h4>' . $row['sitename'] . '</h4>
                                </a><br />

                                <h5>You have successfully registered at ' . $row['sitename'] . '</h5><br /><br />

                                <b>Registration details:</b><br />
                                Username: <b>' . $username . '</b>';
                    $headers = 'MIME-Version: 1.0' . "\r\n";
                    $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
                    $headers .= 'To: ' . $email . ' <' . $email . '>' . "\r\n";
                    $headers .= 'From: ' . $row['email'] . ' <' . $row['email'] . '>' . "\r\n";
                    @mail($email, $subject, $message, $headers);
                    
                    $_SESSION['sec-username'] = $username;
                    echo '<meta http-equiv="refresh" content="0;url=profile.php">';
                }
            }
        }
    }
}
?>
        <form action="" method="post">
            <div class="input-group mb-3 needs-validation">
                <span class="input-group-text"><i class="fas fa-user"></i></span>
                <input type="username" name="username" class="form-control" placeholder="Username" required>
            </div>
			<div class="input-group mb-3 needs-validation">
                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                <input type="email" name="email" class="form-control" placeholder="E-Mail Address" required>
            </div>
            <div class="input-group mb-3 needs-validation">
                <span class="input-group-text"><i class="fas fa-key"></i></span>
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
			<center><div class="g-recaptcha" data-sitekey="<?php
echo $row['gcaptcha_sitekey'];
?>"></div></center><br />

            <button type="submit" name="register" class="btn btn-primary col-12"><i class="fas fa-sign-in-alt"></i>
&nbsp;Sign Up</button>
        </form> 
		
					</div>
				</div>								
            </div>
        </div>
    </div>
<?php
sidebar();
footer();
?>