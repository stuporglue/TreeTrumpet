<!DOCTYPE html>
<html>
<head>
<meta charset='utf-8'>

<?php 
global $_BASEURL;
print "<link type='text/css' href='$_BASEURL/css/tt.css' rel='stylesheet' media='all'/>";
print $page->printCss();
print "<title>$page->title</title>";
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

    view('htjs');
?>
<div style='display:none'>
<?php 
    print $page->hidden;
?>
</div>
</body></html>
<?php
