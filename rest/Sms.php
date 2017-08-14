<?php
/**
 * Created by PhpStorm.
 * User: Amin
 * Date: 8/14/2017
 * Time: 11:43 PM
 */

namespace aminkt\sms;

use aminkt\sms\drivers\AbstractDriver as MethodsFactory;
use aminkt\sms\drivers\Pardad;
use aminkt\sms\exceptions\InvalidDriverException;

/**
 * Class Sms
 *
 * Send sms by defined driver.
 *
 * @package aminkt\sms
 */
class Sms
{
    /** @var  MethodsFactory $driver Driver class. */
    private $driver;


    /**
     * Set sms driver
     *
     * @param string $driver Driver fully class name.
     *
     * @throws InvalidDriverException
     */
    public function setDriver($driver)
    {
        if (class_exists($driver))
            $this->driver = new $driver();
        if (!($driver instanceof MethodsFactory))
            throw new InvalidDriverException();
    }

    /**
     * Return driver name.
     *
     * @return MethodsFactory
     */
    public function getDriver()
    {
        return $this->driver;
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

        $params = $arguments ? $arguments[0] : [];

        return $this->callMethod($method, $params);
    }

    /**
     * Call an API Method using Methods Driver.
     *
     * @param string $method Method name.
     * @param array $params Method args
     *
     * @return Response|mixed
     */
    protected function callMethod($method, array $params = [])
    {
        return $this->methodsFactory()->create($method, $params);
    }

    /**
     * Methods Factory
     *
     * @return MethodsFactory
     */
    protected function methodsFactory()
    {
        if ($this->driver === null) {
            $this->setDriver(Pardad::class);
        }

        return $this->getDriver();
    }
}