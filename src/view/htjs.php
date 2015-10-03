<?php 
// Javascript functions and variables to handle links etc. so they match the PHP 
// ones with respect to htaccess/mod-rewrite 
?>
<script type='text/javascript'><!--
    function linky(url){<?php if(!array_key_exists('ruri',$_GET)){
        print "return url;";
    }else{
        print "return url.replace('.php','');";
    } ?>}
        ttbaseurl = "<?php 
        global $_BASEURL;
        print $_BASEURL;
?>";
--></script>

