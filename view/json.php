<?php
header("Content-Type: application/json; charset=utf-8");
if(is_string($json)){
    print $json;
}else{
    print json_encode($json);
}
