<?php
include "core.php";
head();

$queryst = mysqli_query($connect, "SELECT date_format FROM `settings` LIMIT 1");
$rowst   = mysqli_fetch_assoc($queryst);

$category_id = (int) $_GET['id'];
$runq        = mysqli_query($connect, "SELECT * FROM `categories` WHERE id='$category_id'");
$rw          = mysqli_fetch_assoc($runq);

if (empty($category_id)) {
    echo '<meta http-equiv="refresh" content="0; url=blog.php">';
    exit();
}

if (mysqli_num_rows($runq) == 0) {
    echo '<meta http-equiv="refresh" content="0; url=blog.php">';
    exit();
}
?>
            <div class="col-md-8">

                <div class="card">
                    <div class="card-header"><i class="far fa-file-alt"></i> Blog - <?php
echo $rw['category'];
?></div>
                    <div class="card-body">

<?php
$postsperpage = 6;

$pageNum = 1;
if (isset($_GET['page'])) {
    $pageNum = $_GET['page'];
}
if (!is_numeric($pageNum)) {
    echo '<meta http-equiv="refresh" content="0; url=blog.php">';
    exit();
}
$rows = ($pageNum - 1) * $postsperpage;

$run   = mysqli_query($connect, "SELECT * FROM `posts` WHERE category_id='$category_id' and active='Yes' ORDER BY id DESC LIMIT $rows, $postsperpage");
$count = mysqli_num_rows($run);
if ($count <= 0) {
    echo '<div class="alert alert-info">There are no published posts</div>';
} else {
    while ($row = mysqli_fetch_assoc($run)) {
        
        $image = "";
        if($row['image'] != "") {
            $image = '<img src="' . $row['image'] . '" alt="' . $row['title'] . '" class="card-img-top" width="100%" height="225"">';
        } else {
            $image = '<svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false">
            <title>No Image</title><rect width="100%" height="100%" fill="#55595c"/>
            <text x="46%" y="50%" fill="#eceeef" dy=".3em">No Image</text></svg>';
        }
        
        echo '
                        <div class="card shadow-sm">
                            <a href="post.php?id=' . $row['id'] . '">
                                '. $image .'
                            </a>
                            <div class="card-body">
                                <a href="post.php?id=' . $row['id'] . '">
									<h5 class="card-title">' . $row['title'] . '</h5>
								</a>
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="category.php?id=' . $row['category_id'] . '">
										<span class="badge bg-primary">' . post_category($row['category_id']) . '</span>
									</a>
                                    <small><i class="fas fa-comments"></i> Comments: 
                                        <a href="post.php?id=' . $row['id'] . '#comments" class="blog-comments">
											<strong>' . post_commentscount($row['id']) . '</strong>
										</a>
                                    </small>
                                </div>
                                <p class="card-text">' . short_text(strip_tags(html_entity_decode($row['content'])), 400) . '</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <b><i class="fas fa-user-edit"></i> ' . post_author($row['author_id']) . '</b>
                                    <small class="text-muted">
										<i class="far fa-calendar-alt"></i> ' . date($rowst['date_format'], strtotime($row['date'])) . ', ' . $row['time'] . '
									</small>
                                </div>
                            </div>
                        </div><br />
';
    }
    
    $query   = "SELECT COUNT(id) AS numrows FROM posts WHERE category_id='$category_id' and active='Yes'";
    $result  = mysqli_query($connect, $query);
    $row     = mysqli_fetch_array($result);
    $numrows = $row['numrows'];
    $maxPage = ceil($numrows / $postsperpage);
    
    $pagenums = '';
    
    echo '<center>';
    
    for ($page = 1; $page <= $maxPage; $page++) {
        if ($page == $pageNum) {
            $pagenums .= "<a href='?id=$category_id&page=$page' class='btn btn-primary'>$page</a> ";
        } else {
            $pagenums .= "<a href=\"?id=$category_id&page=$page\" class='btn btn-default'>$page</a> ";
        }
    }
    
    if ($pageNum > 1) {
        $page     = $pageNum - 1;
        $previous = "<a href=\"?id=$category_id&page=$page\" class='btn btn-default'><i class='fa fa-arrow-left'></i> Previous</a> ";
        
        $first = "<a href=\"?id=$category_id&page=1\" class='btn btn-default'><i class='fa fa-arrow-left'\></i> <i class='fa fa-arrow-left'></i> First</a> ";
    } else {
        $previous = ' ';
        $first    = ' ';
    }
    
    if ($pageNum < $maxPage) {
        $page = $pageNum + 1;
        $next = "<a href=\"?id=$category_id&page=$page\" class='btn btn-default'><i class='fa fa-arrow-right'></i> Next</a> ";
        
        $last = "<a href=\"?id=$category_id&page=$maxPage\" class='btn btn-default'><i class='fa fa-arrow-right'></i>  <i class='fa fa-arrow-r'></i> Last</a> ";
    } else {
        $next = ' ';
        $last = ' ';
    }
    
    echo $first . $previous . $pagenums . $next . $last;
    
    echo '</center>';
}
?>

                    </div>
                </div>
                
            </div>
<?php
sidebar();
footer();
?>