<?php 
$page->css('tt_v_split.php');
view('page_header',Array('page' => $page,'menu' => $menu)); ?>
<article>
    <div class='tt-content'>
        <div class='tt-content-left tt-content-block'>
            <?php 
            print $page->body;
            ?>
            </div>
        <div class='tt-content-right tt-content-block'>
            <?php
                print $page->bodyright;
            ?>
        </div>
    </div>
</article>
<?php view('page_footer',Array('page' => $page,'menu' => $menu)); 
