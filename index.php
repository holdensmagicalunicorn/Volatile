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

include_once(str_replace('//','/',dirname(__FILE__).'/') .'config.php');
require_once( ROOT_DIR.'/'.INC_DIR.'/functions.php');

$htaccess = ROOT_DIR.'/.htaccess';
if ( !file_exists($htaccess) )
    create_htaccess($htaccess);

$path = ROOT_DIR.'/'.CACHE_DIR.'/';
if (isSet($_GET['year']) && ( $_GET['year'] !== "" ))
    $path .= $_GET['year']."/";

if (isSet($_GET['month']) && ( $_GET['month'] !== "" ))
    $path .= $_GET['month']."/";

if (isSet($_GET['day']) && ( $_GET['day'] !== "" ))
    $path .= $_GET['day']."/";

$index = 'index';
if (isSet($_GET['title']) && ($_GET['title'] !== "index.php"))
    $index = $_GET['title'];
$index = str_replace('/', '', $index);

$page_number = 0;
if (isSet($_GET['page']) && ( $_GET['page'] !== "" ) )
    $page_number = $_GET['page']-1;

$path = str_replace('//','/',$path.'/');
$path = $path.$index.'_'.$page_number.'.html';

if ( file_exists($path) ){
    readfile($path);
}else{
    header("HTTP/1.0 404 Not Found");
    if ( defined('ERROR_PAGE') && file_exists(ROOT_DIR.ERROR_PAGE) ){
        readfile(ROOT_DIR.ERROR_PAGE);
    }else{
        echo "<h1>404 Not Found</h1>";
        echo "The page that you have requested could not be found.";
    }
}

?>
