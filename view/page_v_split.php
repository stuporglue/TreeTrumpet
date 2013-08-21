<?php view('page_header',Array('page' => $page)); ?>
<article>
    <div class='tt-content'>
        <div class='tt-content-left'>
            <?php 
            print $page->body;
            ?>
            </div>
        <div class='tt-content-right'>
            <?php
                print $page->bodyright;
            ?>
        </div>
    </div>
</article>
<?php view('page_footer',Array('page' => $page)); ?>
