<?php
include "header.php";

if (isset($_GET['delete-id'])) {
    $id     = (int) $_GET["delete-id"];
	
	$queryvalid = $connect->query("SELECT * FROM `pages` WHERE id='$id' LIMIT 1");
	$validator  = mysqli_num_rows($queryvalid);
	if ($validator > 0) {
		
		$rowvalidator = mysqli_fetch_assoc($queryvalid);
		$slug         = $rowvalidator['slug'];
		
		$query2 = mysqli_query($connect, "DELETE FROM `menu` WHERE path='page?name=$slug'");
		$query  = mysqli_query($connect, "DELETE FROM `pages` WHERE id='$id'");
    }
}
?>
	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
		<h3 class="h3"><i class="fas fa-file-alt"></i> Pages</h3>
	</div>
	  
<?php
if (isset($_GET['edit-id'])) {
    $id  = (int) $_GET["edit-id"];
    $sql = mysqli_query($connect, "SELECT * FROM `pages` WHERE id = '$id'");
    $row = mysqli_fetch_assoc($sql);
	
    if (empty($id) || mysqli_num_rows($sql) == 0) {
        echo '<meta http-equiv="refresh" content="0; url=pages.php">';
		exit;
    }
	
	if (isset($_POST['submit'])) {
        $title   = addslashes($_POST['title']);
		$slug    = generateSeoURL($title, 0);
        $content = htmlspecialchars($_POST['content']);
        
		$queryvalid = $connect->query("SELECT * FROM `pages` WHERE title = '$title' AND id != '$id' LIMIT 1");
		$validator  = mysqli_num_rows($queryvalid);
		if ($validator > 0) {
		echo '
			<div class="alert alert-warning">
				<i class="fas fa-info-circle"></i> Page with this name has already been added.
			</div>';
		} else {
		
			$update = mysqli_query($connect, "UPDATE pages SET title='$title', slug='$slug', content='$content' WHERE id='$id'");
			$update = mysqli_query($connect, "UPDATE menu SET page='$title' WHERE path='page?name=$slug'");
			
			echo '<meta http-equiv="refresh" content="0; url=pages.php">';
		}
    }
?>
            <div class="card mb-3">
              <h6 class="card-header">Edit Page</h6>         
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
						  	<textarea name="content" required><?php
						      echo html_entity_decode($row['content']);
?></textarea>
						  </p>
						  <input type="submit" class="btn btn-primary col-12" name="submit" value="Save" /><br />
					  </form>
                  </div>
            </div>
<?php
}
?>

            <div class="card">
              <h6 class="card-header">Pages</h6>
                  <div class="card-body">
				  <a href="add_page.php" class="btn btn-primary col-12"><i class="fa fa-edit"></i> Add Page</a><br /><br />

            <table id="dt-basic" class="table table-border table-hover">
                <thead>
				<tr>
                    <th>Title</th>
					<th>Actions</th>
                </tr>
				</thead>
<?php
$sql = mysqli_query($connect, "SELECT * FROM pages ORDER BY id DESC");
while ($row = mysqli_fetch_assoc($sql)) {
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

<script>
$(document).ready(function() {

	$('#dt-basic').dataTable( {
		"responsive": true,
		"language": {
			"paginate": {
			  "previous": '<i class="fa fa-angle-left"></i>',
			  "next": '<i class="fa fa-angle-right"></i>'
			}
		}
	} );
} );

CKEDITOR.replace( 'content' );
</script>
<?php
include "footer.php";
?>