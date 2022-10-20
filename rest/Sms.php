<?php
/**
 * Created by PhpStorm.
 * User: Amin
 * Date: 8/14/2017
 * Time: 11:43 PM
 */

namespace aminkt\sms;

use aminkt\sms\drivers\AbstractDriver as MethodsFactory;
use aminkt\sms\drivers\Sharifs;
use aminkt\sms\exceptions\InvalidDriverException;

/**
 * Class Sms
 *
 * Send sms by defined driver.
 *
 * @method  mixed   sendSms(array $args = [])                          Send sms message.
 * @method  double  getCreditPrice()                                   Get panel credit
 * @method  integer getCreditNumber()                                  Get panel credit
 * @method  void    setLoginData(array $args = [])   Set panel login data.
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
        if (class_exists($driver)){
            $this->driver = new $driver($this);
            if (!($this->driver instanceof MethodsFactory))
                throw new InvalidDriverException("$driver is not an instance of ".MethodsFactory::class);
        }else{
            throw new InvalidDriverException("$driver Not found.");
        }
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
     * @return \GuzzleHttp\Psr7\Response|mixed
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
            $this->setDriver(Sharifs::class);
        }

        return $this->getDriver();
    }
}
