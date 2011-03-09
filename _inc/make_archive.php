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

require_once ROOT_DIR."/".INC_DIR."/functions.php";

function write_archive(){

    $header = shell_exec('php -q '.ROOT_DIR.'/'.LAYOUT_DIR.'/header.php');
    $footer = shell_exec('php -q '.ROOT_DIR.'/'.LAYOUT_DIR.'/footer.php');
    $sidebar = shell_exec('php -q '.ROOT_DIR.'/'.LAYOUT_DIR.'/sidebar.php');

    $content = "$header\n\n";

    $content .= "<h3>Archives</h3>\n";
    $content .= "<br/><br/>\n";
    $content .= "<hr/>\n\n";

    $archive = array_reverse(create_data_info(NULL), true);
    $year = array_keys($archive);
    $size = sizeOf($year);
    for ($y=0; $y<$size; $y++){
        $content .= "<h2 class='archives'><a href='".URL."/$year[$y]'>$year[$y]</a></h2>\n\n";
        //Reverse order for the month too...
        $archive[$year[$y]] = array_reverse($archive[$year[$y]], true);
        $month = array_keys($archive[$year[$y]]);
        $size_month = sizeOf($month);
        for ($m=0; $m<$size_month; $m++){
            $date = @date( 'F', @mktime(0, 0, 0, $month[$m]));
            $content .= "<h3 class='archives'><a href='".URL.'/'.$year[$y].'/'.$month[$m]."'>$date</a></h3>\n\n";
            $content .= "<ul class='archive-page'>\n";
            $day = array_reverse(array_keys($archive[$year[$y]][$month[$m]]), true);
            $size_day = sizeOf($day);
            for ($d=0; $d<$size_day; $d++){
                $size_day_posts = sizeOf($day[$d]);
                for ($p=0; $p<$size_day_posts; $p++){
                    $filetitle = urlencode($archive[$year[$y]][$month[$m]][$day[$d]][$p][0]);
                    $title = $archive[$year[$y]][$month[$m]][$day[$d]][$p][3];
                    $filetitle = str_replace('+','_',$filetitle);
                    $content .= "<li><a href='".URL."/$filetitle' title='$title'>$title</a></li>\n";
                }
            }
            $content .= "</ul>\n\n";
        }
    }
    unset($archive);

    $content .= "$sidebar\n\n";
    $content .= "$footer\n\n";

    $archive_file = ROOT_DIR.'/'.CACHE_DIR.'/archive.html';
    $archive_fd = fopen($archive_file, 'w') or die("can't open file $archive_file");
    fwrite ($archive_fd, $content);
    fclose($archive_fd);
    chmod($archive_file, 0755);
}

?>
