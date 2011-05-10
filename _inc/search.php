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

function search_posts($query){

    $result_array = array();

    $search_cmd = 'grep -l ';
    $output = shell_exec($search_cmd.escapeshellarg($query)." ".ROOT_DIR.'/'.POST_DIR."/*.md");
    $output = preg_split( '/\r\n|\r|\n/', $output );

    $size = sizeOf($output);
    for ($s=0; $s<$size; $s++){
        if ( "" === $output[$s] )
            continue;
            
        $filename = basename($output[$s], ".md");
        $filename = explode("-", $filename, 4);
         
        $post_content = file_get_contents($output[$s], FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $title = strstr($post_content, "\n", true);
        unset($post_content);

        $result_array[] = array("url" => URL."/".urlencode($filename[3]), "title" => $title);
    }

    return $result_array;
}

?>
