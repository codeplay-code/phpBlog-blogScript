<?php
include "header.php";
?>
	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
		<h3 class="h3"><i class="far fa-envelope"></i> Newsletter Subscribers</h3>
	</div>

        <div class="card">
			<h6 class="card-header">Send mass message</h6>         
			<div class="card-body">
<?php
if (isset($_POST['send_mass_message'])) {
    $title    = addslashes($_POST['title']);
    $content  = htmlspecialchars($_POST['content']);

    $run      = mysqli_query($connect, "SELECT * FROM `settings`");
    $site     = mysqli_fetch_assoc($run);
    $from     = $site['email'];
    $sitename = $site['sitename'];
	
    $run2 = mysqli_query($connect, "SELECT * FROM `newsletter`");
    while ($row = mysqli_fetch_assoc($run2)) {
		
        $to = $row['email'];
		
        $subject = $title;
        
        $message = '
<html>
<body>
  <b><h1><a href="' . $site_url . '/" title="Visit the website">' . $sitename . '</a></h1><b/>
  <br />

  ' . html_entity_decode($content) . '
  
  <hr />
  <i>If you do not want to receive more notifications, you can <a href="' . $site_url . '/unsubscribe.php?email=' . $to . '">Unsubscribe</a></i>
</body>
</html>
';
        
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";

        $headers .= 'From: ' . $from . '';
        
        @mail($to, $subject, $message, $headers);
    }
    
    echo '<div class="alert alert-success">Your global message has been sent successfully.</div>';
}
?>
				<form action="" method="post">
					<p>
						<label>Title</label>
						<input class="form-control" name="title" value="" type="text" required>
					</p>
					<p>
						<label>Content</label>
						<textarea class="form-control" name="content" required></textarea>
					</p>
								
					<input type="submit" name="send_mass_message" class="btn btn-primary col-12" value="Send" />
				</form>
			</div>
        </div><br />
			
			<div class="card">
              <h6 class="card-header">Subscribers</h6>         
                  <div class="card-body">
                    <table class="table table-border table-hover" id="dt-basic" width="100%">
                          <thead>
                              <tr>
                                  <th>E-Mail</th>
                              </tr>
                          </thead>
                          <tbody>
<?php
$query = mysqli_query($connect, "SELECT * FROM newsletter ORDER by email ASC");
while ($row = mysqli_fetch_assoc($query)) {
    echo '
                            <tr>
                                <td>' . $row['email'] . '</td>
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

	CKEDITOR.replace( 'content' );

	$('#dt-basic').dataTable( {
		"responsive": true,
		"order": [[ 0, "asc" ]],
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