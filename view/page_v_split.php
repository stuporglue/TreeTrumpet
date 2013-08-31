<?php 
$page->js('js/social.js');
$page->css('css/page_v_split.css');
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
