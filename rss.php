<?php
include "core.php";

$query = mysqli_query($connect, "SELECT * FROM `posts` WHERE active='Yes' ORDER BY id DESC LIMIT 20");
 
header( "Content-type: text/xml");
 
echo '<?xml version=\'1.0\' encoding=\'UTF-8\'?>
<rss version=\'2.0\'>
	<channel>
		<title>' . $settings['sitename'] . ' | RSS</title>
		<link>' . $settings['site_url'] . '/blog</link>
		<description>RSS Feed</description>
		<language>en-us</language>';
 
while($post = mysqli_fetch_array($query)){
	$title       = $post["title"];
	$link        = $settings['site_url'] . '/post?name=' . $post["slug"];
	$description = short_text(strip_tags(html_entity_decode($post['content'])), 100);
	$date        = $post["date"];
	$time        = $post["time"];
	$guid        = $post["id"];
	
	echo "
	<item>
		<title>$title</title>
		<link>$link</link>
		<!-- <description>$description</description>-->
		<pubDate>$date, $time</pubDate>
		<guid isPermaLink=\"false\">$guid</guid>
	</item>";
 }
 echo "</channel></rss>";
?>