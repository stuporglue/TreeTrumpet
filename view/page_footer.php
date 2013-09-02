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
