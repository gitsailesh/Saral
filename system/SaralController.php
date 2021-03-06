<?php

/**
 * SaralController
 *
 * This file is serves parent Controller for all controllers
 *
 * @category Saral
 * @package	SaralController
 * @version		0.5
 * @since		0.1
 */

/**
 * SaralController class
 *
 * This class is the parent Controller class, extends by all controllers
 *
 * @category Saral
 * @package SaralController
 * @version Release: 0.5
 * @since 0.1
 * @author Sailesh Jaiswal (jaiswalsailesh@gmail.com)
 */
class SaralController extends SaralObject
{

    function __construct()
    {
        parent::__construct();
    }

    /**
     * used to load the view
     *
     * @param string $view            
     * @param array $data            
     * @param string $extract            
     */
    function loadView($view, $data = array(), $extract = false)
    {
        if ($extract) {
            extract($data);
        }
        $root = dirname(dirname(__FILE__)) . '/';
        if ($extract) {
            $views = explode("/", $view);
            $module = $views[0];
            array_shift($views);
            $views = implode("/", $views);
            
            include_once ($root . 'app/' . $module . '/views/' . $views . '.php');
        } else {
            include_once ($root . 'app/ui/' . $view . '.php');
        }
    }

    /**
     * checks whether the provide array of values is not empty, can be used to check if posted data is required or not
     *
     * @param array $data            
     * @return boolean
     */
    protected function requiredData($data, $madatory)
    {
        foreach ($madatory as $field) {
            if (! (isset($data[$field]) && ((is_array($data[$field]) && count($data[$field])) || trim($data[$field]) != ''))) {
                return false;
            }
        }
        return true;
    }
}