<?php
function _404($requested){
    $page = model('page');

    $page->title("Page Not Found!");
    $page->h1("You asked for $requested, but it isn't here!");
    $page->body .= "Sorry the page you requested wasn't found. You can try a link above ";
    $page->body .= "or you can try contacting the site owner. If you ARE the site owner ";
    $page->body .= "you can contact <a href='http://treetrumpet.com/'>TreeTrumpet</a> for ";
    $page->body .= "support";
    view('page',Array('page' => $page));
}
