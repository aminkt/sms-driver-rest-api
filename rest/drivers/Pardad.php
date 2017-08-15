<?php
/**
 * Created by PhpStorm.
 * User: Amin
 * Date: 8/15/2017
 * Time: 12:23 AM
 */

namespace aminkt\sms\drivers;

/**
 * Class Pardad
 * Handle pardad rest api.
 * @see http://pardaadsms.ir/?Page=WebServiceHelp Pardad documents for method inputs [http://pardaadsms.ir/?Page=WebServiceHelp]
 *
 * @method mixed SendArray(array $params = [])
 * @method mixed GetMessageID(array $params = [])
 * @method mixed GetMessageStatus(array $params = [])
 * @method mixed GetNumberGroupData(array $params = [])
 * @method mixed SendNumberGroup(array $params = [])
 * @method mixed SendNumberGroupSchedule(array $params = [])
 * @method mixed InsertNumberInNumberGroup(array $params = [])
 * @method mixed GetInboxMessage(array $params = [])
 * @method mixed GetInboxMessageWithNumber(array $params = [])
 * @method mixed GetInboxMessageWithInboxID(array $params = [])
 * @method mixed GetCredit(array $params = [])
 * @method mixed GetUserInfo(array $params = [])
 *
 * @package aminkt\sms\drivers
 */
class Pardad extends AbstractDriver
{
    /** @inheritdoc */
    public static $serverAddress = 'http://pardaadsms.ir/SMSWS/HttpService.ashx';

    /**
     * @inheritdoc
     */
    public function sendSms($args = [])
    {
        $number = "";
        $chkMessageID = "";
        foreach ($this->loadFromInputArray('recipientNumbers', $args, true) as $item) {
            $number = $number . $item . ",";
        }
        foreach ($this->loadFromInputArray('CheckingMessageID', $args) as $item) {
            $chkMessageID = $chkMessageID . $item . ",";
        }
        $isFlash = $this->loadFromInputArray('isFlash', $args);
        $args = [
            'To' => $number,
            'Message' => $this->loadFromInputArray('message', $args, true),
            'From' => $this->loadFromInputArray('From', $args),
            'Flash' => $isFlash ? "true" : "false",
            'chkMessageId' => $chkMessageID,
        ];
        return $this->SendArray($args);
    }

    /**
     * @inheritdoc
     */
    public function getCreditNumber($args = [])
    {
        return intval(($this->getCreditPrice() / 20));
    }

    /**
     * @inheritdoc
     */
    public function getCreditPrice($args = [])
    {
        return $this->GetCredit();
    }

    private function getLoginData()
    {
        return [
            'UserName' => 'farshad',
            'Password' => 'byBnMbki3zxU'
        ];
    }

    /**
     * @inheritdoc
     */
    public function sendRequest($method, $params, $requestMethod = 'post')
    {
        $params = array_merge($this->getLoginData(), $params);
        $data = '';
        $i = 0;
        foreach ($params as $key => $val) {
            if ($i > 0)
                $data .= '&';
            $data .= trim($key) . '=' . urlencode(trim($val));
            $i++;
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, static::$serverAddress . '?' . $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->timeout);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
}