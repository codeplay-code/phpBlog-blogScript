<?php
include "header.php";

if (isset($_GET['delete-id'])) {
    $id    = (int) $_GET["delete-id"];
    $query = mysqli_query($connect, "DELETE FROM `comments` WHERE id='$id'");
}
?>
	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
		<h3 class="h3"><i class="fas fa-comments"></i> Comments</h3>
	</div>

            <?php
if (isset($_GET['edit-id'])) {
    $id  = (int) $_GET["edit-id"];
    $sql = mysqli_query($connect, "SELECT * FROM `comments` WHERE id = '$id'");
    $row = mysqli_fetch_assoc($sql);
    if (empty($id)) {
        echo '<meta http-equiv="refresh" content="0; url=comments.php">';
		exit;
    }
    if (mysqli_num_rows($sql) == 0) {
        echo '<meta http-equiv="refresh" content="0; url=comments.php">';
		exit;
    }
    
    $author = $row['user_id'];
    if ($row['guest'] == 'Yes') {
        $avatar = 'assets/img/avatar.png';
    } else {
        $querych = mysqli_query($connect, "SELECT * FROM `users` WHERE id='$author' LIMIT 1");
        if (mysqli_num_rows($querych) > 0) {
            $rowch = mysqli_fetch_assoc($querych);
            
            $avatar = $rowch['avatar'];
            $author = $rowch['username'];
        }
    }
	
	if (isset($_POST['submit'])) {
        $approved = $_POST['approved'];
        $edit_sql  = mysqli_query($connect, "UPDATE comments SET approved='$approved' WHERE id='$id'");
		
        echo '<meta http-equiv="refresh" content="0; url=comments.php">';
    }
?>
            <div class="card mb-3">
              <h6 class="card-header">Edit Comment</h6>         
                  <div class="card-body">
					<form action="" method="post">
						<p>
						  <label>Author</label><br />
						  <input class="form-control" class="form-control" name="author" type="text" value="<?php
    echo $author;
?>" disabled>
						</p>
						<p>
						  <label>Avatar</label><br />
						  <img src="../<?php
    echo $avatar;
?>" width="50px" height="50px" /><br />
						</p>
						<p>
						  <label>Approved: 
<?php
    if ($row['approved'] == "Yes") {
        echo 'Yes';
    } else {
        echo 'No';
    }
?>
						  </label><br />
						  <select class="form-select" name="approved" required>
							<option value="Yes" <?php
    if ($row['approved'] == "Yes") {
        echo 'selected';
    }
?>>Yes</option>
							<option value="No" <?php
    if ($row['approved'] == "No") {
        echo 'selected';
    }
?>>No</option>
						  </select>
						</p>
						<p>
						  <label>Comment</label>
						  <textarea name="comment" class="form-control" rows="6" disabled><?php
    echo $row['comment'];
?></textarea>
						</p>
						
						<input type="submit" class="btn btn-primary col-12" name="submit" value="Update" /><br />
					  </form>
                  </div>
              </div>
<?php
}
?>
			
			<div class="card">
              <h6 class="card-header">Comments</h6>         
                  <div class="card-body">

            <table class="table table-border table-hover" id="dt-basic" width="100%">
                <thead>
				<tr>
                    <th>Author</th>
                    <th>Date</th>
					<th>Approved</th>
					<th>Post</th>
					<th>Actions</th>
                </tr>
				</thead>
<?php
$sql    = "SELECT * FROM comments ORDER BY id DESC";
$result = mysqli_query($connect, $sql);
while ($row = mysqli_fetch_assoc($result)) {
    $author = $row['user_id'];
    $badge  = '';
    if ($row['guest'] == 'Yes') {
        $avatar = 'assets/img/avatar.png';
        $badge  = ' <span class="badge bg-info"><i class="fas fa-user"></i> Guest</span>';
        
    } else {
        $querych = mysqli_query($connect, "SELECT * FROM `users` WHERE id='$author' LIMIT 1");
        if (mysqli_num_rows($querych) > 0) {
            $rowch = mysqli_fetch_assoc($querych);
            
            $avatar = $rowch['avatar'];
            $author = $rowch['username'];
        }
    }
    echo '
                <tr>
	                <td><img src="../' . $avatar . '" width="45px" height="45px" /> ' . $author . '' . $badge . '</td>
	                <td>' . date($st['date_format'], strtotime($row['date'])) . ', ' . $row['time'] . '</td>
					<td>' . $row['approved'] . '</td>';
    $post_id = $row['post_id'];
    $runq2   = mysqli_query($connect, "SELECT * FROM `posts` WHERE id='$post_id'");
    $sql2    = mysqli_fetch_assoc($runq2);
    echo '              <td>' . $sql2['title'] . '</td>
					<td>
					    <a href="?edit-id=' . $row['id'] . '" title="View / Edit" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> View / Edit</a>
						<a href="?delete-id=' . $row['id'] . '" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</a>
					</td>
                </tr>
';
}
echo '</table>';
?>
                  </div>
              </div>
			  
<script>
$(document).ready(function() {

	$('#dt-basic').dataTable( {
		"responsive": true,
		"order": [[ 1, "desc" ]],
		"language": {
			"paginate": {
			  "previous": '<i class="fa fa-angle-left"></i>',
			  "next": '<i class="fa fa-angle-right"></i>'
			}
		}
	} );
} );
</script>
<?php
include "footer.php";
?>