<?php
include "core.php";
head();

if (isset($_POST['database_host'])) {
    $_SESSION['database_host'] = addslashes($_POST['database_host']);
} else {
    $_SESSION['database_host'] = '';
}
if (isset($_POST['database_username'])) {
    $_SESSION['database_username'] = addslashes($_POST['database_username']);
} else {
    $_SESSION['database_username'] = '';
}
if (isset($_POST['database_password'])) {
    $_SESSION['database_password'] = addslashes($_POST['database_password']);
} else {
    $_SESSION['database_password'] = '';
}
if (isset($_POST['database_name'])) {
    $_SESSION['database_name'] = addslashes($_POST['database_name']);
} else {
    $_SESSION['database_name'] = '';
}
?>
			<center><h6>Enter your database connection details. If you’re not sure about these, contact your hosting provider.</h6></center>
            <br />
			
			<form method="post" action="" class="form-horizontal row-border"> 
                        
				<div class="form-group row">
					<p class="col-sm-3">Database Host: </p>
					<div class="col-sm-8">
					<div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-database"></i>
                        </span>
						<input type="text" name="database_host" class="form-control" placeholder="localhost" value="<?php
echo $_SESSION['database_host'];
?>" required>
					</div>
					</div>
				</div>
				<div class="form-group row">
					<p class="col-sm-3">Database Name: </p>
					<div class="col-sm-8">
					<div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-list-alt"></i>
                        </span>
						<input type="text" name="database_name" class="form-control" placeholder="phpblog" value="<?php
echo $_SESSION['database_name'];
?>" required>
					</div>
					</div>
				</div>
				<div class="form-group row">
					<p class="col-sm-3">Database Username: </p>
					<div class="col-sm-8">
					<div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-user"></i>
                        </span>
						<input type="text" name="database_username" class="form-control" placeholder="root" value="<?php
echo $_SESSION['database_username'];
?>" required>
					</div>
					</div>
				</div>
				<div class="form-group row">
					<p class="col-sm-3">Database Password: </p>
					<div class="col-sm-8">
					<div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-key"></i>
                        </span>
						<input type="text" name="database_password" class="form-control" placeholder="" value="<?php
echo $_SESSION['database_password'];
?>">
					</div>
					</div>
				</div><br />
<?php
if (isset($_POST['submit'])) {
    $database_host     = $_POST['database_host'];
    $database_name     = $_POST['database_name'];
    $database_username = $_POST['database_username'];
    $database_password = $_POST['database_password'];
    
    @$db = mysqli_connect($database_host, $database_username, $database_password, $database_name);
    if (!$db) {
        echo '
			    <div class="alert alert-danger">
					Database connecting error, please check your connection parameters.<br />
			    </div>
			   ';
    } else {
        echo '<meta http-equiv="refresh" content="0; url=settings.php" />';
    }
}
?>
				<input class="btn-primary btn col-12" type="submit" name="submit" value="Next" />
				</form>
				</div>
<?php
footer();
?>