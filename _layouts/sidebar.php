<?php 
include_once( str_replace('//','/',dirname(__FILE__).'/') .'../config.php');
?>

</div>

<div class="secondary">
<div class="search">
<center><div class="livesearchpopup">
         <div class="box">
         <form name="ls_form" class="form" id="searchbox" method="get" action="<?php echo URL;?>">
        <p><input class="edit" type="text" name="s" id="s" /></p>
         </form>
     <div id="livesearchpopup_box" style="display: none;">
       <img class="pfeil" src="<?php echo URL.'/'.LAYOUT_DIR; ?>/_plugins/live-search-popup/searchpfeil.png" alt="" />

       <h1>R&eacute;sultats</h1>

       <div id="livesearchpopup_results"></div>
     </div>
         </div>
</div>
</center></div>

<ul class="sbmenu">
    <li class="current_page_item"><a href="<?php echo URL;?>">home</a></li>
    <li class="page_item"><a href="<?php echo URL;?>/archives/" title="Archives">Archives</a></li>
</ul>
<div class="clear"></div>

<div class="feedme">
<a href="feed://<?php echo URL.'/'; ?>feed/"><img src="<?php echo URL.'/'.LAYOUT_DIR; ?>/_images/feed.jpg" alt="feed ton aggrégateur !" /></a>
</div>

</div>
<div class="clear"></div>
