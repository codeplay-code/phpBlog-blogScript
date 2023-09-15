<?php
include "header.php";

if (isset($_POST['add'])) {
    $title       = addslashes($_POST['title']);
    $active      = addslashes($_POST['active']);
	$album_id = addslashes($_POST['album_id']);
    $description = htmlspecialchars($_POST['description']);
    
    $image = '';
    
    if (@$_FILES['avafile']['name'] != '') {
        $target_dir    = "uploads/gallery/";
        $target_file   = $target_dir . basename($_FILES["avafile"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        $uploadOk = 1;
        
        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["avafile"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo '<div class="alert alert-danger">The file is not an image.</div>';
            $uploadOk = 0;
        }
        
        // Check file size
        if ($_FILES["avafile"]["size"] > 10000000) {
            echo '<div class="alert alert-warning">Sorry, your file is too large.</div>';
            $uploadOk = 0;
        }
        
        if ($uploadOk == 1) {
            $string     = "0123456789wsderfgtyhjuk";
            $new_string = str_shuffle($string);
            $location   = "../uploads/gallery/image_$new_string.$imageFileType";
            move_uploaded_file($_FILES["avafile"]["tmp_name"], $location);
            $image = 'uploads/gallery/image_' . $new_string . '.' . $imageFileType . '';
        }
    }
    
    $add = mysqli_query($connect, "INSERT INTO `gallery` (album_id, title, image, description, active) VALUES ('$album_id', '$title', '$image', '$description', '$active')");
    echo '<meta http-equiv="refresh" content="0; url=gallery.php">';
}
?>
	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
		<h3 class="h3"><i class="fas fa-images"></i> Gallery</h3>
	</div>

	<div class="card">
        <h6 class="card-header">Add Image</h6>         
            <div class="card-body">
                <form action="" method="post" enctype="multipart/form-data">
					<p>
						<label>Title</label>
						<input class="form-control" name="title" value="" type="text" required>
					</p>
					<p>
						<label>Image</label>
						<input type="file" name="avafile" class="form-control" required />
					</p>
					<p>
						<label>Active</label><br />
						<select name="active" class="form-select" required>
							<option value="Yes" selected>Yes</option>
							<option value="No">No</option>
                        </select>
					</p>
					<p>
						<label>Album</label><br />
						<select name="album_id" class="form-select" required>
<?php
$crun = mysqli_query($connect, "SELECT * FROM `albums`");
while ($rw = mysqli_fetch_assoc($crun)) {
    echo '
                            <option value="' . $rw['id'] . '">' . $rw['title'] . '</option>
									';
}
?>
						</select>
					</p>
					<p>
						<label>Description</label>
						<textarea class="form-control" name="description"></textarea>
					</p>
                                
					<input type="submit" name="add" class="btn btn-primary col-12" value="Add" />
				</form>                            
            </div>
        </div>

<script>
    CKEDITOR.replace( 'description' );
</script>
<?php
include "footer.php";
?>