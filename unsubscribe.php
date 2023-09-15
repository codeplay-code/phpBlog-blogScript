<?php
include "core.php";
head();
?>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header"><i class="fas fa-envelope"></i> Unsubscribe</div>
                <div class="card-body">
<?php
$email = $_GET['email'];
if (!isset($_GET['email'])) {
    echo '<meta http-equiv="refresh" content="0;url=index.php">';
    exit;
} else {

    $querych = mysqli_query($connect, "SELECT * FROM `newsletter` WHERE email='$email' LIMIT 1");
    if (mysqli_num_rows($querych) == 0) {
        echo '<meta http-equiv="refresh" content="0; url=index.php">';
        exit;
        
    } else {
        $query = mysqli_query($connect, "DELETE FROM `newsletter` WHERE email='$email'");
        echo '<div class="alert alert-primary">You were unsubscribed successfully.</div>';
    }
}
?>
                </div>
        </div>
    </div>
<?php
sidebar();
footer();
?>