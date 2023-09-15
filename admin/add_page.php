<?php
include "header.php";

if (isset($_POST['add'])) {
    $title   = addslashes($_POST['title']);
    $content = htmlspecialchars($_POST['content']);
    
    $add = mysqli_query($connect, "INSERT INTO pages (title, content) VALUES ('$title', '$content')");
    
    $sql2    = "SELECT * FROM pages WHERE title='$title'";
    $result2 = mysqli_query($connect, $sql2);
    $row     = mysqli_fetch_assoc($result2);
    $id      = $row['id'];
    $add2    = mysqli_query($connect, "INSERT INTO menu (page, path, fa_icon) VALUES ('$title', 'page.php?id=$id', 'fa-columns')");
	
	echo '<meta http-equiv="refresh" content="0;url=pages.php">';
}
?>
	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
		<h3 class="h3"><i class="fas fa-file-alt"></i> Pages</h3>
	</div>
	
            <div class="card">
              <h6 class="card-header">Add Page</h6>         
                  <div class="card-body">
                      <form action="" method="post">
						<p>
							<label>Title</label>
							<input class="form-control" name="title" value="" type="text" required>
						</p>
						<p>
							<label>Content</label>
							<textarea class="form-control" name="content" required></textarea>
						</p>
							<input type="submit" name="add" class="btn btn-primary col-12" value="Add" />
					  </form>                            
                  </div>
            </div>

<script>
    CKEDITOR.replace( 'content' );
</script>
<?php
include "footer.php";
?>