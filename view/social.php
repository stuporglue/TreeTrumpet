<?php


global $_BASEURL;

$shareUrl = linky($_BASEURL . $_SERVER['REQUEST_URI']);

$socialButtons = '';
$socialButtons .= "<div class='fb-send' data-href='$shareUrl'></div>";


?>
<script type='text/javascript'>
$(document).ready(function(){
    $('.socialButtons').html("<?php print $socialButtons;?>");
    socialButtons();
});
</script>

