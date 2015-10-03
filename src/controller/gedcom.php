<?php

// Disable caching since we're just reading out a file
ob_end_clean();

header("Content-Type: application/gedcom");
header("Content-Length: " .(string)(filesize('family.ged')) );
header('Content-Disposition: attachment; filename="family.ged"');
header("Content-Transfer-Encoding: binary\n");
                 
readfile(__DIR__ . '/../family.ged');
