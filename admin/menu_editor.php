<?php
include "header.php";

if (isset($_GET['up-id'])) {
    $id = (int) $_GET["up-id"];
	
    $querype = mysqli_query($connect, "SELECT id FROM `menu` WHERE id<$id ORDER BY id DESC LIMIT 1");
	$rowpe   = mysqli_fetch_assoc($querype);
	$prev_id = $rowpe['id'];
	
	$queryce = mysqli_query($connect, "SELECT id FROM `menu` WHERE id='$id' LIMIT 1");
	$rowce   = mysqli_fetch_assoc($queryce);
	$curr_id = $rowce['id'];
	
	$update_sql = mysqli_query($connect, "UPDATE menu SET id='9999999' WHERE id='$prev_id'");
	$update_sql = mysqli_query($connect, "UPDATE menu SET id='$prev_id' WHERE id='$curr_id'");
	$update_sql = mysqli_query($connect, "UPDATE menu SET id='$curr_id' WHERE id='9999999'");
}

if (isset($_GET['down-id'])) {
    $id = (int) $_GET["down-id"];
	
    $queryne = mysqli_query($connect, "SELECT id FROM `menu` WHERE id>$id ORDER BY id ASC LIMIT 1");
	$rowne   = mysqli_fetch_assoc($queryne);
	$next_id = $rowne['id'];
	
	$queryce = mysqli_query($connect, "SELECT id FROM `menu` WHERE id='$id' LIMIT 1");
	$rowce   = mysqli_fetch_assoc($queryce);
	$curr_id = $rowce['id'];
	
	$update_sql = mysqli_query($connect, "UPDATE menu SET id='9999998' WHERE id='$next_id'");
	$update_sql = mysqli_query($connect, "UPDATE menu SET id='$next_id' WHERE id='$curr_id'");
	$update_sql = mysqli_query($connect, "UPDATE menu SET id='$curr_id' WHERE id='9999998'");
}

if (isset($_GET['delete-id'])) {
    $id    = (int) $_GET["delete-id"];
    $query = mysqli_query($connect, "DELETE FROM `menu` WHERE id='$id'");
}
?>
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
			<h3 class="h3"><i class="fas fa-bars"></i> Menu Editor</h3>
	  </div>
	  
<?php
if (isset($_GET['edit-id'])) {
    $id  = (int) $_GET["edit-id"];
    $sql = mysqli_query($connect, "SELECT * FROM `menu` WHERE id = '$id'");
    $row = mysqli_fetch_assoc($sql);
    if (empty($id)) {
        echo '<meta http-equiv="refresh" content="0; url=menu_editor.php">';
		exit;
    }
    if (mysqli_num_rows($sql) == 0) {
        echo '<meta http-equiv="refresh" content="0; url=menu_editor.php">';
		exit;
    }
	
	if (isset($_POST['submit'])) {
        $page    = $_POST['page'];
        $path    = $_POST['path'];
        $fa_icon = $_POST['fa_icon'];
        
		$update_sql = mysqli_query($connect, "UPDATE menu SET page='$page', path='$path', fa_icon='$fa_icon' WHERE id='$id'");
        echo '<meta http-equiv="refresh" content="0;url=menu_editor.php">';
    }
?>
            <div class="card mb-3">
              <h6 class="card-header">Edit Menu</h6>         
                  <div class="card-body">
                  <form action="" method="post">
                  <p>
                  	<label>Page</label>
                  	<input name="page" class="form-control" type="text" value="<?php
echo $row['page'];
?>" required>
                  </p>
                  <p>
                  	<label>Path (Link)</label>
                  	<input name="path" class="form-control" type="text" value="<?php
echo $row['path'];
?>" required>
                  </p>
                  <p>
                  	<label>Font Awesome 5 Icon</label>
                  	<input name="fa_icon" class="form-control" type="text" value="<?php
echo $row['fa_icon'];
?>">
                  </p>
                  <input type="submit" class="btn btn-success col-12" name="submit" value="Save" />
                  </form>
                  </div>
            </div>
<?php
}
?>

            <div class="card">
              <h6 class="card-header">Menu Editor</h6>         
                  <div class="card-body">
				  <a href="add_menu.php" class="btn btn-primary col-12"><i class="fa fa-edit"></i> Add Menu</a><br /><br />

            <table class="table table-border table-hover">
                <thead>
				<tr>
                    <th>Order</th>
                    <th>Page</th>
					<th>Path</th>
					<th>Actions</th>
                </tr>
				</thead>
<?php
$query = mysqli_query($connect, "SELECT * FROM menu ORDER BY id ASC");

$queryli  = mysqli_query($connect, "SELECT * FROM menu ORDER BY id DESC LIMIT 1");
$rowli    = mysqli_fetch_assoc($queryli);
$last_id  = $rowli['id'];

$first = true;
while ($row = mysqli_fetch_assoc($query)) {
	
        echo '
                <tr>
	                <td>' . $row['id'] . '</td>
	                <td><i class="fa ' . $row['fa_icon'] . '"></i> ' . $row['page'] . '</td>
					<td>' . $row['path'] . '</td>
					<td>
';
if ($first == false) {
	echo '
						<a href="?up-id=' . $row['id'] . '" title="Move Up" class="btn btn-secondary btn-sm"><i class="fa fa-arrow-up"></i></a>
	';
}
if ($row['id'] != $last_id) {
	echo '
						<a href="?down-id=' . $row['id'] . '" title="Move Down" class="btn btn-secondary btn-sm"><i class="fa fa-arrow-down"></i></a>
	';
}
echo '
					    <a href="?edit-id=' . $row['id'] . '" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Edit</a>
						<a href="?delete-id=' . $row['id'] . '" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</a>
					</td>
                </tr>
';
$first = false;
    }
?>
            </table>
            </div>
        </div>
<?php
include "footer.php";
?>