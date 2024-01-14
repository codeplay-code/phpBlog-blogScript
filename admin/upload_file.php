<?php
include "header.php";
?>
	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h3 class="h3"><i class="fas fa-folder-open"></i> Files</h3>
    </div>
    
            <div class="card">
              <h6 class="card-header">Upload File</h6>
                  <div class="card-body">
                    <form action="" method="post" enctype="multipart/form-data">
						<p>
							<label><b>File</b></label>
							<input type="file" name="file" class="form-control" required />
						</p>
						<div class="form-actions">
                            <input type="submit" name="upload" class="btn btn-primary col-12" value="Upload" />
                        </div>
                    </form>
<?php
if (isset($_POST['upload'])) {
    $file     = $_FILES['file'];
    $tmp_name = $_FILES['file']['tmp_name'];
    $name     = $_FILES['file']['name'];
    
    $date = date($settings['date_format']);
    $time = date('H:i');
    
    @$format = end(explode(".", $name));
    if ($format != "png" && $format != "gif" && $format != "jpeg" && $format != "jpg" 
		&& $format != "JPG" && $format != "PNG" && $format != "bmp" && $format != "GIF" 
		&& $format != "doc" && $format != "docx" && $format != "pdf" && $format != "txt" 
		&& $format != "rar" && $format != "html" && $format != "zip" && $format != "odt"
		&& $format != "rtf" && $format != "csv" && $format != "ods" && $format != "xls"
		&& $format != "xlsx" && $format != "odp" && $format != "ppt" && $format != "pptx"
		&& $format != "mp3" && $format != "flac" && $format != "wav" && $format != "wma"
		&& $format != "aac" && $format != "m4a" && $format != "html" && $format != "htm"
		&& $format != "mov" && $format != "avi" && $format != "mkv" && $format != "mp4"
		&& $format != "wmv" && $format != "webm" && $format != "mkv" && $format != "ts"
		&& $format != "webp" && $format != "svg") {
        echo '<br /><div class="alert alert-info">The uploaded file is with unallowed extension.<br />';
    } else {
        $string     = "0123456789wsderfgtyhjuk";
        $new_string = str_shuffle($string);
        $location   = "../uploads/other/file_$new_string.$format";
        move_uploaded_file($tmp_name, $location);
        
        $run_q = mysqli_query($connect, "INSERT INTO `files` (filename, date, time, path) VALUES ('$name', '$date', '$time', '$location')");
		echo '<meta http-equiv="refresh" content="0; url=files.php">';
    }
}
?>                          
                  </div>
              </div>
            </div>
<?php
include "footer.php";
?>