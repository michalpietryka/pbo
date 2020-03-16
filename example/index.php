<?php

require './../src/PboReader.php';
require './../src/PboFile.php';
require './../src/PboHeaderEntry.php';
require './../src/PboHeader.php';

$pbo = PboReader::getFromFile('./Ignite.Altis.pbo');
$pbo->extract('./extracted');