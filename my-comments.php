<?php
include "core.php";
head();

if ($settings['sidebar_position'] == 'Left') {
	sidebar();
}

$user_id = $rowu['id'];

if ($logged == 'No') {
    echo '<meta http-equiv="refresh" content="0;url=login">';
    exit;
}

if (isset($_GET['delete-comment'])) {
    $id    = (int) $_GET["delete-comment"];
    $query = mysqli_query($connect, "DELETE FROM `comments` WHERE user_id='$user_id' AND id='$id'");
}
?>
    <div class="col-md-8 mb-3">
        <div class="card">
            <div class="card-header"><i class="fa fa-comments"></i> My Comments</div>
            <div class="card-body">

<?php
$query = mysqli_query($connect, "SELECT * FROM comments WHERE user_id='$user_id' ORDER BY id DESC");
$count = mysqli_num_rows($query);
if ($count <= 0) {
    echo '<div class="alert alert-info">You have not written any comments yet.</div>';
} else {
    while ($comment = mysqli_fetch_array($query)) {
		echo '
			<div class="card mb-3">
			  <div class="row">
				<div class="col-md-12">
				  <div class="card-body">
					<h6 class="card-title">
						<div class="row">
							<div class="col-md-10">
								<i class="fas fa-newspaper"></i> On post: <a href="post?name=' . post_slug($comment['post_id']) . '#comments">' . post_title($comment['post_id'])  . '</a>
							</div>
							<div class="col-md-2 d-flex justify-content-end">
								<a href="?delete-comment=' . $comment['id']  . '" class="btn btn-danger btn-sm" title="Delete">
									<i class="fa fa-trash"></i>
								</a>
							</div>
						</div>
					</h6>
					<p class="card-text">' . $comment['comment']  . '</p>
					<p class="card-text">
						<div class="row">
							<div class="col-md-10">
								<small class="text-muted">
									' . date($settings['date_format'], strtotime($comment['date'])) . ', ' . $comment['time'] . '
								</small>
							</div>
							<div class="col-md-2 d-flex justify-content-end">
								'; 
								if ($comment['approved'] == 'Yes') {
									echo '<span class="badge bg-success"><i class="fas fa-check"></i> Approved</span>';
								} else {
									echo '<span class="badge bg-secondary"><i class="fas fa-clock"></i> Pending</span>';
								}
								echo '
							</div>
						</div>
					</p>
				  </div>
				</div>
			  </div>
			</div>			
	';
	}
}
?>

            </div>
		</div>
	</div>
<?php
if ($settings['sidebar_position'] == 'Right') {
	sidebar();
}
footer();
?>