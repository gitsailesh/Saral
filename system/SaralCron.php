<?php
$root = dirname(__FILE__);
include ($root . DIRECTORY_SEPARATOR . "SaralObject.php");
include ($root . DIRECTORY_SEPARATOR . "Database.php");
include ($root . DIRECTORY_SEPARATOR . "SaralModel.php");

class SaralCron extends SaralObject
{

    public function __construct()
    {
        parent::__construct();
    }
}