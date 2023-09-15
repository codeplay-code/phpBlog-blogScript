<?php
include "header.php";

if (isset($_GET['delete-id'])) {
    $id    = (int) $_GET["delete-id"];
    $query = mysqli_query($connect, "DELETE FROM `users` WHERE id='$id'");
	$query2 = mysqli_query($connect, "DELETE FROM `comments` WHERE user_id='$id' AND guest='No'");
}
?>

	
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
		<h3 class="h3"><i class="fas fa-users"></i> Users</h3>
	</div>
	
<?php
if (isset($_GET['edit-id'])) {
    $id  = (int) $_GET["edit-id"];
    $sql = mysqli_query($connect, "SELECT * FROM `users` WHERE id = '$id'");
    $row = mysqli_fetch_assoc($sql);
    if (empty($id)) {
        echo '<meta http-equiv="refresh" content="0; url=users.php">';
		exit;
    }
    if (mysqli_num_rows($sql) == 0) {
        echo '<meta http-equiv="refresh" content="0; url=users.php">';
		exit;
    }
	
	if (isset($_POST['edit'])) {
		$role = $_POST['role'];
        
		$query = mysqli_query($connect, "UPDATE `users` SET role='$role' WHERE id='$id'");
		echo '<meta http-equiv="refresh" content="0;url=users.php">';
	}
?>
            <div class="card mb-3">
              <h6 class="card-header">Edit User</h6>         
                  <div class="card-body">
                    <form action="" method="post">
						<div class="form-group">
							<label class="control-label">Username: </label>
							<input type="text" name="username" class="form-control" value="<?php
    echo $row['username'];
?>" readonly disabled>
						</div><br />
						<div class="form-group">
							<label class="control-label">E-Mail Address: </label>
								<input type="email" name="email" class="form-control" value="<?php
    echo $row['email'];
?>" readonly disabled>
						</div><br />
						<div class="form-group">
							<label class="control-label">Role: </label><br />
							<select name="role" class="form-select" required>
								<option value="User" <?php
    if ($row['role'] == "User") {
        echo 'selected';
    }
?>>User</option>
                                <option value="Editor" <?php
    if ($row['role'] == "Editor") {
        echo 'selected';
    }
?>>Editor</option>
								<option value="Admin" <?php
    if ($row['role'] == "Admin") {
        echo 'selected';
    }
?>>Administrator</option>
                            </select><br />
						</div>
						<div class="form-actions">
                            <input type="submit" name="edit" class="btn btn-primary col-12" value="Save" />
                        </div>
					</form>
                  </div>
            </div>
<?php
}
?>

			<div class="card">
              <h6 class="card-header">Users</h6>         
                  <div class="card-body">
                    <table id="dt-basic" class="table table-border table-hover bootstrap-datatable" width="100%">
                          <thead>
                              <tr>
								  <th>Username</th>
								  <th>E-Mail</th>
								  <th>Role</th>
								  <th>Actions</th>
                              </tr>
                          </thead>
                          <tbody>
<?php
$query = mysqli_query($connect, "SELECT * FROM users ORDER BY id ASC");
while ($row = mysqli_fetch_assoc($query)) {
    $badge = '';
    if ($row['role'] == 'Admin') {
        $badge = '<h6><span class="badge bg-danger">Admin</span></h6>';
    }
	if ($row['role'] == 'Editor') {
        $badge = '<h6><span class="badge bg-success">Editor</span></h6>';
    }
	if ($row['role'] == 'User') {
        $badge = '<h6><span class="badge bg-primary">User</span></h6>';
    }
    echo '
                            <tr>
                                <td><img src="../' . $row['avatar'] . '" width="40px" height="40px" /> ' . $row['username'] . '</td>
								<td>' . $row['email'] . '</td>
								<td>' . $badge . '</td>
                                <td>
                                    <a class="btn btn-primary btn-sm" href="?edit-id=' . $row['id'] . '">
                                        <i class="fa fa-edit"></i> Edit
                                    </a>
                                    <a class="btn btn-danger btn-sm" href="?delete-id=' . $row['id'] . '">
                                        <i class="fa fa-trash"></i> Delete
                                    </a>
                                </td>
                            </tr>
';
}
?>
                          </tbody>
                     </table>
                  </div>
            </div>
			
<script>
$(document).ready(function() {
	$('#dt-basic').dataTable( {
		"responsive": true,
		"language": {
			"paginate": {
			  "previous": '<i class="fas fa-angle-left"></i>',
			  "next": '<i class="fas fa-angle-right"></i>'
			}
		}
	} );
} );
</script>
<?php
include "footer.php";
?>