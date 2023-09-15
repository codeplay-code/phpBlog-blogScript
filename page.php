<?php
include "core.php";
head();

$id = (int) $_GET['id'];
if (empty($id)) {
    echo '<meta http-equiv="refresh" content="0;url=index.php">';
    exit;
}

$run = mysqli_query($connect, "SELECT * FROM `pages` WHERE id='$id' LIMIT 1");
if (mysqli_num_rows($run) == 0) {
    echo '<meta http-equiv="refresh" content="0; url=index.php">';
    exit;
}

$row = mysqli_fetch_assoc($run);
echo '
            <div class="col-md-8">

                <div class="card">
                    <div class="card-header">' . $row['title'] . '</div>
                    <div class="card-body">
                       ' . html_entity_decode($row['content']) . '
                    </div>
                </div>
					
			</div>
';

sidebar();
footer();
?>