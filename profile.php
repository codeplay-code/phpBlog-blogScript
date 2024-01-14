<?php
include "core.php";
head();

if ($logged == 'No') {
    echo '<meta http-equiv="refresh" content="0;url=login">';
    exit;
}

if ($settings['sidebar_position'] == 'Left') {
	sidebar();
}
?>
    <div class="col-md-8 mb-3">
            <div class="card">
                <div class="card-header"><i class="fas fa-cog"></i> Account Settings</div>
                    <div class="card-body">
<?php
$uname   = $_SESSION['sec-username'];
$user_id = $rowu['id'];

if (isset($_POST['save'])) {
    $email    = $_POST['email'];
    $username = $_POST['username'];
    $avatar   = $rowu['avatar'];
    $password = $_POST['password'];
    
    $emused = 'No';
    
    $susere  = mysqli_query($connect, "SELECT * FROM `users` WHERE email='$email' && id != $user_id LIMIT 1");
    $countue = mysqli_num_rows($susere);
    if ($countue > 0) {
        $emused = 'Yes';
    }
    
    if (@$_FILES['avafile']['name'] != '') {
        $target_dir    = "uploads/avatars/";
        $target_file   = $target_dir . basename($_FILES["avafile"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $filename      = $uname . '.' . $imageFileType;
        
        $uploadOk = 1;
        
        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["avafile"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo '<div class="alert alert-warning">File is not an image.';
            $uploadOk = 0;
        }
        
        // Check file size
        if ($_FILES["avafile"]["size"] > 1000000) {
            echo '<div class="alert alert-warning">Sorry, your file is too large.</div>';
            $uploadOk = 0;
        }
        
        if ($uploadOk == 1) {
            move_uploaded_file($_FILES["avafile"]["tmp_name"], "uploads/avatars/" . $filename);
            $avatar = "uploads/avatars/" . $filename;
        }
    }
    
    if (filter_var($email, FILTER_VALIDATE_EMAIL) && $emused == 'No') {
        
        if ($password != null) {
            $password = hash('sha256', $_POST['password']);
            $querysd  = mysqli_query($connect, "UPDATE `users` SET email='$email', username='$username', avatar='$avatar', password='$password' WHERE id='$user_id'");
        } else {
            $querysd = mysqli_query($connect, "UPDATE `users` SET email='$email', username='$username', avatar='$avatar' WHERE id='$user_id'");
        }
        
    }
    
    echo '<meta http-equiv="refresh" content="0;url=profile">';
}
?>
<form method="post" action="" enctype="multipart/form-data">
						<label for="username"><i class="fa fa-user"></i> Username:</label>
                        <input type="text" name="username" id="username" value="<?php
echo $rowu['username'];
?>" class="form-control" required />
                        <br />
									
						<label for="email"><i class="fa fa-envelope"></i> E-Mail Address:</label>
                        <input type="email" name="email" id="email" value="<?php
echo $rowu['email'];
?>" class="form-control" required />
                        <br />
									
						<label for="avatar"><i class="fa fa-image"></i> Avatar:</label>
                        <center><img src="<?php
echo $rowu['avatar'];
?>" width="12%"></center>
                        <div class="custom-file">
                            <input type="file" class="form-control" name="avafile" accept="image/*" id="avatarfile">
                        </div><br /><br />
									
						<label for="name"><i class="fa fa-key"></i> Password:</label>
                        <input type="password" name="password" id="name" value="" class="form-control" />
                        <i>Fill this field only if you want to change your password.</i>
						<br /><br />

                        <input type="submit" name="save" class="btn btn-primary col-12" value="Update" />
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