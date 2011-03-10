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

//Define few vars usefull when generating archives and searching
// NO SLASH AT THE END OF ANY PATH

// Blog info
define('URL', "http://myblog.com");

    // Custom your display
    define('POST_PER_PAGE', 10);
    define('POST_RSS', 10);

    //Extra
    define('HTML_INLINE', false); // Do you want your HTML inline or not ? It may speed loading time a bit.
    define('WITH_PAGINATOR', false); // This will generate a paginator a-la digg for you.
    define('ERROR_PAGE', "/path/to/error/404.html"); // Custom 404 error page

    //DISQUS
    define('DISQUS_SHORTNAME', ''); // The DISQUS shortname as you can find it on your admin panel.

    // Date (Look there : http://php.net/manual/en/timezones.php to find your timezone)
    define('TIMEZONE', 'Europe/Paris');

    //RewriteRule - Here you can enter your custom rewrite rules.
    define("REWRITERULES", serialize(
        array(
            "RewriteRule ^(about\b)(/?$) _layouts/about.php [L,QSA]",
        )
    ) );

    // User & Password (to protect the update.php from evil Internet)
    define('USER', "username");
    // Don't put your pass in clear, put the output of :
    // htpasswd -nb user password
    define('PASSWORD', "password_encoded"); 

// Author and Title (for the RSS feed)
define('FEED_TITLE', 'myblog');
define('AUTHOR_NAME', 'me');
define('AUTHOR_MAIL', 'me@myblog.com');

// Path vars - DO NOT TOUCH UNLESS YOU KNOW WHAT YOU ARE DOING.
define('ROOT_DIR', str_replace('//','/',dirname(__FILE__)));
define('POST_DIR', '_posts');
define('LAYOUT_DIR', '_layouts');
define('INC_DIR', '_inc');
define('CACHE_DIR', '_cache');

?>
