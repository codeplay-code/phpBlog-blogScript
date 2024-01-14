<?php
include "core.php";
head();

if ($settings['sidebar_position'] == 'Left') {
	sidebar();
}
?>
            <div class="col-md-8 mb-3">

                <div class="card">
                    <div class="card-header"><i class="fas fa-search"></i> Search</div>
                    <div class="card-body">

<?php
if (isset($_GET['q'])) {
    $word = $_GET['q'];
    
    if (strlen($word) < 2) {
        echo '<div class="alert alert-warning">Enter at least 2 characters to search.</div>';
    } else {
        
        $sql    = "SELECT * FROM posts WHERE active='Yes' AND (title LIKE '%$word%' OR content LIKE '%$word%') ORDER BY id DESC";
        $result = mysqli_query($connect, $sql);
        $row    = mysqli_fetch_assoc($result);
        $count  = mysqli_num_rows($result);
        if ($count == 0) {
            echo '<div class="alert alert-info">No results found.</div>';
        } else {
        
            echo '<div class="alert alert-success">' . $count . ' results for <b>"' . $word . '"</b></div>';

$postsperpage = 8;

$pageNum = 1;
if (isset($_GET['page'])) {
    $pageNum = $_GET['page'];
}
if (!is_numeric($pageNum)) {
    echo '<meta http-equiv="refresh" content="0; url=blog">';
    exit();
}
$rows = ($pageNum - 1) * $postsperpage;

$run   = mysqli_query($connect, "SELECT * FROM `posts` WHERE (title LIKE '%$word%' OR content LIKE '%$word%') AND active='Yes' ORDER BY id DESC LIMIT $rows, $postsperpage");
$count = mysqli_num_rows($run);
if ($count <= 0) {
    echo '<div class="alert alert-info">There are no published posts</div>';
} else {
    while ($row = mysqli_fetch_assoc($run)) {
        
        $image = "";
        if($row['image'] != "") {
            $image = '<img src="' . $row['image'] . '" alt="' . $row['title'] . '" class="rounded-start" width="100%" height="100%">';
        } else {
            $image = '<svg class="bd-placeholder-img rounded-start" width="100%" height="100%" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false">
            <title>No Image</title><rect width="100%" height="100%" fill="#55595c"/>
            <text x="37%" y="50%" fill="#eceeef" dy=".3em">No Image</text></svg>';
        }
        
        echo '
                        <div class="card shadow-sm mb-3">
                            <div class="row g-0">
								<div class="col-md-4">
									<a href="post?name=' . $row['slug'] . '">
										'. $image .'
									</a>
								</div>
								<div class="col-md-8">
									<div class="card-body">
										<div class="d-flex justify-content-between align-items-center row">
											<div class="col-md-9">
												<a href="post?name=' . $row['slug'] . '">
													<h5 class="card-title">' . $row['title'] . '</h5>
												</a>
											</div>
											<div class="col-md-3">
												<a href="category?name=' . post_categoryslug($row['category_id']) . '">
													<span class="badge bg-primary float-end">' . post_category($row['category_id']) . '</span>
												</a>
											</div>
										</div>
										
										<div class="d-flex justify-content-between align-items-center mb-3">
											<small>
												Posted by <b><i><i class="fas fa-user"></i> ' . post_author($row['author_id']) . '</i></b>
												on <b><i><i class="far fa-calendar-alt"></i> ' . date($settings['date_format'], strtotime($row['date'])) . ', ' . $row['time'] . '</i></b>
											</small>
											<small class="float-end"><i class="fas fa-comments"></i>
												<a href="post?name=' . $row['slug'] . '#comments" class="blog-comments"><b>' . post_commentscount($row['id']) . '</b></a>
											</small>
										</div>
										
										<p class="card-text">' . short_text(strip_tags(html_entity_decode($row['content'])), 200) . '</p>
									</div>
								</div>
							</div>
						</div>
';
    }
    
    $query   = "SELECT COUNT(id) AS numrows FROM posts WHERE (title LIKE '%$word%' OR content LIKE '%$word%') AND active='Yes'";
    $result  = mysqli_query($connect, $query);
    $row     = mysqli_fetch_array($result);
    $numrows = $row['numrows'];
    $maxPage = ceil($numrows / $postsperpage);
    
    $pagenums = '';
    
    echo '<center>';
    
    for ($page = 1; $page <= $maxPage; $page++) {
        if ($page == $pageNum) {
            $pagenums .= "<a href='?q=$word&page=$page' class='btn btn-primary'>$page</a> ";
        } else {
            $pagenums .= "<a href=\"?q=$word&page=$page\" class='btn btn-default'>$page</a> ";
        }
    }
    
    if ($pageNum > 1) {
        $page     = $pageNum - 1;
        $previous = "<a href=\"?q=$word&page=$page\" class='btn btn-default'><i class='fa fa-arrow-left'></i> Previous</a> ";
        
        $first = "<a href=\"?q=$word&page=1\" class='btn btn-default'>First</a> ";
    } else {
        $previous = ' ';
        $first    = ' ';
    }
    
    if ($pageNum < $maxPage) {
        $page = $pageNum + 1;
        $next = "<a href=\"?q=$word&page=$page\" class='btn btn-default'><i class='fa fa-arrow-right'></i> Next</a> ";
        
        $last = "<a href=\"?q=$word&page=$maxPage\" class='btn btn-default'>Last</a> ";
    } else {
        $next = ' ';
        $last = ' ';
    }
    
    echo $first . $previous . $pagenums . $next . $last;
    
    echo '</center>';
}
}
}
} else {
    echo '<meta http-equiv="refresh" content="0; url=' . $settings['site_url'] . '">';
    exit();
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