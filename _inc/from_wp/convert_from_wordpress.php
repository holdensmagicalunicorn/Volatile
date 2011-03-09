<?php

//Login into MySQL
$username="user";
$password="pass";
$database="db_name";
$host="localhost";

//////////////////////// DO NO TOUCH ANYTHING BELOW THIS !!!!

include_once(str_replace('//','/',dirname(__FILE__).'/') .'../../config.php');

mysql_connect($host,$username,$password);
@mysql_select_db($database) or die( "Unable to select database");
$query="SELECT * FROM wp_posts where post_status='publish' and post_type='post'";
$result=mysql_query($query);
$num=mysql_numrows($result);

mysql_close();

echo "Done : ";
$done = "";
for($i=0;$i < $num;$i++) {
    $title = mysql_result($result,$i,"post_title");
    $name = mysql_result($result,$i,"post_name");
    $content = mysql_result($result,$i,"post_content");
    $date = mysql_result($result,$i,"post_date");
    $date = strtotime($date);

    $post_content = $title."\n".str_repeat('=', strlen($title))."\n\n";
    $post_content .= $content."\n";
    $filename_date = date("Y-m-d", $date);
    $filename = "$filename_date-$name.md";

    $post_file = ROOT_DIR.'/'.POST_DIR.'/'.$filename;
    $post_fd = fopen($post_file, 'w') or die("can't open file");
    fwrite ($post_fd, $post_content."\n");
    fclose($post_fd);
    //We need to set the mtime attribute properly 
    //as we will use this to build the blog.
    touch($post_file, $date);

    echo str_repeat(chr(8), strlen($done));
    $done = round((($i/$num)*100), 1)."%";
    echo $done;
}

echo str_repeat(chr(8), strlen($done));
echo "100%\n";

?>
