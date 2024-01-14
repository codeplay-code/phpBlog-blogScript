<?php
function generateSeoURL($string, $random_numbers = 1, $wordLimit = 8) { 
    $separator = '-'; 
     
    if($wordLimit != 0){ 
        $wordArr = explode(' ', $string); 
        $string = implode(' ', array_slice($wordArr, 0, $wordLimit)); 
    } 
 
    $quoteSeparator = preg_quote($separator, '#'); 
 
    $trans = array( 
        '&.+?;'                 => '', 
        '[^\w\d _-]'            => '', 
        '\s+'                   => $separator, 
        '('.$quoteSeparator.')+'=> $separator 
    ); 
 
    $string = strip_tags($string); 
    foreach ($trans as $key => $val){ 
        $string = preg_replace('#'.$key.'#iu', $val, $string); 
    } 
 
    $string = strtolower($string); 
	if ($random_numbers == 1) {
		$string = $string . '-' . rand(10000, 99999); 
	}
 
    return trim(trim($string, $separator)); 
}

// Upgrade config.php file
$data = file('../config.php');
function replace_a_line($data) {
	if (stristr($data, '$site_url')) {
		return 'include "config_settings.php";';
	}
	return $data;
}
$data = array_map('replace_a_line', $data);
file_put_contents('../config.php', $data);
echo 'Config file updated <br />';

// Settings migrate to file
include '../config.php';
$query_settings = mysqli_query($connect, "SELECT * FROM settings");
$row_settings   = mysqli_fetch_assoc($query_settings);

if (isset($_SERVER['HTTPS'])) {
    $htp = 'https';
} else {
    $htp = 'http';
}
$fullpath                       = "$htp://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$settings['site_url']           = substr($fullpath, 0, strpos($fullpath, '/install'));
$settings['sitename'] 			= $row_settings['sitename'];
$settings['description']        = $row_settings['description'];
$settings['email']              = $row_settings['email'];
$settings['gcaptcha_sitekey']   = $row_settings['gcaptcha_sitekey'];
$settings['gcaptcha_secretkey'] = $row_settings['gcaptcha_secretkey'];
$settings['head_customcode'] 	= $row_settings['head_customcode'];
$settings['facebook']           = $row_settings['facebook'];
$settings['instagram']          = $row_settings['instagram'];
$settings['twitter']            = $row_settings['twitter'];
$settings['youtube']            = $row_settings['youtube'];
$settings['linkedin']           = $row_settings['linkedin'];
$settings['comments']           = $row_settings['comments'];
$settings['rtl']                = $row_settings['rtl'];
$settings['date_format']        = $row_settings['date_format'];
$settings['layout']             = 'Boxed';
$settings['latestposts_bar']    = $row_settings['latestposts_bar'];
$settings['sidebar_position']   = 'Right';
$settings['posts_per_row']      = '3';
$settings['theme']              = $row_settings['theme'];
$settings['background_image']   = $row_settings['background_image'];

file_put_contents('../config_settings.php', '<?php $settings = ' . var_export($settings, true) . '; ?>');
echo 'Settings table migrated to file-based <br />';

// Generate post slugs
$query_posts = mysqli_query($connect, "SELECT * FROM posts");
while ($row_posts = mysqli_fetch_assoc($query_posts)) {
	$post_id   = $row_posts['id'];
	$post_slug = generateSeoURL($row_posts['title']);
	
	$post_update = mysqli_query($connect, "UPDATE posts SET slug='$post_slug' WHERE id='$post_id'");
}
echo 'Post slugs generated <br />';

// Generate category slugs
$query_categories = mysqli_query($connect, "SELECT * FROM categories");
while ($row_categories = mysqli_fetch_assoc($query_categories)) {
	$category_id   = $row_categories['id'];
	$category_slug = generateSeoURL($row_categories['category'], 0);
	
	$category_update = mysqli_query($connect, "UPDATE categories SET slug='$category_slug' WHERE id='$category_id'");
}
echo 'Category slugs generated <br />';

// Generate page slugs
$query_pages = mysqli_query($connect, "SELECT * FROM pages");
while ($row_pages = mysqli_fetch_assoc($query_pages)) {
	$page_id   = $row_pages['id'];
	$page_slug = generateSeoURL($row_pages['title'], 0);
	
	$page_update = mysqli_query($connect, "UPDATE pages SET slug='$page_slug' WHERE id='$page_id'");
}
echo 'Page slugs generated <br />';

// Update menu paths
$query_menus = mysqli_query($connect, "SELECT * FROM menu WHERE path LIKE '%.php?id=%'");
while ($row_menus = mysqli_fetch_assoc($query_menus)) {
	$menu_id   = $row_menus['id'];
	$menu_path = str_replace(".php?id=", "?name=", $row_menus['path']);

	$menu_update = mysqli_query($connect, "UPDATE menu SET path='$menu_path' WHERE id='$menu_id'");
}

$query_menus2 = mysqli_query($connect, "SELECT * FROM menu WHERE path LIKE '%.php%'");
while ($row_menus2 = mysqli_fetch_assoc($query_menus2)) {
	$menu_id2   = $row_menus2['id'];
	$menu_path2 = str_replace(".php", "", $row_menus2['path']);

	$menu_update2 = mysqli_query($connect, "UPDATE menu SET path='$menu_path2' WHERE id='$menu_id2'");
}
echo 'Menu paths updated <br />';

?>