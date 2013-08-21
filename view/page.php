<?php view('page_header',Array('page' => $page)); ?>
<article>
    <div class='tt-content'>
    <?php 
    print $page->body;
    print $page->bodyright;
    ?>
    </div>
</article>
<?php view('page_footer',Array('page' => $page)); ?>

