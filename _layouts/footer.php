<?php include_once (str_replace('//','/',dirname(__FILE__).'/') .'../config.php'); ?>

<div class="clear">
</div>
</div>
<hr />

<div id="footer"><small>Designed by <a href="http://paulstamatiou.com" title="Paul Stamatiou">Paul Stamatiou</a>. Optimized by <a href="http://www.pronetadvertising.com/about" title="Neil Patel">Neil Patel</a>. Powered by <a href="http://github.com/maximeh/seiteki/" title="seiteki">seiteki</a></small></div>
<script src="<?php echo URL;?>/mint/?js" type="text/javascript"></script>

<script type="text/javascript" src="http://www.google.com/jsapi"></script>
<script type="text/javascript">google.load("prototype", "1.6.0.3");</script>
<script type="text/javascript" src="<?php echo URL.'/'.LAYOUT_DIR; ?>/_plugins/live-search-popup/live_search.js"></script>
<script type="text/javascript">
    ls.url = "<?php echo URL.'/'.LAYOUT_DIR; ?>/_plugins/live-search-popup/search_results.php";
</script>

</body>
</html>
