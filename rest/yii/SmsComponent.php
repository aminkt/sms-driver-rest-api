<?php

namespace aminkt\sms\yii;


use aminkt\sms\Sms;
use yii\base\Component;

/**
 * Class SmsComponent
 *
 * @package aminkt\sms\yii
 *
 * @author  Amin Keshavarz <amin@keshavarz.pro>
 */
class SmsComponent extends Component
{
    public $loginData = [];
    public $driverClass = null;

    /**
     * Send sms message.
     *
     * @param array $args Send sms by prepared args.
     *                    <code>
     *                    $args = [
     *                    'message'=>'SMS text',
     *                    'numners'=>[
     *                    09120813856,
     *                    09398745687,
     *                    ...
     *                    ],
     *                    'isFlash'=>false
     *                    ]
     *                    </code>
     *                    This input should work with driver that you use.
     *
     *
     * @return boolean
     *
     * @author Amin Keshavarz <amin@keshavarz.pro>
     */
    public function send($args)
    {
        $sms = new Sms();
        $sms->setDrviver([$this->driverClass]);

        $sms->setLoginData($this->loginData);

        return $sms->sendSms($args);
    }

}