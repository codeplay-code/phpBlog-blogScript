<?php
include "core.php";
head();
?>
	<div class="col-md-12 mb-3">
        <div class="card">
            <div class="card-header"><i class="fa fa-images"></i> Gallery</div>
                <div class="card-body">

					<nav>
						<div class="nav nav-pills nav-fill" id="nav-tab" role="tablist">
							<button class="nav-link active" id="nav-all-tab" data-bs-toggle="tab" data-bs-target="#nav-all" type="button" role="tab" aria-controls="nav-all" aria-selected="true">
								<i class="fas fa-border-all"></i> All
							</button>
<?php
$runalb = mysqli_query($connect, "SELECT * FROM `albums` ORDER BY id DESC");
while ($rowalb = mysqli_fetch_assoc($runalb)) {
	echo '<button class="nav-link" id="nav-' . $rowalb['id'] . '-tab" data-bs-toggle="tab" data-bs-target="#nav-' . $rowalb['id'] . '" type="button" role="tab" aria-controls="nav-' . $rowalb['id'] . '" aria-selected="false">
		      <i class="fas fa-folder"></i> ' . $rowalb['title'] . '
		  </button>';
}
?>
						</div>
					</nav>
					<div class="tab-content" id="nav-tabContent">
						<div class="tab-pane fade show active" id="nav-all" role="tabpanel" aria-labelledby="nav-home-tab" tabindex="0">
							<br />
							<div class="row">
<?php
// Home tab
$run   = mysqli_query($connect, "SELECT * FROM `gallery` WHERE active='Yes' ORDER BY id DESC");
$count = mysqli_num_rows($run);
if ($count <= 0) {
    echo '<div class="alert alert-info">There are no added images.</div>';
} else {
    while ($row = mysqli_fetch_assoc($run)) {
        echo '
		
            <div class="col-md-4 mb-3" data-bs-toggle="modal" data-bs-target="#p' . $row['id'] . '" class="col-md-4">
             
                <div class="card shadow-sm">
                    <img src="' . $row['image'] . '" alt="' . $row['title'] . '" style="width: 100%; height: 180px;">

                    <div class="card-body">
                        <h6 class="card-title">' . $row['title'] . '</h6>
                        <div class="d-flex justify-content-between align-items-center">
                            <button type="button" data-bs-toggle="modal" data-bs-target="#p' . $row['id'] . '" class="btn btn-sm btn-outline-secondary col-12">
                                <i class="fas fa-info-circle"></i> Details
                            </button>
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
						
<?php
$runalb = mysqli_query($connect, "SELECT * FROM `albums` ORDER BY id DESC");
while ($rowalb = mysqli_fetch_assoc($runalb)) {
	echo '<div class="tab-pane fade" id="nav-' . $rowalb['id'] . '" role="tabpanel" aria-labelledby="nav-' . $rowalb['id'] . '-tab">
		      <br />
			  <div class="row">';

	$run   = mysqli_query($connect, "SELECT * FROM `gallery` WHERE active='Yes' AND album_id='$rowalb[id]' ORDER BY id DESC");
	$count = mysqli_num_rows($run);
	if ($count <= 0) {
		echo '<div class="alert alert-info">There are no images in this album.</div>';
	} else {
		while ($row = mysqli_fetch_assoc($run)) {
			echo '
				<div class="col-md-4 mb-3" data-bs-toggle="modal" data-bs-target="#p' . $row['id'] . '" class="col-md-4">
				 
					<div class="card shadow-sm">
						<img src="' . $row['image'] . '" alt="' . $row['title'] . '" style="width: 100%; height: 180px;">

						<div class="card-body">
							<h6 class="card-title">' . $row['title'] . '</h6>
							<div class="d-flex justify-content-between align-items-center">
								<button type="button" data-bs-toggle="modal" data-bs-target="#p' . $row['id'] . '" class="btn btn-sm btn-outline-secondary col-12">
									<i class="fas fa-info-circle"></i> Details
								</button>
							</div>
						</div>
					</div>
				
				</div>
	';
		}
	}

	echo '</div></div>';
}

$runimg   = mysqli_query($connect, "SELECT * FROM `gallery` WHERE active='Yes' ORDER BY id DESC");
$countimg = mysqli_num_rows($runimg);
if ($countimg > 0) {
	while ($rowimg = mysqli_fetch_assoc($runimg)) {
		echo '
			<div class="modal" id="p' . $rowimg['id'] . '">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">' . $rowimg['title'] . '</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							<img src="' . $rowimg['image'] . '" width="100%" height="auto" alt="' . $rowimg['title'] . '" /><br /><br />
							' . html_entity_decode($rowimg['description']) . '
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
	</div>
</div></div>
<?php
footer();
?>