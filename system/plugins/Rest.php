<?php

/**
 * REST Server API
 * 
 * This file is the REST Server API
 * 
 * @category Saral
 * @package	Saral_REST
 * @version		0.1
 * @since		0.1
 */

/**
 * REST class
 *
 * Class is used by controller whereever need to implement RESTfull service
 *
 * @category Saral
 * @package Saral_REST
 * @version Release: 0.1
 * @since 29.oct.2013
 * @author Sailesh Jaiswal (jaiswalsailesh@gmail.com)
 */
class REST
{

    /**
     *
     * default content type set to json
     *
     * @var string
     */
    private $_content_type = "application/json";

    /**
     *
     * default server status code
     *
     * @var integer
     */
    private $_code = 200;

    /**
     *
     * default server status code
     *
     * @var integer
     */
    private $_headers = array();

    /**
     */
    public function __construct()
    {
        $this->_headers = getallheaders();
    }

    /**
     *
     * Returns the referer
     *
     * @return string
     */
    public function getReferer()
    {
        return $_SERVER['HTTP_REFERER'];
    }

    /**
     *
     * returns the requested method
     *
     * @return string
     */
    public function getRequestMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     *
     * Retrieves params sent by client and retuns the response as array
     *
     * @return array
     */
    public function getJSONData()
    {
        switch ($this->getRequestMethod()) {
            case "POST":
                $inputs = json_decode(file_get_contents("php://input"), true);
                return $this->cleanInputs($inputs);
                break;
            case "GET":
            case "DELETE":
                return $this->cleanInputs($_GET);
                break;
            case "PUT":
                $inputs = json_decode(file_get_contents("php://input"), true);
                return $this->cleanInputs($inputs);
                break;
            default:
                return $this->getResponse(406);
                break;
        }
    }

    /**
     * filter the input data
     *
     * @param array $data            
     * @return string|array
     */
    private function cleanInputs($data)
    {
        $clean_input = array();
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                $clean_input[$k] = $this->cleanInputs($v);
            }
        } else {
            if (get_magic_quotes_gpc()) {
                $data = trim(stripslashes($data));
            }
            $data = strip_tags($data);
            $clean_input = trim($data);
        }
        return $clean_input;
    }

    /**
     * get header
     *
     * @param string $var            
     * @return string|boolean
     */
    public function getHeader($var)
    {
        if (isset($this->_headers[$var])) {
            return $this->_headers[$var];
        } else {
            return false;
        }
    }

    /**
     * sends curl request
     *
     * @param string $url            
     * @param array $data            
     * @param string $type            
     * @return SimpleXMLElement|string
     */
    function sendRequest($url, $data, $type = 'xml')
    {
        $content_type = ($type == 'xml') ? 'text/xml' : 'application/json';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, Array(
            "Content-Type:" . $content_type
        ));
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        return ($type == 'xml') ? new SimpleXMLElement($response) : $response;
    }
}
?>