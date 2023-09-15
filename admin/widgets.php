<?php
include "header.php";

if (isset($_GET['delete-id'])) {
    $id    = (int) $_GET["delete-id"];
    $query = mysqli_query($connect, "DELETE FROM `widgets` WHERE id='$id'");
}
?>
	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
		<h3 class="h3"><i class="fas fa-archive"></i> Widgets</h3>
	</div>

<?php
if (isset($_GET['edit-id'])) {
    $id  = (int) $_GET["edit-id"];
    $sql = mysqli_query($connect, "SELECT * FROM `widgets` WHERE id = '$id'");
    $row = mysqli_fetch_assoc($sql);
	
    if (empty($id) || mysqli_num_rows($sql) == 0) {
        echo '<meta http-equiv="refresh" content="0; url=widgets.php">';
		exit;
    }
	
	if (isset($_POST['submit'])) {
        $title    = addslashes($_POST['title']);
		$position = addslashes($_POST['position']);
        $content  = htmlspecialchars($_POST['content']);
		
        $update  = mysqli_query($connect, "UPDATE widgets SET title='$title', content='$content', position='$position' WHERE id='$id'");
        echo '<meta http-equiv="refresh" content="0; url=widgets.php">';
    }
?>
            <div class="card mb-3">
              <h6 class="card-header">Edit Widget</h6>         
                  <div class="card-body">
                  <form action="" method="post">
                  <p>
                  	<label>Title</label>
                  	<input name="title" type="text" class="form-control" value="<?php
echo $row['title'];
?>" required>
                  </p>
                  <p>
                  	<label>Content</label>
                  	<textarea name="content" class="ckeditor" required><?php
echo html_entity_decode($row['content']);
?></textarea>
                  </p>
				  <div class="form-group">
                      <label>Position:</label>
                      <select class="form-select" name="position" required>
						<option value="Sidebar" <?php
    if ($row['position'] == "Sidebar") {
        echo 'selected';
    }
?>>Sidebar</option>
                        <option value="Header" <?php
    if ($row['position'] == "Header") {
        echo 'selected';
    }
?>>Header</option>
                        <option value="Footer" <?php
    if ($row['position'] == "Footer") {
        echo 'selected';
    }
?>>Footer</option>
                      </select>
                  </div><br />
				  
                  <input type="submit" class="btn btn-primary col-12" name="submit" value="Save" />
                  </form>
                  </div>
            </div>
<?php
}
?>

            <div class="card">
              <h6 class="card-header">Widgets</h6>         
                  <div class="card-body">
				  <a href="add_widget.php" class="btn btn-primary col-12"><i class="fa fa-edit"></i> Add Widget</a><br /><br />

            <table class="table table-border table-hover">
                <thead>
				<tr>
                    <th>Title</th>
					<th>Position</th>
					<th>Actions</th>
                </tr>
				</thead>
<?php
$sql = mysqli_query($connect, "SELECT * FROM widgets ORDER BY id DESC");
while ($row = mysqli_fetch_assoc($sql)) {
    echo '
                <tr>
	                <td>' . $row['title'] . '</td>
					<td>' . $row['position'] . '</td>
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