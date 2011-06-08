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

require_once( ROOT_DIR.'/'.INC_DIR.'/functions.php');

function write_feed(){

    date_default_timezone_set(TIMEZONE);
    $xml_feed = ROOT_DIR.'/_atom.xml';
    $flat_posts = create_data_info("post");

    //Check if we need to create the feed or not
    $last_mtime = 0;
    $size = sizeOf($flat_posts)-1;
    $bound = $size-POST_RSS;
    for ($i=$size; $i>$bound; $i--){
        if ( empty($flat_posts[$i]) ) continue;
        $last_mtime = ($flat_posts[$i][4] > $last_mtime) ? $flat_posts[$i][4] : $last_mtime;
    }

    if ( file_exists($xml_feed) && ( $last_mtime < filemtime($xml_feed) )  ){
        unset($flat_posts);
        return NULL;
    }

    $now = date('c');
    $content = "<?xml version='1.0' encoding='utf-8'?>\n";
    $content .= "<feed xmlns='http://www.w3.org/2005/Atom'>\n";
    $content .= "<title>".FEED_TITLE."</title>\n";
    $content .= "<link href='".URL."' />\n";
    $content .= "<link href='".URL."/feed/' rel='self' />\n";

    $base_url = str_replace(array('http://','https://'), '', URL);
    $base_url = str_replace("#", "/", $base_url);
    $content .= "<id>tag:$base_url,".date("Y-m-d").":/</id>\n";

    $content .= "<updated>$now</updated>\n";
    $content .= "<author>\n";
    $content .= "<name>".AUTHOR_NAME."</name>\n";
    $content .= "<email>".AUTHOR_MAIL."</email>\n";
    $content .= "</author>\n";

    for ($i=$size; $i>$bound; $i--){
        if ( empty($flat_posts[$i]) ) continue;
        $filename = $flat_posts[$i][2];
        $url_name = URL.'/'.$flat_posts[$i][0];
        $title = $flat_posts[$i][3];
        $date = date("d F Y H:i:s", $flat_posts[$i][4]);
        $tag_date = $flat_posts[$i][5].'-'.$flat_posts[$i][6].'-'.$flat_posts[$i][7];

        $content .= "<entry>"."\n";

        $content .= "<title>$title</title>"."\n";
        $content .= "<link rel='alternate' type='text/html' href='$url_name'/>"."\n";

        $content .= "<id>tag:$base_url,".$tag_date.":/".$flat_posts[$i][0]."</id>\n";

        $content .= "<updated>";
        $date_rfc3339 = date('c', $flat_posts[$i][4]);
        $content .= "$date_rfc3339";
        $content .= "</updated>"."\n";

        $post_html = post_to_html($flat_posts[$i], False, False);
        $content .= "<content type='html'>\n<![CDATA[$post_html]]>\n</content>"."\n";
        unset($post_html);

        $content .= "</entry>"."\n\n";
    }

    $content .= "</feed>\n";

    $xml_fd = fopen($xml_feed, 'w') or die("can't open $xml_feed\n");
    fwrite ($xml_fd, $content);
    fclose($xml_fd);
    chmod($xml_feed, 0755);

}

?>
