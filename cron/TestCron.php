<?php
require_once (dirname(__FILE__) . "/../system/SaralCron.php");

class TestCron extends SaralCron
{

    public function __construct()
    {
        parent::__construct();
    }

    public function hello()
    {
        echo "Hello World!";
    }

    public function intro()
    {
        echo "I'm Sailesh!";
    }

    public function welcome($name)
    {
        echo "Welcome, $name";
    }
}

$test_cron = new TestCron();
$method = $argv[1]; /* command line arguments, first arg can be used as method */

if (isset($argv[2])) { /* command line arguments, second arg can be used as parameter to method, we can have as many params */
    $param1 = $argv[2];
    $test_cron->{$method}($param1);
} else {
    $test_cron->{$method}();
}
/**
 * examples
 * without arguments to the methods
 * 1. <path to php executable folder>/php <path of the root folder>/cron/TestCron.php hello
 * output: Hello World!
 * 2. <path to php executable folder>/php <path of the root folder>/cron/TestCron.php intro
 * output: I'm Sailesh!
 *
 * with arguments to the methods
 * 3. <path to php executable folder>/php <path of the root folder>/cron/TestCron.php welcome "DJ"
 * output: Welcome, DJ
 *
 *
 */
