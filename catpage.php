<?php
/*
Plugin Name: Category Displayer 
Plugin URI: http://craigfarnes.co.uk/
Description: Display catergorys on a page with images and extracts of the post.
Author: Craig Farnes
Version: 1.0
Author URI: http://craigfarnes.co.uk/
*/

add_filter( "the_content", "catpagecontent" ); 
function catpagecontent($content){
global $wpdb;
$homefind=$wpdb->get_row("SELECT FROM * wp_options WHERE option_id=32");
$home = $homefind->option_value;
$content1=explode("[cat=",$content,2);
$start=strpos($content,"[cat="); 
if($start !== false){
$remove=strpos($content1[1],"]")+1;
$remove2=strpos($content1[1],"]");
$number=str_split($content1[1],$remove2);
$content1[1]=substr($content1[1], $remove);
$content1[2]=$content1[1];

$content=$number[0];
$categories=  get_posts("cat=$number[0]");
$j=0;
foreach ($categories as $categorie){
$post[$j]=$categorie->ID;
$j++;
}
$content1[1]="<br>";
foreach($post as $posts){
$info = $wpdb->get_row("SELECT * FROM wp_posts WHERE ID=$posts");
$title = $info->post_title;
$id=$info->ID;
$content = $info->post_content;

$imagecontainstart = strpos($content,'<img');
if($imagecontainstart !== false){
$imagecontainend = strpos($content,'/>',$imagecontainstart);
$imagecontainend2 = strpos($content,'/>',$imagecontainstart)+2;
$imagestart = strpos($content,'src=',$imagecontainstart);
$imagestart2 = strpos($content,'src=',$imagecontainstart)+5;
$imageend = strpos($content,'"',$imagestart2);
$length=$imageend-$imagestart2;
$image=substr($content,$imagestart2,$length);

$content2 = substr($content,0,$imagecontainstart);
$content3 = substr($content,$imagecontainend2);
$content=$content2.$content3;


}
$content = substr($content,0,200);
$content = $content."...";
if($imagecontainstart !== false){
$content1[1] =$content1[1]."<h1>".$title."</h1><br />".'<a href="'.$image.'"><img class="alignnone size-medium wp-image-16"  src="'.$image.'"  /></a><br />'.$content."<a href='".$home."/?p=".$id."'>View More</a><br />";
}else{
$content1[1] =$content1[1]."<h4>".$title."</h4><br />".$content."<br />";
}
}
$content = $content1[0].$content1[1].$content1[2];
}
return($content);
}
?>