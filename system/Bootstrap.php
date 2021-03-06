<?php
/**
 * bootstrap file
 *
 * This file is loaded in the index file connecting all the controllers
 *
 * @category Saral
 * @version		0.1
 * @since		0.1
 */
session_start();

/**
 *
 * use to auto load classes
 *
 * @param string $class            
 */
function autoloadClasses($class)
{
    $class = str_replace('_', DIRECTORY_SEPARATOR, $class);
    require $class . ".php";
}
$inc_path = ini_get("include_path");

$ps = PATH_SEPARATOR;
$ds = DIRECTORY_SEPARATOR;

$root = str_replace(DIRECTORY_SEPARATOR, '/', dirname(dirname(__FILE__)));

ini_set("include_path", $inc_path . "$ps$root{$ds}app$ps$root{$ds}app/helpers$ps$root{$ds}system{$ds}plugins");

spl_autoload_register("autoloadClasses");

$object = new SaralObject();

if ($object->countParams() == 0) {
    $default = $object->getConfig('default');
    if ($default == '') {
        include ($root . "/error/404.php");
    } else {
        $params = explode("/", $default['module']);
        $object->setParams($params);
    }
}
if ($object->countParams() > 0) {
    $data = array();
    $location = '';
    
    $object->recurPath('controllers', $location, $object->getParams(), $data);
    if (count($data)) {
        require ($root . $data['path']);
        $controller = new $data['class']();
        if ($data['method'] != '' && method_exists($controller, 'do' . $data['method'])) {
            
            $method = 'do' . $data['method'];
            $controller->$method();
        } else if (file_exists($root . DIRECTORY_SEPARATOR . 'app/helpers/Router.php')) {
            include ($root . DIRECTORY_SEPARATOR . 'app/helpers/Router.php');
            foreach ($route as $key => $val) {
                $ke = explode("/", $key);
                if ($object->countParams() == count($ke) && ($object->getParam(0) == $ke[0] || $ke[0] == ':any')) {
                    $params = explode("/", $val);
                    $prms = $object->getParams();
                    $index = arraySearchPartial($ke, ':');
                    for ($i = 0; $i < $index; $i ++) {
                        array_shift($prms);
                    }
                    $params = array_merge($params, $prms);
                    $object->setParams($params);
                    $data = array();
                    $location = '';
                    $object->recurPath('controllers', $location, $object->getParams(), $data);
                    $controller = new $data['class']();
                    if ($data['method'] != '' && method_exists($controller, 'do' . $data['method'])) {
                        $method = 'do' . $data['method'];
                        $controller->$method();
                    } else {
                        include ($root . "/error/404.php");
                    }
                    break;
                }
            }
        } else {
            include ($root . "/error/404.php");
        }
    } else if (file_exists($root . DIRECTORY_SEPARATOR . 'app/helpers/Router.php')) {
        include ($root . DIRECTORY_SEPARATOR . 'app/helpers/Router.php');
        foreach ($route as $key => $val) {
            $ke = explode("/", $key);
            if ($object->countParams() == count($ke) && ($object->getParam(0) == $ke[0] || $ke[0] == ':any')) {
                $params = explode("/", $val);
                $prms = $object->getParams();
                $index = arraySearchPartial($ke, ':');
                for ($i = 0; $i < $index; $i ++) {
                    array_shift($prms);
                }
                $params = array_merge($params, $prms);
                $object->setParams($params);
                $data = array();
                $location = '';
                $object->recurPath('controllers', $location, $object->getParams(), $data);
                require ($root . $data['path']);
                $controller = new $data['class']();
                if ($data['method'] != '' && method_exists($controller, 'do' . $data['method'])) {
                    $method = 'do' . $data['method'];
                    $controller->$method();
                } else {
                    include ($root . "/error/404.php");
                }
                break;
            }
        }
    }
} else {
    $default = $object->getConfig('default');
    // if ($default == '') {
    include ($root . "/error/404.php");
    // } else {
    // $object->redirect($object->siteURL() . $default['module']);
    // }
}

function arraySearchPartial($arr, $keyword)
{
    foreach ($arr as $index => $string) {
        if (strpos($string, $keyword) !== FALSE)
            return $index;
    }
}
