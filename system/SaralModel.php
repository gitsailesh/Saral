<?php

/**
 * SaralModel file
 * 
 * This file is the parent class for all the models
 * 
 * @category Saral
 * @package	SaralModel
 * @version		0.4
 * @since		0.1
 */

/**
 * SaralModel class
 *
 * Class is extended by all the models
 *
 * @category Saral
 * @package SaralModel
 * @version Release: 0.4
 * @since 29.oct.2013
 * @author Sailesh Jaiswal (jaiswalsailesh@gmail.com)
 */
class SaralModel extends Database
{

    function __construct()
    {
        parent::__construct();
    }
}