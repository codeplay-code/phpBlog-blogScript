<?php
include "header.php";

if (isset($_POST['add'])) {
    $title    = addslashes($_POST['title']);
    $content  = htmlspecialchars($_POST['content']);
	$position = addslashes($_POST['position']);
    
    $add = mysqli_query($connect, "INSERT INTO widgets (title, content, position) VALUES ('$title', '$content', '$position')");
    echo '<meta http-equiv="refresh" content="0; url=widgets.php">';
}
?>
	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
		<h3 class="h3"><i class="fas fa-archive"></i> Widgets</h3>
	</div>

	<div class="card">
        <h6 class="card-header">Add Widget</h6>         
            <div class="card-body">
                <form action="" method="post">
					<p>
						<label>Title</label>
						<input class="form-control" name="title" value="" type="text" required>
					</p>
					<p>
						<label>Content</label>
						<textarea class="form-control ckeditor" name="content" required></textarea>
					</p>
					<div class="form-group">
						<label>Position:</label>
						<select class="form-select" name="position" required>
							<option value="Sidebar" selected>Sidebar</option>
							<option value="Header">Header</option>
							<option value="Footer">Footer</option>
						</select>
					</div><br />
					
					<input type="submit" name="add" class="btn btn-primary col-12" value="Add" />
				</form>                          
			</div>
	</div>
<?php
include "footer.php";
?>