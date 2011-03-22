<?php 
ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);
// set the maximal number of displayed search results here
$max_result_number = 10;

if ($_GET['s'] !== '') {

    include_once (str_replace('//','/',dirname(__FILE__).'/') .'../../../config.php');
    include_once ROOT_DIR.'/'.INC_DIR."/search.php"; 

    $result_array = array();
    $result_array = search_posts($_GET['s']);

    if (count($result_array) > 0) {
        echo '<ul id="resultlist">'."\n";
        $result = array_slice($result_array, 0, $max_result_number);
        $size = sizeOf($result);
        for ($i=0; $i<$size; $i++) {
?>
            <li class="resultlistitem">
                <a href="<?php echo $result[$i]["url"] ?>" rel="bookmark" title="Permanent Link: <?php echo $result[$i]["url"]; ?>"><?php echo $result[$i]["title"]; ?></a>
            </li>
<?php
        } 
                                 
        // If there are more than 10 results, show an additional <li>-element 
        // with total # or results - choosing that will then go to the search results page
        if (count($result) > $max_result_number) {
            echo'<li class="resultlistitem"><a href="' .  get_bloginfo(url) . "?s=" . $_GET['s'] .
              '" style="font-weight: bold" rel="bookmark" onclick="ls_form.submit(); return false;" >&gt;&gt; ' .
              __('Tous les résultats') . "</a></li>";
        }

        echo '</ul>';
    } else {
        echo '<p>Aucun Résultats.</p>';
    }
    
    /* uncomment this to show the link that allows closing of the search results
    echo '<a href="#" onclick="ls.close(); return false;" class="close_link">X Clear search results</a>';
    */
}
?>