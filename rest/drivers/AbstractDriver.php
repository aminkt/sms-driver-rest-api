<?php
/**
 * Created by PhpStorm.
 * User: Amin
 * Date: 8/15/2017
 * Time: 12:23 AM
 */

namespace aminkt\sms\drivers;


use aminkt\sms\exceptions\InvalidInputException;
use aminkt\sms\Sms;

/**
 * Class AbstractDriver
 * Implment base of drivers.
 *
 * @package aminkt\sms\drivers
 */
abstract class AbstractDriver
{
    /** @var  string $serverAddress Server address. */
    public static $serverAddress;

    /** @var Sms $sms Holds Sms object instance */
    protected $sms;

    /** @var \Psr\Http\Message\ResponseInterface $response Holds Sms API Response */
    protected $response;

    /** @var double $timeout Curl request time out. */
    protected $timeout;

    /**
     * Driver constructor.
     *
     * @param Sms $api
     */
    public function __construct(Sms $api)
    {
        $this->setSms($api);
    }

    /**
     * @return Sms
     */
    public function getSms()
    {
        return $this->sms;
    }

    /**
     * @param Sms $sms
     */
    public function setSms($sms)
    {
        $this->sms = $sms;
    }

    /**
     * Magic method to process any dynamic method calls.
     *
     * @param $method
     * @param $arguments
     *
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        if (method_exists($this, $method)) {
            return call_user_func_array([$this, $method], $arguments);
        }
        if (isset($arguments[0]))
            $arguments = $arguments[0];
        return $this->create($method, $arguments);
    }

    /**
     * Call driver method.
     *
     * @param string $method Method name.
     * @param array $params Method args
     *
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function create($method, $params = [])
    {
        if (method_exists($this, $method)) {
            $arr = array($params);
            return call_user_func_array([$this, $method], $arr);
        }
        $reqMethod = 'post';
        if (preg_match('/get/is', $method)) {
            $reqMethod = 'get';
        }
        $response = $this->sendRequest($method, $params, $reqMethod);
        $this->response = $response;
        return $response;
    }

    /**
     * Send post request to server and return response.
     *
     * @param string $method
     * @param array $params
     * @param string $requestMethod
     *
     * @return mixed
     */
    abstract public function sendRequest($method, $params, $requestMethod = 'post');

    /**
     * Send sms message.
     *
     * @param mixed $args Send sms by prepared args.
     * <code>
     * $args = [
     *      'message'=>'SMS text',
     *      'numbers'=>[
     *          09120813856,
     *          09398745687,
     *          ...
     *      ],
     *      'isFlash'=>false
     * ]
     * </code>
     * @return boolean
     */
    abstract public function sendSms($args = []);

    /**
     * Get panel credit
     *
     * @return double
     */
    abstract public function getCreditPrice();

    /**
     * Get panel credit
     *
     * @return integer
     */
    abstract public function getCreditNumber();

    /**
     * Load input from array.
     *
     * @param string $key
     * @param array $array
     * @param bool $required
     *
     * @return null|mixed
     *
     * @throws InvalidInputException
     */
    protected function loadFromInputArray($key, $array, $required = false)
    {
        if (isset($array[$key]))
            return $array[$key];

        if ($required)
            throw new InvalidInputException("$key is not exist as inout.");

        return null;
    }

    /**
     * Set panel login data.
     * @param array $args   Input login data. For example:
     * <code>
     * $args = [
     *  'username'=>yourUserName,
     *  'password'=>password
     * ]
     * </code>
     */
    abstract public function setLoginData($args = []);

    /**
     * Get panel login data.
     * @return array
     */
    abstract protected function getLoginData();
}