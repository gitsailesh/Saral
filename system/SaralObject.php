<?php

/**
 * Main file
 *
 * This file is the initializer for the controller and models. It is inheritated from database class to have database methods used in models
 *
 * @category Saral
 * @package	SaralObject
 * @version		0.4
 * @since		0.1
 */

/**
 * SaralObject class
 *
 * Class is used by controller & model, it has common methods that are used all over the application
 *
 * @category Saral
 * @package SaralObject
 * @version Release: 0.4
 * @since 29.oct.2013
 * @author Sailesh Jaiswal (jaiswalsailesh@gmail.com)
 */
class SaralObject
{

    /**
     * used to hold configuration values from config.ini file
     *
     * @var object
     */
    private $config;

    /**
     * used to hold error messages from error.ini file
     *
     * @var object
     */
    private static $error;

    /**
     * used to hold success messages from success.ini file
     *
     * @var object
     */
    private $success;

    /**
     * holds the parameters extracted from uri
     *
     * @var array
     */
    private $params;

    /**
     * holds the current method/page been accessed
     *
     * @var string
     */
    private $current_page;

    /**
     * constructor to connect to database by reading data from config.ini and form parameters from URI
     *
     * @return void
     */
    function __construct()
    {
        $root = dirname(dirname(__FILE__));
        if (! defined('SITE_ROOT'))
            define('SITE_ROOT', $root);
        $this->config = parse_ini_file($root . '/app/config.ini', true);
        
        $cwd = trim(str_replace(DIRECTORY_SEPARATOR, "/", dirname(dirname(__FILE__))), '/');
        $root = trim(str_replace(DIRECTORY_SEPARATOR, "/", $_SERVER['DOCUMENT_ROOT']), '/');
        $croot = trim(str_replace($root, '', $cwd), '/');
        
        $request_uri = $_SERVER['REQUEST_URI'];
        $question_mark = strrpos($request_uri, '?');
        $query_string = substr($request_uri, $question_mark + 1);
        parse_str($query_string, $_GET);
        $request_uri = $_SERVER['REQUEST_URI'];
        $question_mark_pos = strpos($request_uri, '?');
        $request_uri = ($question_mark_pos ? substr($request_uri, 0, $question_mark_pos) : $request_uri);
        
        if ($croot == '') {
            $uri = trim($request_uri, '/');
        } else {
            $uri = trim(str_replace($croot, '', trim($request_uri)), '/');
        }
        $this->params = ($uri != '') ? explode("/", $uri) : array();
    }

    /**
     * used to set params
     *
     * @param unknown $params            
     */
    public function setParams($params)
    {
        $this->params = $params;
    }

    /**
     * used to redirect to provided url
     *
     * @param string $url            
     */
    public function redirect($url)
    {
        header("location: $url");
    }

    /**
     * read the configuration variable from class variable config
     *
     * @param string $var            
     * @return string
     */
    public function getConfig($section = '', $var = '')
    {
        if ($section == '') {
            return $this->config;
        } else {
            if ($var == '') {
                return $this->config[$section];
            } else {
                return $this->config[$section][$var];
            }
        }
    }

    /**
     * read the message from messages.ini file
     *
     * @param string $var            
     * @return string
     */
    function getMessage($var = '')
    {
        $messages_ini = $this->getRootPath() . '/app/messages.ini';
        $messages = (file_exists($messages_ini)) ? parse_ini_file($messages_ini, true) : array();
        if ($var == '') {
            return $messages;
        } else if (array_key_exists($var, $messages)) {
            return $messages[$var];
        }
    }

    /**
     * used to send data to REST URL with data (optional) using POST method and returns an array
     *
     * @param string $url            
     * @param array $data            
     * @return array
     */
    function getRESTResponse($url, $data = array())
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, True);
        if (count($data)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type:application/json'
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, True);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        $output = curl_exec($ch);
        curl_errno($ch);
        curl_close($ch);
        
        $array = json_decode($output);
        return $array;
    }

    /**
     * retuns the value from params (URI segment) for given index
     *
     * @param number $index            
     * @return boolean|mixed
     */
    function getParam($index)
    {
        return isset($this->params[$index]) ? $this->params[$index] : false;
    }

    /**
     * returns the array of params (URI segments)
     *
     * @return array
     */
    function getParams()
    {
        return $this->params;
    }

    /**
     * counts the number of elements we got for params array
     *
     * @return number
     */
    function countParams()
    {
        return count($this->params);
    }

    /**
     * clears session
     */
    function clearSession()
    {
        session_unset();
        session_destroy();
    }

    /**
     * read the session value based on variable passed
     *
     * @param string $var            
     * @return mixed|boolean
     */
    function getSession($var)
    {
        if (isset($_SESSION[$var])) {
            return $_SESSION[$var];
        } else {
            return false;
        }
    }

    /**
     * sets the session value
     *
     * @param string $var            
     * @param mixed $val            
     */
    function setSession($var, $val)
    {
        $_SESSION[$var] = $val;
    }

    /**
     * read the cookie value for the given variable
     *
     * @param string $var            
     * @return mixed null
     */
    function getCookie($var)
    {
        if (isset($_COOKIE[$var])) {
            return $_COOKIE[$var];
        } else {
            return false;
        }
    }

    /**
     * removes the session variable(s)
     *
     * @param mixed $var            
     */
    function removeSession($var)
    {
        if (is_array($var)) {
            foreach ($var as $v) {
                unset($_SESSION[$v]);
            }
        } else {
            unset($_SESSION[$var]);
        }
    }

    /**
     * reads the get data
     *
     * @param string $var            
     * @return array
     */
    function getData($var)
    {
        return isset($_GET) ? $_GET : array();
    }

    /**
     * returns the post data
     *
     * @return array
     */
    function getPostData()
    {
        return isset($_POST) ? $_POST : array();
    }

    /**
     * logs the info
     *
     * @param string $str            
     */
    function logInfo($str = false)
    {
        $log_folder = $this->getRootPath() . '/logs';
        $file_name = $log_folder . "/log_" . date("Y-m-d") . ".log";
        
        $date = date("Y-m-d H:i:s");
        
        if ($str === false) {
            $str = str_repeat('-', 32);
        } else {
            $str = $date . ": " . $str;
        }
        
        if (! file_exists($log_folder)) {
            mkdir($log_folder, 0777);
        }
        if (file_exists($file_name) && filesize($file_name) >= 1048576) {
            chmod($file_name, 0777);
            $f = $this->getRootPath() . "/logs/log_" . date("Y-m-d") . "_" . time() . ".log";
            copy($file_name, $f);
            unlink($file_name);
            touch($file_name);
        }
        file_put_contents($file_name, $str . "\n", FILE_APPEND);
    }

    /**
     * converts an object to array
     *
     * @param object $object            
     * @return array
     */
    function objectToArray($object)
    {
        if (is_object($object)) {
            // Gets the properties of the given object
            // with get_object_vars function
            $d = get_object_vars($object);
        }
        
        if (is_array($object)) {
            /*
             * Return array converted to object Using __FUNCTION__ (Magic constant) for recursive call
             */
            return array_map(__FUNCTION__, $object);
        } else {
            // Return array
            return $object;
        }
    }

    /**
     * fetches latitude & longitude for given address
     *
     * @param string $address            
     * @return array
     */
    function getLatLong($address)
    {
        $prep_addr = str_replace(' ', '+', $address);
        
        $geocode = file_get_contents('http://maps.google.com/maps/api/geocode/json?address=' . $prep_addr . '&sensor=false');
        
        $output = json_decode($geocode);
        
        $lat = $output->results[0]->geometry->location->lat;
        $long = $output->results[0]->geometry->location->lng;
        
        return array(
            "latitude" => $lat,
            "longitude" => $long
        );
    }

    /**
     * Calculates the days difference between provided dates.
     * Formats accept YYYY-MM-DD HH:II:SS. If holidays has to be excluded provide value for $holidays.
     * To exclude weekends (Saturday, Sunday) set $exclude_weekend to true
     *
     * @param string $date1            
     * @param string $date2            
     * @param boolean $exclude_weekend            
     * @param array $holidays            
     * @return number
     */
    function dateDifference($date1, $date2, $exclude_weekend = false, $holidays = array())
    {
        $start = new DateTime($date1);
        $end = new DateTime($date2);
        
        $end->modify('+1 day');
        
        $interval = $end->diff($start);
        
        // total days
        $days = $interval->days;
        if (! $exclude_weekend && count($holidays) == 0) {
            return $days;
        }
        
        // create an iterateable period of date (P1D equates to 1 day)
        $period = new DatePeriod($start, new DateInterval('P1D'), $end);
        
        foreach ($period as $dt) {
            $curr = $dt->format('D');
            
            if (count($holidays)) {
                if (in_array($dt->format('Y-m-d'), $holidays)) {
                    $days --;
                }
            }
            
            if ($exclude_weekend) {
                // substract if Saturday or Sunday
                if ($curr == 'Sat' || $curr == 'Sun') {
                    $days --;
                }
            }
        }
        return $days; // 4
    }

    /**
     * gives project root absolute path
     *
     * @return string
     */
    function getRootPath()
    {
        return str_replace(DIRECTORY_SEPARATOR, "/", dirname(dirname(__FILE__))) . "/";
    }

    /**
     * gives you site url
     *
     * @return string
     */
    function getSiteURL()
    {
        $cwd = trim(str_replace(DIRECTORY_SEPARATOR, "/", dirname(dirname(__FILE__))), '/');
        $root = trim(str_replace(DIRECTORY_SEPARATOR, "/", $_SERVER['DOCUMENT_ROOT']), '/');
        $croot = trim(str_replace($root, '', $cwd), '/');
        
        $site_url = @$_SERVER['SERVER_NAME'];
        $project_folder = ($croot == '') ? '/' : '/' . $croot . '/';
        return ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 'https' : 'http') . '://' . $site_url . $project_folder;
    }

    /**
     * returns the public folder url to use in access css/images/scripts
     *
     * @return string
     * @since v0.2
     */
    function getPublicURL()
    {
        return $this->getSiteURL() . 'public/';
    }

    /**
     * returns the path of the public folder where user can upload the files if needed.
     *
     * @return string
     * @since v0.2
     */
    function getPublicPath()
    {
        return $this->getRootPath() . '/public/';
    }

    /**
     * includes plugin from plugins folder.
     * if $class is true then object is returned
     *
     * @param string $plugin            
     * @param boolean $class            
     * @return object
     */
    function loadPlugin($plugin, $class = false)
    {
        $root = dirname(dirname(__FILE__)) . '/';
        include_once ($root . "system/plugins/{$plugin}.php");
        $plg_arr = explode("/", $plugin);
        $plugin_name = end($plg_arr);
        if ($class)
            return new $plugin_name();
    }

    /**
     * generates hash password
     *
     * @param string $str            
     * @return string
     */
    function hashPassword($str)
    {
        return password_hash($str, PASSWORD_BCRYPT);
    }

    /**
     * verifies the password against the hash
     *
     * @param string $password            
     * @param string $hash            
     * @return boolean
     */
    function verifyPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }

    /**
     * generate token that can be used for hand shake
     *
     * @param number $length            
     * @return string
     */
    function generateToken($length)
    {
        return bin2hex(openssl_random_pseudo_bytes($length));
    }

    /**
     * generate random code based on the $type
     *
     * @param number $size            
     * @param number $type            
     * @return number|string
     */
    function generateCode($size, $type = 0)
    {
        switch ($type) {
            case 0: // numeric
                $start = str_repeat(1, $size);
                $end = str_repeat(9, $size);
                return rand($start, $end);
                break;
            case 1: // alpha
                $alpha = "abcdefghijklmnoprstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
                return substr(str_shuffle($alpha), 10, $size);
                break;
            case 2: // alphanumeric
                $alpha = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
                return substr(str_shuffle($alpha), 0, $size);
                break;
        }
    }

    /**
     * generate thumbnail
     *
     * @param string $source            
     * @param string $destination            
     * @param number $width            
     * @param number $height            
     * @return bool
     */
    function generateThumb($source, $destination, $width = 0, $height = 0)
    {
        $ext = strtolower(substr($source, strrpos($source, ".") + 1));
        $format = ($ext == 'jpg' || $ext == 'jpeg') ? 'jpeg' : $ext;
        $from_format = "imagecreatefrom" . $format;
        $source_image = $from_format($source);
        if ($ext == 'jpg' || $ext == 'jpeg') {
            $exif = exif_read_data($source);
            if (! empty($exif['Orientation'])) {
                switch ($exif['Orientation']) {
                    case 8:
                        $source_image = imagerotate($source_image, 90, 0);
                        break;
                    case 3:
                        $source_image = imagerotate($source_image, 180, 0);
                        break;
                    case 6:
                        $source_image = imagerotate($source_image, - 90, 0);
                        break;
                }
            }
        }
        $source_width = imagesx($source_image);
        $source_height = imagesy($source_image);
        $ratio1 = $source_width / $width;
        if ($height > 0)
            $ratio2 = $source_height / $height;
        if ($height != 0 && $width != 0) {
            if ($ratio1 > $ratio2) {
                $height = $source_height / $ratio1;
            } else {
                $width = $source_width / $ratio2;
            }
        } else if ($width != 0 && $height == 0) {
            // $height = ($source_width < $width)?($source_height):($source_height / $ratio1);
            // $width = ($source_width < $width)?$source_width:$width;
            $height = $source_height / $ratio1;
        } else if ($height != 0 && $width == 0) {
            $width = $source_width / $ratio2;
        } else {
            return false;
        }
        
        $target_image = imagecreatetruecolor($width, $height);
        imagecopyresampled($target_image, $source_image, 0, 0, 0, 0, $width, $height, $source_width, $source_height);
        $image_function = "image" . $format;
        if ($format == 'png') {
            $returnVal = $image_function($target_image, $destination . '.' . $ext, 9);
        } else {
            $returnVal = $image_function($target_image, $destination . '.' . $ext, 100);
        }
        imagedestroy($target_image);
        return $returnVal;
    }

    /**
     * loads the model (returns the object of the model)
     *
     * @param string $model            
     * @return object
     */
    function loadModel($model)
    {
        $models = explode("/", $model);
        $model = array_pop($models);
        
        $path = $this->getRootPath() . 'app/' . implode("/", $models) . "/models/" . $model . '.php';
        require_once ($path);
        return new $model();
    }

    /**
     * generate path
     *
     * @param string $what            
     * @param string $location            
     * @param array $params            
     * @param array $data            
     */
    function recurPath($what, $location = '', $params, &$data)
    {
        $root = $this->getRootPath();
        $param = array_shift($params);
        $method = '';
        if (count($params) >= 0) {
            if ($location == '') {
                if (file_exists($root . '/app/' . $param)) {
                    $location .= '/app/' . $param;
                    $this->recurPath($what, $location, $params, $data);
                }
            }
            
            if (file_exists($root . $location . '/' . $what)) {
                $location .= '/' . $what;
                $file_name = str_replace(" ", "", ucwords(str_replace("-", " ", $param)));
                $file_name2 = str_replace(" ", "", strtoupper(str_replace("-", " ", $param)));
                if (file_exists($root . $location . '/' . $file_name . '.php')) {
                    
                    if (count($params)) {
                        $method = str_replace(" ", "", ucwords(str_replace("-", " ", array_shift($params))));
                    }
                    if (count($data) == 0) {
                        $data = array(
                            'path' => $location . '/' . $file_name . '.php',
                            'class' => $file_name,
                            'method' => $method
                        );
                    }
                    return;
                } else if (file_exists($root . $location . '/' . $file_name2 . '.php')) {
                    
                    if (count($params)) {
                        $method = str_replace(" ", "", ucwords(str_replace("-", " ", array_shift($params))));
                    }
                    if (count($data) == 0) {
                        $data = array(
                            'path' => $location . '/' . $file_name2 . '.php',
                            'class' => $file_name2,
                            'method' => $method
                        );
                    }
                    return;
                }
            } else if (file_exists($root . $location . '/' . $param)) {
                $location .= '/' . $param;
                $this->recurPath($what, $location, $params, $data);
            }
        }
    }

    /**
     * gives time ago in detailed
     *
     * @param string $date_time            
     * @return string
     */
    function timeAgo($date_time)
    {
        $then = new DateTime($date_time);
        $now = new DateTime();
        $delta = $now->diff($then);
        
        $quantities = array(
            'year' => $delta->y,
            'month' => $delta->m,
            'day' => $delta->d,
            'hour' => $delta->h,
            'minute' => $delta->i,
            'second' => $delta->s
        );
        
        $str = '';
        foreach ($quantities as $unit => $value) {
            if ($value == 0)
                continue;
            $str .= $value . ' ' . $unit;
            if ($value != 1) {
                $str .= 's';
            }
            $str .= ', ';
        }
        $str = $str == '' ? 'a moment ' : substr($str, 0, - 2);
        return $str . ' ago';
    }

    /**
     * coverts the given date time to GMT/UTC based on timezone/daylight saving offset in seconds provided
     *
     * @param string $date            
     * @param string $value            
     * @param string $is_timezone
     *            if $is_timezone is true then $value would be timezone (eg: asia/Kolkata)
     *            if $is_timezone is false then $value would be +19800 (+5:30 hrs)
     * @return string
     */
    function getGMT($date, $value, $is_timezone = true)
    {
        date_default_timezone_set("UTC");
        
        if ($is_timezone) {
            $daylight_savings_offset_in_seconds = timezone_offset_get(timezone_open($value), new DateTime());
        } else {
            $daylight_savings_offset_in_seconds = $value;
        }
        
        return $new_date = date('Y-m-d H:i:s', strtotime('-' . $daylight_savings_offset_in_seconds . ' seconds', strtotime($date)));
    }

    /**
     * coverts the given date time in GMT/UTC to local date time on timezone/daylight saving offset in seconds provided
     *
     * @param string $date            
     * @param string $value            
     * @param boolean $is_timezone
     *            if $is_timezone is true then $value would be timezone (eg: asia/Kolkata)
     *            if $is_timezone is false then $value would be +19800 (+5:30 hrs)
     * @return string
     */
    function getLocal($date, $value, $is_timezone = true)
    {
        date_default_timezone_set("UTC");
        if ($is_timezone) {
            $daylight_savings_offset_in_seconds = timezone_offset_get(timezone_open($value), new DateTime());
        } else {
            $daylight_savings_offset_in_seconds = $value;
        }
        return $new_date = date('Y-m-d H:i:s', strtotime('+' . $daylight_savings_offset_in_seconds . ' seconds', strtotime($date)));
    }

    /**
     * to find distance between co-ordinates in $units
     *
     * @param float $lat1            
     * @param float $lon1            
     * @param float $lat2            
     * @param float $lon2            
     * @param string $unit
     *            $unit = M -> in Meters $unit = N -> in Miles $unit = K -> in Kilometers
     * @return number
     */
    function distCoordinates($lat1, $lon1, $lat2, $lon2, $unit = 'M')
    {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);
        
        if ($unit == "K") {
            return ($miles * 1.609344);
        } else if ($unit == "N") {
            return ($miles * 0.8684);
        } else if ($unit == "M") {
            return ($miles * 1.609344) * 1000;
        } else {
            return $miles;
        }
    }

    /**
     * url decode
     *
     * @param string $str            
     * @return string
     */
    function decode($str)
    {
        return urldecode($str);
    }

    /**
     * url encode
     *
     * @param string $str            
     * @return string
     */
    function encode($str)
    {
        return urlencode($str);
    }

    /**
     *
     * returns the server status message for the code
     *
     * @return string
     */
    protected function getStatusMessage($code = 500)
    {
        $status = array(
            100 => 'Continue',
            101 => 'Switching Protocols',
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Found',
            303 => 'See Other',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            306 => '(Unused)',
            307 => 'Temporary Redirect',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Timeout',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Request Entity Too Large',
            414 => 'Request-URI Too Long',
            415 => 'Unsupported Media Type',
            416 => 'Requested Range Not Satisfiable',
            417 => 'Expectation Failed',
            422 => 'Unprocessable Entity',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
            505 => 'HTTP Version Not Supported'
        );
        return $status[$code];
    }

    /**
     * sets the header before json data sent
     */
    protected function setResponseHeader($status, $content_type = 'application/json')
    {
        header("HTTP/1.1 " . $status . " " . $this->getStatusMessage($status));
        header("Content-Type:" . $content_type);
    }

    /**
     * send json response
     */
    protected function sendJSONResponse($data)
    {
        echo json_encode($data);
        exit();
    }

    /**
     * converts bytes to human readable
     *
     * @param number $bytes            
     * @param number $precision            
     * @return string
     */
    protected function convertBytes($bytes, $precision = 2)
    {
        $unit = array(
            'B',
            'KB',
            'MB',
            'GB',
            'TB',
            'PB',
            'EB'
        );
        
        return @round($bytes / pow(1024, ($i = floor(log($bytes, 1024)))), $precision) . ' ' . $unit[$i];
    }

    /**
     * This function will generate strict random password of 8 chars, with at least one Upper Alpha, one Lower Alpha, One Special Char and One number
     *
     * @return string
     */
    function generateComplexPassword()
    {
        $upper_alpha = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $lower_alpha = "abcdefghijklmnopqrstuvwxyz";
        $special_char = "!@#$&*";
        $numbers = "1234567890";
        $result = substr(str_shuffle($lower_alpha), 3, 3);
        $result .= substr(str_shuffle($upper_alpha), 2, 2);
        $result .= substr(str_shuffle($special_char), 1, 1);
        $result .= substr(str_shuffle($numbers), 2, 2);
        
        return $result;
    }

    /**
     * creates object for the class
     *
     * @param string $class_name            
     * @return object
     */
    protected function getInstance($class_name)
    {
        return new $class_name();
    }

    /**
     * returns media physical path
     *
     * @return string
     */
    protected function getMediaPath()
    {
        return $this->getRootPath() . 'media/';
    }

    /**
     * returns media url
     *
     * @return string
     */
    protected function getMediaURL()
    {
        return $this->siteURL() . 'media/';
    }

    /**
     * method to send mail using PHPMailer
     *
     * @author Sailesh Jaiswal
     * @since 22-06-2017
     * @param string $to            
     * @param string $subject            
     * @param string $message            
     * @param string $attachment            
     * @param string $from_name            
     * @param string $from_email            
     */
    protected function sendEmail($to, $subject, $message, $attachment = '', $from_name = '', $from_email = '')
    {
        $smtp = $this->config['smtp'];
        
        $this->loadPlugin("PHPMailer/PHPMailerAutoload");
        
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->SMTPSecure = $smtp['secure'];
        
        // smtp host
        if (trim($smtp['host']))
            $mail->Host = $smtp['host'];
        
        // smtp port
        if (trim($smtp['port']))
            $mail->Port = $smtp['port'];
        
        // auth
        if (trim($smtp['auth']))
            $mail->SMTPAuth = $smtp['auth'];
        
        // username
        if (trim($smtp['username']))
            $mail->Username = $smtp['username'];
        
        // password
        if (trim($smtp['password']))
            $mail->Password = $smtp['password'];
        
        // attachment
        if (trim($attachment)) {
            $fp = fopen($attachment, "rb");
            $file = fread($fp, filesize($attachment));
            $file = chunk_split(base64_encode(file_get_contents($attachment)));
            $mail->addAttachment($attachment);
        }
        
        // form name/sender name
        if ($from_name == '')
            $from_name = $smtp['from_name'];
        
        // from email/sender email
        if ($from_email == '')
            $from_email = $smtp['from_email'];
        
        $mail->setFrom($from_email, $from_name);
        $mail->addAddress($to);
        $mail->Subject = $subject;
        $mail->msgHTML($message);
        
        if (! $mail->send())
            $this->logInfo($mail->ErrorInfo);
    }

    /**
     * force download file
     *
     * @param string $file_path            
     */
    function downloadFile($file_path)
    {
        if (file_exists($file_path)) {
            $file_name = basename($file_path);
            $file_name = strpos($file_name, '_') ? substr($file_name, strpos($file_name, '_') + 1) : $file_name;
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $file_name . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file_path));
            flush(); // Flush system output buffer
            readfile($file_path);
            exit();
        } else {
            echo "Sorry file does not exists";
            exit();
        }
    }
}

if (! defined('PASSWORD_BCRYPT'))
    define('PASSWORD_BCRYPT', 1);
if (! defined('PASSWORD_DEFAULT'))
    define('PASSWORD_DEFAULT', PASSWORD_BCRYPT);

if (! function_exists('password_verify')) {

    /**
     * Verify a password against a hash using a timing attack resistant approach
     *
     * @param string $password
     *            The password to verify
     * @param string $hash
     *            The hash to verify against
     *            
     * @return boolean If the password matches the hash
     */
    function password_verify($password, $hash)
    {
        if (! function_exists('crypt')) {
            trigger_error("Crypt must be loaded for password_verify to function", E_USER_WARNING);
            return false;
        }
        $ret = crypt($password, $hash);
        if (! is_string($ret) || strlen($ret) != strlen($hash) || strlen($ret) <= 13) {
            return false;
        }
        
        $status = 0;
        for ($i = 0; $i < strlen($ret); $i ++) {
            $status |= (ord($ret[$i]) ^ ord($hash[$i]));
        }
        
        return $status === 0;
    }
}

if (! function_exists('password_hash')) {

    /**
     * Hash the password using the specified algorithm
     *
     * @param string $password
     *            The password to hash
     * @param int $algo
     *            The algorithm to use (Defined by PASSWORD_* constants)
     * @param array $options
     *            The options for the algorithm to use
     *            
     * @return string false hashed password, or false on error.
     */
    function password_hash($password, $algo, array $options = array())
    {
        if (! function_exists('crypt')) {
            trigger_error("Crypt must be loaded for password_hash to function", E_USER_WARNING);
            return null;
        }
        if (! is_string($password)) {
            trigger_error("password_hash(): Password must be a string", E_USER_WARNING);
            return null;
        }
        if (! is_int($algo)) {
            trigger_error("password_hash() expects parameter 2 to be long, " . gettype($algo) . " given", E_USER_WARNING);
            return null;
        }
        switch ($algo) {
            case PASSWORD_BCRYPT:
                
                // Note that this is a C constant, but not exposed to PHP, so we don't define it here.
                $cost = 10;
                if (isset($options['cost'])) {
                    $cost = $options['cost'];
                    if ($cost < 4 || $cost > 31) {
                        trigger_error(sprintf("password_hash(): Invalid bcrypt cost parameter specified: %d", $cost), E_USER_WARNING);
                        return null;
                    }
                }
                // The length of salt to generate
                $raw_salt_len = 16;
                // The length required in the final serialization
                $required_salt_len = 22;
                $hash_format = sprintf("$2y$%02d$", $cost);
                break;
            default:
                trigger_error(sprintf("password_hash(): Unknown password hashing algorithm: %s", $algo), E_USER_WARNING);
                return null;
        }
        if (isset($options['salt'])) {
            switch (gettype($options['salt'])) {
                case 'NULL':
                case 'boolean':
                case 'integer':
                case 'double':
                case 'string':
                    $salt = (string) $options['salt'];
                    break;
                case 'object':
                    if (method_exists($options['salt'], '__tostring')) {
                        $salt = (string) $options['salt'];
                        break;
                    }
                case 'array':
                case 'resource':
                default:
                    trigger_error('password_hash(): Non-string salt parameter supplied', E_USER_WARNING);
                    return null;
            }
            if (strlen($salt) < $required_salt_len) {
                trigger_error(sprintf("password_hash(): Provided salt is too short: %d expecting %d", strlen($salt), $required_salt_len), E_USER_WARNING);
                return null;
            } elseif (0 == preg_match('#^[a-zA-Z0-9./]+$#D', $salt)) {
                $salt = str_replace('+', '.', base64_encode($salt));
            }
        } else {
            $buffer = '';
            $buffer_valid = false;
            if (function_exists('mcrypt_create_iv') && ! defined('PHALANGER')) {
                $buffer = mcrypt_create_iv($raw_salt_len, MCRYPT_DEV_URANDOM);
                if ($buffer) {
                    $buffer_valid = true;
                }
            }
            if (! $buffer_valid && function_exists('openssl_random_pseudo_bytes')) {
                $buffer = openssl_random_pseudo_bytes($raw_salt_len);
                if ($buffer) {
                    $buffer_valid = true;
                }
            }
            if (! $buffer_valid && is_readable('/dev/urandom')) {
                $f = fopen('/dev/urandom', 'r');
                $read = strlen($buffer);
                while ($read < $raw_salt_len) {
                    $buffer .= fread($f, $raw_salt_len - $read);
                    $read = strlen($buffer);
                }
                fclose($f);
                if ($read >= $raw_salt_len) {
                    $buffer_valid = true;
                }
            }
            if (! $buffer_valid || strlen($buffer) < $raw_salt_len) {
                $bl = strlen($buffer);
                for ($i = 0; $i < $raw_salt_len; $i ++) {
                    if ($i < $bl) {
                        $buffer[$i] = $buffer[$i] ^ chr(mt_rand(0, 255));
                    } else {
                        $buffer .= chr(mt_rand(0, 255));
                    }
                }
            }
            $salt = str_replace('+', '.', base64_encode($buffer));
        }
        $salt = substr($salt, 0, $required_salt_len);
        
        $hash = $hash_format . $salt;
        
        $ret = crypt($password, $hash);
        
        if (! is_string($ret) || strlen($ret) <= 13) {
            return false;
        }
        
        return $ret;
    }
}

if (! function_exists('openssl_random_pseudo_bytes')) {

    function openssl_random_pseudo_bytes($length)
    {
        $length_n = (int) $length; // shell injection is no fun
        $handle = popen("/usr/bin/openssl rand $length_n", "r");
        $data = stream_get_contents($handle);
        pclose($handle);
        return $data;
    }
}

