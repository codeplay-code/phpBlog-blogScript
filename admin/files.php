<?php
include "header.php";

if (isset($_GET['delete-id'])) {
    $id     = (int) $_GET["delete-id"];
    $query2 = mysqli_query($connect, "SELECT * FROM `files` WHERE id='$id'");
    $row2   = mysqli_fetch_assoc($query2);
    $path   = $row2['path'];
	
    unlink($path);
	$query = mysqli_query($connect, "DELETE FROM `files` WHERE id='$id'");
}
?>
	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h3 class="h3"><i class="fas fa-folder-open"></i> Files</h3>
    </div>

            <div class="card">
              <h6 class="card-header">Files</h6>         
                  <div class="card-body">
				  <a href="upload_file.php" class="btn btn-primary col-12"><i class="fa fa-edit"></i> Upload File</a><br /><br />

            <table id="dt-basic" class="table table-border table-hover" width="100%">
                <thead>
				<tr>
                    <th>File Name</th>
					<th>Type</th>
					<th>Size</th>
					<th>Uploaded</th>
					<th>Actions</th>
                </tr>
				</thead>
<?php
$query = mysqli_query($connect, "SELECT * FROM files ORDER BY id DESC");
while ($row = mysqli_fetch_assoc($query)) {
        echo '
                <tr>
	                <td>' . $row['filename'] . '</td>
					<td>' . filetype($row['path']) . '</td>
					<td>' . byte_convert(filesize($row['path'])) . '</td>
					<td>' . date($st['date_format'], strtotime($row['date'])) . ', ' . $row['time'] . '</td>
					<td>
					    <a href="' . $row['path'] . '" target="_blank" class="btn btn-success btn-sm"><i class="fa fa-eye"></i> View</a>
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
        "order": [[ 3, "desc" ]],
		"responsive": true,
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