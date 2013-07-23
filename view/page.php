<!DOCTYPE html>
<html>
<head>
<meta charset='utf-8'>
<?php 

print "<title>$page->title</title>";

print $page->printCss();

print $page->head;

?></head>
<body>
<div id='tt-content'>
<header>
    <h1><?php print $page->h1;?></h1>
    <menu><?php controller('menu',Array($menu)); ?></menu>
</header>
<article>
    <div class='tt-content'>
    <?php 
    print $page->body;
    ?>
    </div>
</article>
<footer>
    <div id='tt-footer'>
    <?php
    print $page->footer;
    ?>
    </div>
</footer>
<?php
    print $page->printJs();
?>
<div style='display:none'>
<?php 
    print $page->hidden;
?>
</div>
</body></html>
