<?php
include "header.php";

if (isset($_GET['id'])) {
    $id  = $_GET['id'];
    $del = mysqli_query($connect, "DELETE FROM messages WHERE id = '$id'");
}
?>
	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
		<h3 class="h3"><i class="fas fa-envelope"></i> Messages</h3>
	</div>

            <div class="card">
              <h6 class="card-header">Messages</h6>         
                  <div class="card-body">
                    <table class="table table-border table-hover" id="dt-basic" width="100%">
                          <thead>
                              <tr>
                                  <th>Name</th>
                                  <th>E-Mail</th>
                                  <th>Date</th>
								  <th>Actions</th>
                              </tr>
                          </thead>
                          <tbody>
<?php
$query = mysqli_query($connect, "SELECT * FROM messages ORDER by id DESC");
while ($row = mysqli_fetch_assoc($query)) {
    echo '
                            <tr>
                                <td>' . $row['name'] . ' ';
	if($row['viewed'] == 'No') {
		echo '<span class="badge bg-primary">Unread</span>';
	}
	echo '
								</td>
                                <td>' . $row['email'] . '</td>
								<td data-sort="' . strtotime($row['date']) . '">' . date($settings['date_format'], strtotime($row['date'])) . ', ' . $row['time'] . '</td>
                                <td>
                                    <a class="btn btn-success btn-sm" href="read_message.php?id=' . $row['id'] . '">
                                        <i class="fa fa-eye"></i> View
                                    </a>
                                    <a class="btn btn-danger btn-sm" href="?id=' . $row['id'] . '">
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
		"order": [[ 2, "desc" ]],
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