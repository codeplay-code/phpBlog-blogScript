<?php
include "header.php";

if (isset($_POST['add'])) {
    $page    = $_POST['page'];
    $path    = $_POST['path'];
    $fa_icon = $_POST['fa_icon'];
    
	$add_sql = mysqli_query($connect, "INSERT INTO menu (page, path, fa_icon) VALUES ('$page', '$path', '$fa_icon')");

    echo '<meta http-equiv="refresh" content="0;url=menu_editor.php">';
}
?>
		<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
			<h3 class="h3"><i class="fas fa-bars"></i> Menu Editor</h3>
		</div>

            <div class="card">
              <h6 class="card-header">Add Menu</h6>         
                  <div class="card-body">
                        <form action="" method="post">
							<p>
								<label>Title</label>
								<input class="form-control" name="page" value="" type="text" required>
							</p>
							<p>
								<label>Path (Link)</label>
								<input class="form-control" name="path" value="" type="text" required>
							</p>
                            <p>
								<label>Font Awesome 5 Icon</label>
								<input class="form-control" name="fa_icon" value="" type="text">
							</p>
							<div class="form-actions">
                                <input type="submit" name="add" class="btn btn-primary col-12" value="Add" />
                            </div>
						</form>                       
                  </div>
            </div>
<?php
include "footer.php";
?>