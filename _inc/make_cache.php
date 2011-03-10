<?php
# Author:     Maxime Hadjinlian
#             maxime.hadjinlian@gmail.com
# All rights reserved.
#
# Redistribution and use in source and binary forms, with or without
# modification, are permitted provided that the following conditions
# are met:
# 1. Redistributions of source code must retain the above copyright
#    notice, this list of conditions and the following disclaimer.
# 2. Redistributions in binary form must reproduce the above copyright
#    notice, this list of conditions and the following disclaimer in the
#    documentation and/or other materials provided with the distribution.
# 3. The name of the author may not be used to endorse or promote products
#    derived from this software without specific prior written permission.
#
# THIS SOFTWARE IS PROVIDED BY THE AUTHOR ``AS IS'' AND ANY EXPRESS OR
# IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES
# OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED.
# IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY DIRECT, INDIRECT,
# INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT
# NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
# DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
# THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
# (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF
# THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

/*
Here we will create the 'cache'.
The cache is your whole blog in plain html so we only load html.
PHP is only here to generate stuff, not display anything.
*/
require_once( ROOT_DIR.'/'.INC_DIR.'/functions.php');

function write_cache(){

    date_default_timezone_set(TIMEZONE);
    $header = shell_exec('php -q '.ROOT_DIR.'/'.LAYOUT_DIR.'/header.php');
    $sidebar = shell_exec('php -q '.ROOT_DIR.'/'.LAYOUT_DIR.'/sidebar.php');
    $footer = shell_exec('php -q '.ROOT_DIR.'/'.LAYOUT_DIR.'/footer.php');

    // Create cache for each year   
    $flat_year = create_data_info("year");
    $year = array_keys($flat_year);
    $size = sizeOf($year);
    for ($i=0; $i<$size; $i++){
        create_pages($flat_year[$year[$i]], 
                     sizeOf($flat_year[$year[$i]]), 
                     $year[$i],
                     $header, $sidebar, $footer);
    }
    unset($flat_year);

    // Create cache for each year/month (the key of the array is yearmonth so we can split it.)
    $flat_month = create_data_info("month");
    $month_per_year = array_keys($flat_month);
    $size = sizeOf($month_per_year);
    for ($i=0; $i<$size; $i++){
        $month = substr($month_per_year[$i], -2);
        $year = substr($month_per_year[$i], 0, 4);
        create_pages($flat_month[$month_per_year[$i]], 
                     sizeOf($flat_month[$month_per_year[$i]]), 
                     $year.'/'.$month,
                     $header, $sidebar, $footer);
    }
    unset($flat_month);

    // Create the cache for each day.
    $flat_day = create_data_info("day");
    $day_per_month = array_keys($flat_day);
    $size = sizeOf($day_per_month);
    for ($i=0; $i<$size; $i++){
        $day = substr($day_per_month[$i], -2);
        $month = substr($day_per_month[$i], 4, 2);
        $year = substr($day_per_month[$i], 0, 4);
        create_pages($flat_day[$day_per_month[$i]], 
                     sizeOf($flat_day[$day_per_month[$i]]), 
                     $year.'/'.$month.'/'.$day,
                     $header, $sidebar, $footer);
    }
    unset($flat_day);

    // Create cache per post
    $flat_post = create_data_info("post");
    // Create the index page that will create the "main" pages of the blog
    create_pages($flat_post, 
                 sizeOf($flat_post), 
                 "",
                 $header, $sidebar, $footer);

    $size = sizeOf($flat_post);
    for ($i=0; $i<$size; $i++) {
        $title = urlencode($flat_post[$i][0]);
        $title = strtolower(str_replace('+','_',$title));
        $filepath = ROOT_DIR.'/'.CACHE_DIR.'/'.$title."_0.html";
        create_page_content($filepath, 
                            $flat_post[$i], 
                            $header, 
                            $sidebar, 
                            $footer,
                            true
                           );
    }
    unset($flat_post);

}

function create_pages($data_post, $nb_post, $path, $header, $sidebar, $footer){
    $data_post = array_reverse($data_post, true);

    //Create the HTML file for the day.
    $nb_page = (int)ceil($nb_post/POST_PER_PAGE);
    for ($i=0; $i<$nb_page; $i++){
        if ( isSet($data_post[0]) && @is_array($data_post[0]) ){
            $to_include = array_slice($data_post, POST_PER_PAGE*$i, POST_PER_PAGE);
        }else{
            $to_include = $data_post;
        }
        $filepath = ROOT_DIR.'/'.CACHE_DIR.'/'."$path/index_$i.html";
        $footer_pag = $footer;
        if (WITH_PAGINATOR)
            $footer_pag = create_paginator($i+1, $nb_page, $path).$footer;
        create_page_content($filepath, $to_include, $header, $sidebar, $footer_pag);
    }
}

function create_page_content($filepath, $post_file, 
                             $header, $sidebar, $footer, 
                             $is_page=false){

    $content = "$header\n\n";
    if ( isSet($post_file[0]) && @is_array($post_file[0]) ) {
        $size = sizeOf($post_file);

        // Check if the cache page is more recent than all the posts
        $last_mtime = 0;
        for ($i=0; $i<$size; $i++)
            $last_mtime = ($post_file[$i][4] > $last_mtime) ? $post_file[$i][4] : $last_mtime;

        if ( file_exists($filepath) && ($last_mtime < filemtime($filepath))  ){
            unset($post_file);
            return NULL;
        }

        for ($i=0; $i<$size; $i++)
            $content .= post_to_html($post_file[$i], True, True);
    }else{
        // only one post to write
        // Check if the cache page is more recent than the posts
        if ( file_exists($filepath) && ($post_file[4] < filemtime($filepath))  )
                return NULL;
        $content .= post_to_html($post_file, True, False);
        if ( $is_page && defined('DISQUS_SHORTNAME') && ( DISQUS_SHORTNAME !== '' ) )
            add_disqus(&$content, $post_file[0]);
    }
    unset($post_file);

    $content .= "$sidebar\n\n";
    $content .= "$footer\n\n";

    if (HTML_INLINE)
        $content =  str_replace(array("\r\n","\n","\r")," ",$content);

    if ( !file_exists(dirname($filepath)) )
        @(mkdir( dirname($filepath), 0777, true)) OR die ("Can't make ".dirname($filepath).". Please, check your rights.\n");
    $cache_fd = fopen($filepath, 'w') or die("can't open $filepath file\n");
    fwrite ($cache_fd, $content);
    fclose($cache_fd);
    unset($content);
}

?>
