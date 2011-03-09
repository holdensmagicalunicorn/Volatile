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


$total = microtime(true);

include_once (str_replace('//','/',dirname(__FILE__).'/') .'config.php');

function rrmdir($path){
  return is_file($path)?
    @unlink($path):
    array_map('rrmdir',glob($path.'/*'))===@rmdir($path)
  ;
}

if (isSet($_GET['clean']) && '1' === $_GET['clean']){
    //Clean the cache before rebuilding it
    rrmdir(ROOT_DIR."/".CACHE_DIR."/");
    echo "The cache folder (".ROOT_DIR."/".CACHE_DIR."/) was deleted.<br>\n";
}

if ( !file_exists(ROOT_DIR."/".CACHE_DIR."/") )
    @(mkdir( ROOT_DIR."/".CACHE_DIR."/", 0777, true)) OR die ("Can't make ".ROOT_DIR."/".CACHE_DIR.". Please, check your rights.\n");

//This file will re create your archives and generate your cache.
include ROOT_DIR."/".INC_DIR."/make_archive.php";
$t1 = microtime(true);
write_archive();
echo 'Time write archive: ' , (microtime(true) - $t1) , "<br>", "\n";
echo 'The archive has been created'."<br>\n";

include ROOT_DIR.'/'.INC_DIR."/make_cache.php";
$t1 = microtime(true);
set_time_limit(0);
write_cache();
set_time_limit(30);
echo 'Time make cache: ' , (microtime(true) - $t1) , "<br>", "\n";
echo 'The cache has been created'."<br>\n";

include ROOT_DIR.'/'.INC_DIR."/make_feed.php";
$t1 = microtime(true);
write_feed();
echo 'Time make feed: ' , (microtime(true) - $t1) , "<br>", "\n";
echo 'The RSS file has been created'."<br>\n";

echo 'The blog is updated, you may close this.'."<br>\n";

echo 'Total: ' , (microtime(true) - $total) , "<br>\n";

?>
