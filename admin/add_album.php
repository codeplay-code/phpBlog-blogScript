<?php
include "header.php";

if (isset($_POST['add'])) {
    $title = $_POST['title'];
    
    $add_sql = mysqli_query($connect, "INSERT INTO albums (title) VALUES ('$title')");
    echo '<meta http-equiv="refresh" content="0; url=albums.php">';
}
?>
	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
		<h3 class="h3"><i class="fas fa-list-ol"></i> Albums</h3>
	</div>
	
            <div class="card">
              <h6 class="card-header">Add Album</h6>         
                  <div class="card-body">
                      <form action="" method="post">
                      <p>
                          <label>Title</label>
                          <input class="form-control" name="title" value="" type="text" required>
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