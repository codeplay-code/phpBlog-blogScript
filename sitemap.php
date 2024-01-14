<?php
include "core.php";

header('Content-type: application/xml');

$get_result = mysqli_query($connect, "SELECT * FROM `menu` WHERE path != 'index.php'");

echo "<?xml version='1.0' encoding='UTF-8'?>"."\n";
echo "<urlset xmlns='http://www.sitemaps.org/schemas/sitemap/0.9'>"."\n";

	echo "<url>";
	echo '<loc>' . $settings['site_url'] . '/</loc>';
	echo "<changefreq>always</changefreq>";
	echo "<priority>1.0</priority>";
	echo "</url>";

while($link = mysqli_fetch_array($get_result)) {
	echo "<url>";
	echo '<loc>' . $settings['site_url'] . '/' . $link['path'] . '</loc>';
	echo "<changefreq>always</changefreq>";
	echo "<priority>1.0</priority>";
	echo "</url>";
}

$categories = mysqli_query($connect, "SELECT * FROM `categories`");
while($cat = mysqli_fetch_array($categories)) {
	echo "<url>";
	echo '<loc>' . $settings['site_url'] . '/category?name=' . $cat['slug'] . '</loc>';
	echo "<changefreq>always</changefreq>";
	echo "<priority>0.7</priority>";
	echo "</url>";
}

	echo "<url>";
	echo '<loc>' . $settings['site_url'] . '/login</loc>';
	echo "<changefreq>yearly</changefreq>";
	echo "<priority>0.8</priority>";
	echo "</url>";

echo "</urlset>";

?>