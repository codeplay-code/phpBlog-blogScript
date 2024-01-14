<?php
include "core.php";
head();

if ($settings['sidebar_position'] == 'Left') {
	sidebar();
}
?>
	<div class="col-md-8 mb-3">
<?php
$mt3_i = "";
$run   = mysqli_query($connect, "SELECT * FROM `posts` WHERE active='Yes' AND featured='Yes' ORDER BY id DESC");
$count = mysqli_num_rows($run);
if ($count > 0) {
    $i = 0;
    $mt3_i = "mt-3";
?>
<div id="carouselExampleCaptions" class="col-md-12 carousel slide" data-bs-ride="carousel">
	<div class="carousel-indicators">
<?php
    while ($row = mysqli_fetch_assoc($run)) {
        $active1 = "";
        if ($i == 0) {
            $active1 = 'class="active" aria-current="true"';
        }
        
        echo '<button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="' . $i . '" '. $active1 .' aria-label="' . $row['title'] . '"></button>
        ';
        
        $i++;
    }
?>
	</div>
	<div class="carousel-inner rounded">
<?php
    $j = 0;
    $run2 = mysqli_query($connect, "SELECT * FROM `posts` WHERE active='Yes' AND featured='Yes' ORDER BY id DESC");
    while ($row2 = mysqli_fetch_assoc($run2)) {
        $active = "";
        if ($j == 0) {
            $active = " active";
        }
        
        $image = "";
        if($row2['image'] != "") {
            $image = '<img src="' . $row2['image'] . '" alt="' . $row2['title'] . '" class="d-block w-100" height="400">';
        } else {
            $image = '<svg class="bd-placeholder-img bd-placeholder-img-lg d-block w-100" height="400" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="No Image" preserveAspectRatio="xMidYMid slice" focusable="false">
						<title>' . $row2['title'] . '</title>
						<rect width="100%" height="100%" fill="#555"></rect>
						<text x="45%" y="50%" fill="black" dy=".3em">No Image</text></svg>';
        }

        echo '
        <div class="carousel-item'. $active .'">
            <a href="post?name=' . $row2['slug'] . '">' . $image . '</a>
            <div class="carousel-caption d-md-block">
                <h5>
					<a href="post?name=' . $row2['slug'] . '" class="text-light" style="text-shadow: 1px 1px black;">' . $row2['title'] . '</a>
				</h5>
				<p class="text-light" style="text-shadow: 1px 1px black;">
					<i class="fas fa-calendar"></i> ' . date($settings['date_format'], strtotime($row2['date'])) . ', ' . $row2['time'] . '
				</p>
            </div>
        </div>
        ';
        
        $j++;
    }
?>
	</div>
  
	<button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
		<span class="carousel-control-prev-icon" aria-hidden="true"></span>
		<span class="visually-hidden">Previous</span>
	</button>
	<button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
		<span class="carousel-control-next-icon" aria-hidden="true"></span>
		<span class="visually-hidden">Next</span>
	</button>
</div>
<?php
}
?>
            <div class="row <?php echo $mt3_i; ?>">
                <h5><i class="fa fa-list"></i> Recent Posts</h5>
<?php
$run   = mysqli_query($connect, "SELECT * FROM `posts` WHERE active='Yes' ORDER BY id DESC LIMIT 8");
$count = mysqli_num_rows($run);
if ($count <= 0) {
    echo '<p>There are no published posts</p>';
} else {
    while ($row = mysqli_fetch_assoc($run)) {
        
        $image = "";
        if($row['image'] != "") {
            $image = '<img src="' . $row['image'] . '" alt="' . $row['title'] . '" class="card-img-top" width="100%" height="208em" />';
        } else {
            $image = '<svg class="bd-placeholder-img card-img-top" width="100%" height="13em" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false">
            <title>No Image</title><rect width="100%" height="100%" fill="#55595c"/>
            <text x="40%" y="50%" fill="#eceeef" dy=".3em">No Image</text></svg>';
        }
        
        echo '
                    <div class="';
if ($settings['posts_per_row'] == 3) {
	echo 'col-md-4';
} else {
	echo 'col-md-6';
}
echo ' mb-3"> 
                        <div class="card shadow-sm">
                            <a href="post?name=' . $row['slug'] . '">
                                '. $image .'
                            </a>
                            <div class="card-body">
                                <a href="post?name=' . $row['slug'] . '"><h6 class="card-title">' . $row['title'] . '</h6></a>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
										<i class="far fa-calendar-alt"></i> ' . date($settings['date_format'], strtotime($row['date'])) . ', ' . $row['time'] . '
									</small>
									<small><i class="fas fa-comments"></i> 
                                        <a href="post?name=' . $row['slug'] . '#comments" class="blog-comments">
											<strong>' . post_commentscount($row['id']) . '</strong>
										</a>
                                    </small>
                                </div>
								<div class="d-flex justify-content-between align-items-center">
                                    <a href="category?name=' . post_categoryslug($row['category_id']) . '">
										<span class="badge bg-secondary">' . post_category($row['category_id']) . '</span>
									</a>
                                </div>

                                <p class="card-text mt-2">' . short_text(strip_tags(html_entity_decode($row['content'])), 100) . '</p>

								<a href="post?name=' . $row['slug'] . '" class="btn btn-sm btn-primary col-12">
									Read more
								</a>
                            </div>
                        </div>
                    </div>
';
    }
}
?>
            </div>
            <a href="blog" class="btn btn-primary col-12">
				<i class="fas fa-arrow-alt-circle-right"></i> All posts
			</a>
        </div>
<?php
if ($settings['sidebar_position'] == 'Right') {
	sidebar();
}
footer();
?>