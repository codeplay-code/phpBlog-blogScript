<?php
include "core.php";

$query = mysqli_query($connect, "SELECT * FROM `posts` WHERE active='Yes' ORDER BY id DESC LIMIT 20");
 
header( "Content-type: text/xml");
 
echo '<?xml version=\'1.0\' encoding=\'UTF-8\'?>
<rss version=\'2.0\'>
	<channel>
		<title>' . $row['sitename'] . ' | RSS</title>
		<link>' . $site_url . '/blog.php</link>
		<description>RSS Feed</description>
		<language>en-us</language>';
 
while($article = mysqli_fetch_array($query)){
	$title       = $article["title"];
	$link        = $site_url . '/post.php?id=' . $article["id"];
	$description = short_text(strip_tags(html_entity_decode($article['content'])), 100);
	$date        = $article["date"];
	$time        = $article["time"];
	$guid        = $article["id"];
	
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