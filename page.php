<?php
include "core.php";
head();

if ($settings['sidebar_position'] == 'Left') {
	sidebar();
}

$slug = $_GET['name'];
if (empty($slug)) {
    echo '<meta http-equiv="refresh" content="0; url=' . $settings['site_url'] . '">';
    exit;
}

$run = mysqli_query($connect, "SELECT * FROM `pages` WHERE slug='$slug' LIMIT 1");
if (mysqli_num_rows($run) == 0) {
    echo '<meta http-equiv="refresh" content="0; url=' . $settings['site_url'] . '">';
    exit;
}

$row = mysqli_fetch_assoc($run);
echo '
            <div class="col-md-8 mb-3">

                <div class="card">
                    <div class="card-header">' . $row['title'] . '</div>
                    <div class="card-body">
                       ' . html_entity_decode($row['content']) . '
                    </div>
                </div>
					
			</div>
';

if ($settings['sidebar_position'] == 'Right') {
	sidebar();
}
footer();
?>