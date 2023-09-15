<?php
include "header.php";

if (isset($_GET['delete-id'])) {
    $id    = (int) $_GET["delete-id"];
    $query = mysqli_query($connect, "DELETE FROM `albums` WHERE id='$id'");
    $query = mysqli_query($connect, "DELETE FROM `galery` WHERE album_id='$id'");
}
?>
	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
		<h3 class="h3"><i class="fas fa-list-ol"></i> Albums</h3>
	</div>
	  
<?php
if (isset($_GET['edit-id'])) {
    $id  = (int) $_GET["edit-id"];
    $sql = mysqli_query($connect, "SELECT * FROM `albums` WHERE id = '$id'");
    $row = mysqli_fetch_assoc($sql);
    if (empty($id)) {
        echo '<meta http-equiv="refresh" content="0; url=albums.php">';
		exit;
    }
    if (mysqli_num_rows($sql) == 0) {
        echo '<meta http-equiv="refresh" content="0; url=albums.php">';
		exit;
    }
    
    if (isset($_POST['submit'])) {
        $title    = $_POST['title'];
        $edit_sql = mysqli_query($connect, "UPDATE albums SET title='$title' WHERE id='$id'");
        echo '<meta http-equiv="refresh" content="0; url=albums.php">';
    }
?>
            <div class="card mb-3">
              <h6 class="card-header">Edit Album</h6>         
                  <div class="card-body">
                      <form action="" method="post">
						<p>
                          <label>Title</label>
                          <input class="form-control" class="form-control" name="title" type="text" value="<?php
    echo $row['title'];
?>" required>
						</p>
                        <input type="submit" class="btn btn-primary col-12" name="submit" value="Save" /><br />
                      </form>
                  </div>
            </div>
<?php
}
?>

            <div class="card">
              <h6 class="card-header">Albums</h6>         
                  <div class="card-body">
				  <a href="add_album.php" class="btn btn-primary col-12"><i class="fa fa-edit"></i> Add Album</a><br /><br />

            <table class="table table-border table-hover">
                <thead>
				<tr>
                    <th>Title</th>
					<th>Actions</th>
                </tr>
				</thead>
<?php
$sql    = "SELECT * FROM albums ORDER BY title ASC";
$result = mysqli_query($connect, $sql);
while ($row = mysqli_fetch_assoc($result)) {
        echo '
                <tr>
	                <td>' . $row['title'] . '</td>
					<td>
					    <a href="?edit-id=' . $row['id'] . '" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Edit</a>
						<a href="?delete-id=' . $row['id'] . '" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</a>
					</td>
                </tr>
';
}
?>
            </table>

                  </div>
              </div>
<?php
include "footer.php";
?>