<?php
include "../config.php";

session_start();

if (isset($_SESSION['sec-username'])) {
    $uname = $_SESSION['sec-username'];
    $suser = mysqli_query($connect, "SELECT * FROM `users` WHERE username='$uname' AND (role='Admin' || role='Editor')");
    $count = mysqli_num_rows($suser);
    if ($count <= 0) {
        echo '<meta http-equiv="refresh" content="0; url=../login" />';
        exit;
    } else {
        echo '<meta http-equiv="refresh" content="0; url=dashboard.php" />';
    }
} else {
    echo '<meta http-equiv="refresh" content="0; url=../login" />';
    exit;
}
?>