<?php
include "header.php";
?>
	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h3 class="h3"><i class="fas fa-columns"></i> Dashboard</h3>
    </div>
	  
            <div class="card">
              <h6 class="card-header">Shortcuts</h6>         
                <div class="card-body">
                  <center>
                    <a href="add_post.php" class="btn btn-primary"><i class="fa fa-edit"></i><br />Write Post</a>
<?php
if ($user['role'] == "Admin") {
?>
                    <a href="settings.php" class="btn btn-primary"><i class="fa fa-cogs"></i><br /> Settings</a>
					<a href="messages.php" class="btn btn-primary"><i class="fa fa-envelope"></i><br /> Messages</a>
					<a href="menu_editor.php" class="btn btn-primary"><i class="fa fa-bars"></i><br /> Menu Editor</a>
					<a href="add_page.php" class="btn btn-primary"><i class="fa fa-file-alt"></i><br /> Add Page</a>
                    <a href="add_image.php" class="btn btn-primary"><i class="fa fa-camera-retro"></i><br /> Add Image</a>
					<a href="widgets.php" class="btn btn-primary"><i class="fa fa-archive"></i><br /> Widgets</a>
					<a href="upload_file.php" class="btn btn-primary"><i class="fa fa-upload"></i><br /> Upload File</a>
<?php
}
?>
                    <a href="../index.php" class="btn btn-primary"><i class="fa fa-eye"></i><br /> Visit Site</a>
                  </center>
                </div>
            </div>
	  
	  <div class="row mt-3">
         <div class="col-md-6 column">
             <div class="card">
              <h6 class="card-header">Statistics</h6>         
                  <div class="card-body">
					<ul class="list-group">
<?php
$query = mysqli_query($connect, "SELECT id FROM posts");
$total = mysqli_num_rows($query);
?>
                      <li class="list-group-item d-flex justify-content-between align-items-start">
                        <a href="posts.php">
						    <i class="fa fa-list"></i> Posts
                        </a>
						<span class="badge bg-primary rounded-pill"><?php
echo $total;
?></span>
                      </li>
<?php
$query = mysqli_query($connect, "SELECT id FROM categories");
$total = mysqli_num_rows($query);
?>
					  <li class="list-group-item d-flex justify-content-between align-items-start">
                        <a href="categories.php">
						    <i class="fa fa-list-ol"></i> Categories
                        </a>
                        <span class="badge bg-primary rounded-pill"><?php
echo $total;
?></span>
                      </li>
<?php
$query = mysqli_query($connect, "SELECT id FROM comments");
$total = mysqli_num_rows($query);
?>
					  <li class="list-group-item d-flex justify-content-between align-items-start">
                        <a href="comments.php">
						    <i class="fa fa-comments"></i> Comments
                        </a>
                        <span class="badge bg-primary rounded-pill"><?php
echo $total;
?></span>
                      </li>
<?php
$query = mysqli_query($connect, "SELECT id FROM gallery");
$total = mysqli_num_rows($query);
?>
					  <li class="list-group-item d-flex justify-content-between align-items-start">
                        <a href="gallery.php">
						    <i class="fa fa-image"></i> Images
                        </a>
                        <span class="badge bg-primary rounded-pill"><?php
echo $total;
?></span>
                      </li>
<?php
$query = mysqli_query($connect, "SELECT id FROM albums");
$total = mysqli_num_rows($query);
?>
					  <li class="list-group-item d-flex justify-content-between align-items-start">
                        <a href="albums.php">
						    <i class="fa fa-list-ol"></i> Albums
                        </a>
                        <span class="badge bg-primary rounded-pill"><?php
echo $total;
?></span>
                      </li>
<?php
$query = mysqli_query($connect, "SELECT id FROM pages");
$total = mysqli_num_rows($query);
?>
					  <li class="list-group-item d-flex justify-content-between align-items-start">
                        <a href="pages.php">
						    <i class="fa fa-file-alt"></i> Pages
                        </a>
                        <span class="badge bg-primary rounded-pill"><?php
echo $total;
?></span>
                      </li>
<?php
$query = mysqli_query($connect, "SELECT id FROM widgets");
$total = mysqli_num_rows($query);
?>
					  <li class="list-group-item d-flex justify-content-between align-items-start">
                        <a href="widgets.php">
						    <i class="fa fa-archive"></i> Widgets
                        </a>
						<span class="badge bg-primary rounded-pill"><?php
echo $total;
?></span>
                      </li>
<?php
$query = mysqli_query($connect, "SELECT id FROM files");
$total = mysqli_num_rows($query);
?>
					  <li class="list-group-item d-flex justify-content-between align-items-start">
                        <a href="files.php">
						    <i class="fa fa-folder-open"></i> Files
                        </a>
						<span class="badge bg-primary rounded-pill"><?php
echo $total;
?></span>
                      </li>
<?php
$query = mysqli_query($connect, "SELECT id FROM messages");
$total = mysqli_num_rows($query);
?>
					  <li class="list-group-item d-flex justify-content-between align-items-start">
                        <a href="messages.php">
						    <i class="fa fa-envelope"></i> Messages
                        </a>
						<span class="badge bg-primary rounded-pill"><?php
echo $total;
?></span>
                      </li>
<?php
$query = mysqli_query($connect, "SELECT id FROM users");
$total = mysqli_num_rows($query);
?>
					  <li class="list-group-item d-flex justify-content-between align-items-start">
                        <a href="users.php">
						    <i class="fa fa-users"></i> Users
                        </a>
                        <span class="badge bg-primary rounded-pill"><?php
echo $total;
?></span>
                      </li>
                    </ul>
                  </div>
            </div>
        </div>    

		<div class="col-md-6 column">
             <div class="card">
              <h6 class="card-header">Recent Comments</h6>
              <div class="card-container-toggle">
                  <div class="card-body">
                    <div class="row">
<?php
$query = mysqli_query($connect, "SELECT * FROM `comments` ORDER BY `id` DESC LIMIT 4");
$cmnts = mysqli_num_rows($query);
if ($cmnts == "0") {
    echo '<div class="alert alert-info">There are no posted comments.</div>';
} else {
    while ($row = mysqli_fetch_array($query)) {
        $query2 = mysqli_query($connect, "SELECT * FROM `posts` WHERE id='$row[post_id]'");
        while ($row2 = mysqli_fetch_array($query2)) {
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
            
            echo '
				<div class="col-md-2">
					<img src="../' . $avatar . '" class="dashboard-member-activity-avatar" />
                </div>
				<div class="col-md-10">
					<a href="comments.php?edit-id=' . $row['id'] . '">
						<span class="blue">Comment by <strong>' . $author . ' </strong> on <strong>' . date($st['date_format'], strtotime($row['date'])) . ', ' . $row['time'] . '</strong></span>
					</a><br />
';
            if ($row['approved'] == "Yes") {
                echo '<strong>Status:</strong> <span class="badge bg-success">Approved</span> ';
            } else {
                echo '<strong>Status:</strong> <span class="badge bg-warning">Pending</span> ';
            }
            if ($row['guest'] == "Yes") {
                echo '<span class="badge bg-info"><i class="fas fa-user"></i> Guest</span> ';
            }
            echo '
                    <p>' . short_text($row['comment'], 100) . '</p>
				</div>
';
        }
    }
}
?>
                    </div>
                  </div>
              </div>
            </div>
         </div>
      </div>

<?php
include "footer.php";
?>