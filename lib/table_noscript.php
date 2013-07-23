<?php
require_once('lib/3rdparty/ged2json.php');
$gedcom = new ged2json('family.ged');
$ancestors = $gedcom->toJsonHash(FALSE);
ksort($ancestors);


