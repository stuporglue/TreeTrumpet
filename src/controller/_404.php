<?php

// Don't cache 404s since they might exist in the future
ob_end_clean();

function _404($requested){
    $page = model('page');

    $page->title("Page Not Found!");
    $page->h1("You asked for <em>$requested</em>, but it isn't here!");
    $page->body .= "<p>You're searching for your ancestors, and we're searching for this lost page!</p>";
    $page->body .= "<p>Sorry the page you requested wasn't found. You can try a link above. The <a href='" . linky("$_BASEURL/people.php") . "'>People</a>";
    $page->body .= " page has a list of everyone, so that might be a good place to start.";
    $page->body .= "You could also try contacting the site owner.</p>";
    $page->body .= "<p>If you ARE the site owner you can contact <a href='https://github.com/stuporglue/TreeTrumpet'>TreeTrumpet</a> for support.</p>";
    view('page',Array('page' => $page));
}
