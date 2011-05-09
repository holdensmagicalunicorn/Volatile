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

function create_data_info($flat=NULL){

    $data = array();
    $files = glob(ROOT_DIR.'/'.POST_DIR.'/*.md');
    $size = sizeOf($files);
    $existing_title = array();

    if (isSet($flat)){
        if ( "year" === $flat ){
            for ($i = 0; $i < $size; $i++) {
                $info = extract_info($files[$i], $existing_file);
                //Create the data array with all the data ordered by year/month/day
                $data[(int)$info[5]][] = $info;
            }
        }elseif ( "month" === $flat ){
            for ($i = 0; $i < $size; $i++) {
                $info = extract_info($files[$i], $existing_file);
                $key = $info[5].$info[6];
                //Create the data array with all the data ordered by year/month/day
                $data[(int)$key][] = $info;
            }
        }elseif ( "day" === $flat ){
            for ($i = 0; $i < $size; $i++) {
                $info = extract_info($files[$i], $existing_file);
                $key = $info[5].$info[6].$info[7];
                //Create the data array with all the data ordered by year/month/day
                $data[(int)$key][] = $info;
            }
        }elseif ( "post" === $flat ){
            for ($i = 0; $i < $size; $i++) {
                //Create the data array with all the data ordered by year/month/day
                $data[] = extract_info($files[$i], $existing_file);
            }
        }
    }else{
        for ($i = 0; $i < $size; $i++) {
            if (empty($files[$i])) continue;
            $info = extract_info($files[$i], $existing_file);
            //Create the data array with all the data ordered by year/month/day
            $data[(int)$info[5]][(int)$info[6]][(int)$info[7]][] = $info;
        }
    }
    unset($existing_file);
    unset($files);
    unset($size);
    return $data;
}

function extract_info($file, &$existing){
    $full_path_file = $file;
    $file = basename($file);
    $info_file = explode("-", $file, 4);

    $filetitle = explode(".", $info_file[3]);
    $info[0] = $filetitle[0];

    if (!isSet($existing[$info[0]]))
        $existing[$info[0]] = -1;
    $existing[$info[0]] += 1;
    if ($existing[$info[0]] > 0)
        //We have already found a post with this title
        //the creation of the cache is based on info[4] data for the filename
        //so we need to tune it
        $info[0] = $info[0]."-".$existing[$info[0]];

    $info[1] = $info_file[3];
    $info[2] = $full_path_file;
    $post_content = file(ROOT_DIR.'/'.POST_DIR.'/'.$file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $info[3] = $post_content[0];
    unset($post_content);

    $info[4] = filemtime(ROOT_DIR.'/'.POST_DIR.'/'.$file);

    $info[5] = $info_file[0]; //year
    $info[6] = $info_file[1]; //month
    $info[7] = $info_file[2]; //day
    return $info;
}

function post_to_html($post, $with_title=True, $with_more=True){

    include_once ROOT_DIR.'/'.INC_DIR."/php-markdown/markdown.php";
    $filename = $post[2];
    $title = $post[3];
    $url_name = URL.'/'.str_replace('+','_',urlencode($post[0]));

    $filename_ar = explode('/', $filename);
    $filename_short = $filename_ar[count($filename_ar)-1];
    $date = explode('-', $filename_short);
    $date[1] = @date( 'F', @mktime(0, 0, 0, $date[1]));
    $date = $date[2]." ".$date[1]." ".$date[0];

    $offset = (strlen($title)*2)+1; // we don't want to read the title.

    $content = "<div id='post' class='item entry'>\n";
    if ( $with_title ){
        $content .= "<div class='itemhead'>\n";
        $content .= "<h3><a href='$url_name' rel='bookmark' title='$title'>$title</a></h3>\n";
        $content .= "<p class='metadata'>$date</p>\n</div>\n";
    }
    $content .= "<div class='itemtext'>\n";
    $post_content = file_get_contents($filename, FILE_USE_INCLUDE_PATH, NULL, $offset);
    if ( $with_more ) {
        $more_static = "<!--more-->";
        // Cut the article at the line
        $where_to_cut = strpos($post_content, $more_static);
        if ( !$where_to_cut == false ){
            $post_content = substr($post_content, 0, $where_to_cut);
            // And add link to full page
            $post_content .= "\n</br><a href='$url_name'>Read the rest of this entry &raquo;</a></br>\n";
        }
    }
    $content .= Markdown($post_content)."\n\n";
    unset($post_content);
    $content .= "</div>\n</div>\n";

    return $content;
}

function create_htaccess($htaccess){
    $htaccess_fd = fopen($htaccess, 'w') or die("can't open file");

    $content = '<FilesMatch "\.(php?)$">'."\n";
    $content .= "php_flag zlib.output_compression off\n";
    $content .= "</FilesMatch>\n";

    $content .= "# 480 weeks\n";
    $content .= '<FilesMatch "\.(ico|pdf|flv|jpg|jpeg|png|gif|js|css|swf)$">'."\n";
    $content .= 'Header set Cache-Control "max-age=290304000, public"'."\n";
    $content .= "</FilesMatch>\n";
    $content .= "# 2 HOURS\n";
    $content .= '<FilesMatch "\.(html|htm)$">'."\n";
    $content .= 'Header set Cache-Control "max-age=7200, must-revalidate"'."\n";
    $content .= "</FilesMatch>\n";

    $content .= "<IfModule mod_rewrite.c>\n";
    $content .= "RewriteEngine On\n";

    if ( defined("OLD_SLUG") && OLD_SLUG !== "" ){
        $content .= "RewriteCond %{REQUEST_FILENAME} !-d\n";
        $content .= "RewriteCond %{REQUEST_FILENAME} !-f\n";
        $content .= OLD_SLUG."\n";
    }

    $content .= "RewriteRule ^([0-9]{4})/([0-9]{2})/([0-9]{2})/(\w*\b)(/?$) index.php?title=$4 [L,QSA]\n";
    $content .= "RewriteRule ^([0-9]{4})(/([0-9]{2})(/([0-9]{2}))?)?(/page/([0-9]+))?/?\s*$ index.php?year=$1&month=$3&day=$5&page=$7 [L,QSA]\n";

    $content .= "RewriteRule ^(archives\b)(/?$) _cache/archive.html [L,QSA]\n";
    $content .= "RewriteRule ^(feed\b)(/?$) /_atom.xml [L,QSA]\n";
    $content .= "RewriteRule ^(preview\b)(/?$) _inc/preview.php [L,QSA]\n";

    $custom_rules = unserialize(REWRITERULES);
    $size = sizeOf($custom_rules);
    for ($i=0;$i<$size;$i++)
        $content .= $custom_rules[$i]."\n";

    $content .= "RewriteRule ^page/([0-9]+)(/?$) index.php?page=$1 [L,QSA]\n";

    $content .= "RewriteCond %{REQUEST_FILENAME} !-d\n";
    $content .= "RewriteCond %{REQUEST_FILENAME} !-f\n";
    $content .= "RewriteRule ^([^_].*)(/?$) index.php?title=$1 [L,QSA]\n";

    $content .= "</IfModule>\n";

    if ( defined("USER") && defined("PASSWORD") ){
        $content .= "AuthUserFile ".ROOT_DIR."/.htpasswd\n";
        $content .= "AuthType Basic\n";
        $content .= "AuthName 'seiteki'\n";
        $content .= "<Files 'update.php'>\n";
        $content .= "Require valid-user\n";
        $content .= "</Files>\n";

        //Write .htpassword file
        $htpwd_fd = fopen(ROOT_DIR."/.htpasswd", 'w') or die("can't open file");
        fwrite($htpwd_fd, USER.":".PASSWORD."\n");
        fclose($htpwd_fd);
        chmod(ROOT_DIR."/.htpasswd", 0755);
    }

    fwrite ($htaccess_fd, $content);
    fclose($htaccess_fd);
    chmod($htaccess, 0755);

}

function create_paginator($currentpage, $nb_items, $path){
    require_once ROOT_DIR.'/'.INC_DIR.'/paginator-digg/pagination.php';

    $p = new pagination;
    $p->items($nb_items);
    $p->currentPage($currentpage);
    $p->limit(1);
    $p->urlFriendly();
    $p->target(URL."/$path/page/%/");
    if ( "" === $path )
        $p->target(URL."/page/%/");
    $p->adjacents(1);

    if (!$p->calculate)
        if($p->calculate())
            return "<div class=\"$p->className\">$p->pagination</div>";

}

function add_disqus(&$content, $title){

    $title = str_replace('+','_',urlencode($title));
    $content .= '<div id="disqus_thread"></div>'."\n";
    $content .= '<script type="text/javascript">'."\n";
    $content .= "var disqus_shortname = '".DISQUS_SHORTNAME."';\n";
    //$content .= "var disqus_identifier = '".$title."/';\n";
    $content .= "var disqus_url = '".URL."/".$title."/';\n";
    $content .= "(function() {\n";
    $content .= "var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;\n";
    $content .= "dsq.src = 'http://' + disqus_shortname + '.disqus.com/embed.js';\n";
    $content .= "(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);\n";
    $content .= " })();\n";
    $content .= "</script>\n";
    $content .= "<noscript>Please enable JavaScript to view the <a href='http://disqus.com/?ref_noscript'>comments powered by Disqus.</a></noscript>";
}

?>
