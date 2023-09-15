<?php
include "header.php";

if (isset($_GET['delete_bgrimg'])) {
	$querystb = mysqli_query($connect, "SELECT background_image FROM settings LIMIT 1");
	$stb      = mysqli_fetch_assoc($querystb);
	unlink('../' . $stb['background_image']);
	
    $edit_sql = mysqli_query($connect, "UPDATE `settings` SET background_image='' ");
}

if (isset($_POST['save'])) {
    $sitename           = addslashes($_POST['sitename']);
    $description        = addslashes($_POST['description']);
    $email              = addslashes($_POST['email']);
    $gcaptcha_sitekey   = addslashes($_POST['gcaptcha-sitekey']);
    $gcaptcha_secretkey = addslashes($_POST['gcaptcha-secretkey']);
	$head_customcode 	= base64_encode($_POST['head-customcode']);
    $facebook           = addslashes($_POST['facebook']);
    $instagram          = addslashes($_POST['instagram']);
    $twitter            = addslashes($_POST['twitter']);
    $youtube            = addslashes($_POST['youtube']);
	$linkedin           = addslashes($_POST['linkedin']);
    $comments           = addslashes($_POST['comments']);
	$rtl                = addslashes($_POST['rtl']);
	$date_format        = addslashes($_POST['date_format']);
	$latestposts_bar    = addslashes($_POST['latestposts_bar']);
    $theme              = addslashes($_POST['theme']);
	
	$image = "";
	if (@$_FILES['background_image']['name'] != '') {
        $target_dir    = "uploads/other/";
        $target_file   = $target_dir . basename($_FILES["background_image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        $uploadOk = 1;
        
        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["background_image"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo '<div class="alert alert-danger">The file is not an image.</div>';
            $uploadOk = 0;
        }
        
        // Check file size
        if ($_FILES["background_image"]["size"] > 1000000) {
            echo '<div class="alert alert-warning">Sorry, the file is too large.</div>';
            $uploadOk = 0;
        }
        
        if ($uploadOk == 1) {
            $string     = "0123456789wsderfgtyhjuk";
            $new_string = str_shuffle($string);
            $location   = "../uploads/other/bgr_$new_string.$imageFileType";
            move_uploaded_file($_FILES["background_image"]["tmp_name"], $location);
            $image = 'uploads/other/bgr_' . $new_string . '.' . $imageFileType . '';
        }
    }
	
    $edit_sql = mysqli_query($connect, "UPDATE settings SET 
		sitename='$sitename', 
		description='$description', 
		email='$email', 
		gcaptcha_sitekey='$gcaptcha_sitekey', 
		gcaptcha_secretkey='$gcaptcha_secretkey', 
		head_customcode='$head_customcode', 
		facebook='$facebook', 
		instagram='$instagram', 
		twitter='$twitter', 
		youtube='$youtube', 
		linkedin='$linkedin', 
		rtl='$rtl', 
		comments='$comments', 
		date_format='$date_format', 
		latestposts_bar='$latestposts_bar', 
		background_image='$image', 
		theme='$theme'
	");

}
?>
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
			<h3 class="h3"><i class="fas fa-cogs"></i> Settings</h3>
		</div>

            <div class="card">
              <h6 class="card-header">Settings</h6>         
                  <div class="card-body">
<?php
$query = mysqli_query($connect, "SELECT * FROM settings");
while ($s = mysqli_fetch_assoc($query)) {
?>
                                <form action="" method="post" enctype="multipart/form-data">
								<p>
									<label>Site Name</label>
									<input class="form-control" name="sitename" value="<?php
    echo $s['sitename'];
?>" type="text" required>
								</p>
								<p>
									<label>Description</label>
									<textarea class="form-control" name="description" required><?php
    echo $s['description'];
?></textarea>
								</p>
								<p>
									<label>Website's E-Mail Address</label>
									<input class="form-control" name="email" type="email" value="<?php
    echo $s['email'];
?>" type="email" required>
								</p>
								<p>
									<label>reCAPTCHA v2 Site Key:</label>
									<input class="form-control" name="gcaptcha-sitekey" value="<?php
    echo $s['gcaptcha_sitekey'];
?>" type="text" required>
								</p>
								<p>
									<label>reCAPTCHA v2 Secret Key:</label>
									<input class="form-control" name="gcaptcha-secretkey" value="<?php
    echo $s['gcaptcha_secretkey'];
?>" type="text" required>
								</p>
								<p>
									<label>Custom code for < head > tag</label>
									<textarea name="head-customcode" class="form-control" rows="6" placeholder="For example: Google Analytics tracking code can be placed here"><?php
echo base64_decode($s['head_customcode']);
?></textarea>
								</p>
								<p>
									<label>Facebook Profile</label>
									<input class="form-control" name="facebook" type="url" value="<?php
    echo $s['facebook'];
?>" type="text">
								</p>
								<p>
									<label>Instagram Profile</label>
									<input class="form-control" name="instagram" type="url" value="<?php
    echo $s['instagram'];
?>" type="text">
								</p>
								<p>
									<label>Twitter Profile</label>
									<input class="form-control" name="twitter" type="url" value="<?php
    echo $s['twitter'];
?>" type="text">
								</p>
								<p>
									<label>Youtube Profile</label>
									<input class="form-control" name="youtube" type="url" value="<?php
    echo $s['youtube'];
?>" type="text">
								</p>
								<p>
									<label>LinkedIn Profile</label>
									<input class="form-control" name="linkedin" type="url" value="<?php
    echo $s['linkedin'];
?>" type="text">
								</p>
								<p><label>RTL Content (Right-To-Left)</label><br />
									<select name="rtl" class="form-select" required>
									    <option value="No" <?php
    if ($s['rtl'] == "No") {
        echo 'selected';
    }
?>>No</option>
										<option value="Yes" <?php
    if ($s['rtl'] == "Yes") {
        echo 'selected';
    }
?>>Yes</option>
                                    </select>
								</p>
								<p><label>Comments Section</label><br />
									<select name="comments" class="form-select" required>
									    <option value="guests" <?php
    if ($s['comments'] == "guests") {
        echo 'selected';
    }
?>>Registration not required</option>
										<option value="registered" <?php
    if ($s['comments'] == "registered") {
        echo 'selected';
    }
?>>Registration required</option>
                                    </select>
								</p>
								<p><label>Date Format</label><br />
									<select name="date_format" class="form-select" required>
									    <option value="d.m.Y" <?php
    if ($s['date_format'] == "d.m.Y") {
        echo 'selected';
    }
?>><?php echo date("d.m.Y"); ?></option>
										<option value="m.d.Y" <?php
    if ($s['date_format'] == "m.d.Y") {
        echo 'selected';
    }
?>><?php echo date("m.d.Y"); ?></option>
										<option value="Y.m.d" <?php
    if ($s['date_format'] == "Y.m.d") {
        echo 'selected';
    }
?>><?php echo date("Y.m.d"); ?></option>
<option disabled>───────────</option>
										<option value="d F Y" <?php
    if ($s['date_format'] == "d F Y") {
        echo 'selected';
    }
?>><?php echo date("d F Y"); ?></option>
										<option value="F j, Y" <?php
    if ($s['date_format'] == "F j, Y") {
        echo 'selected';
    }
?>><?php echo date("F j, Y"); ?></option>
										<option value="Y F j" <?php
    if ($s['date_format'] == "Y F j") {
        echo 'selected';
    }
?>><?php echo date("Y F j"); ?></option>
<option disabled>───────────</option>
										<option value="d-m-Y" <?php
    if ($s['date_format'] == "d-m-Y") {
        echo 'selected';
    }
?>><?php echo date("d-m-Y"); ?></option>
										<option value="m-d-Y" <?php
    if ($s['date_format'] == "m-d-Y") {
        echo 'selected';
    }
?>><?php echo date("m-d-Y"); ?></option>
										<option value="Y-m-d" <?php
    if ($s['date_format'] == "Y-m-d") {
        echo 'selected';
    }
?>><?php echo date("Y-m-d"); ?></option>
<option disabled>───────────</option>
										<option value="d/m/Y" <?php
    if ($s['date_format'] == "d/m/Y") {
        echo 'selected';
    }
?>><?php echo date("d/m/Y"); ?></option>
										<option value="m/d/Y" <?php
    if ($s['date_format'] == "m/d/Y") {
        echo 'selected';
    }
?>><?php echo date("m/d/Y"); ?></option>
										<option value="Y/m/d" <?php
    if ($s['date_format'] == "Y/m/d") {
        echo 'selected';
    }
?>><?php echo date("Y/m/d"); ?></option>
                                    </select>
								</p>
								<p><label>Latest Posts bar</label><br />
									<select name="latestposts_bar" class="form-select" required>
									    <option value="Enabled" <?php
    if ($s['latestposts_bar'] == "Enabled") {
        echo 'selected';
    }
?>>Enabled</option>
										<option value="Disabled" <?php
    if ($s['latestposts_bar'] == "Disabled") {
        echo 'selected';
    }
?>>Disabled</option>
                                    </select>
								</p>
								<p>
									<label>Background Image</label>
<?php
    if ($s['background_image'] != "") {
        echo '<div class="row d-flex justify-content-center align-items-md-center"><img src="../'.$s['background_image'].'" class="col-md-2" width="128px" height="128px" />
		<a href="?delete_bgrimg" class="btn btn-sm btn-danger col-md-2"><i class="fas fa-trash"></i> Delete</a></div>';
    }
?>
									<input name="background_image" class="form-control" type="file" id="formFile">
								</p>
								<p><label>Theme</label><br />
									<select class="form-select" name="theme" required>
<?php
$themes = array("Bootstrap 5", "Cerulean", "Cosmo", "Darkly", "Flatly", "Journal", "Litera", "Lumen", "Lux", "Materia", "Minty", "Morph", "Pulse", "Quartz", "Sandstone", "Simplex", "Sketchy", "Slate", "Solar", "Spacelab", "Superhero", "United", "Vapor", "Yeti", "Zephyr");
foreach ($themes as $design) {
    if ($s['theme'] == $design) {
        $selected = 'selected';
    } else {
        $selected = '';
    }
    echo '<option value="'.$design.'" '.$selected.'>'.$design.'</option>';
}
?>
                      </select>
								</p>
								<div class="form-actions">
                                    <input type="submit" name="save" class="btn btn-success col-12" value="Save" />
                                </div>
							</form>

<?php
}
?>                             
                  </div>
            </div>   
<?php
include "footer.php";
?>