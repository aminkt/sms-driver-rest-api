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

    private $username;
    private $password;
    private $WSID;
    public $from = '50002070001555';

    /**
     * @inheritdoc
     */
    public function sendSms($args = [])
    {
        $number = "";
        $chkMessageID = "";
        $numbers = $this->loadFromInputArray('numbers', $args, true);
        $chkMessageIDs = $this->loadFromInputArray('CheckingMessageID', $args);
        foreach ($numbers ? $numbers : [] as $item) {
            $number = $number . $item . ",";
        }
        foreach ($chkMessageIDs ? $chkMessageIDs : [] as $item) {
            $chkMessageID = $chkMessageID . $item . ",";
        }
        $isFlash = $this->loadFromInputArray('isFlash', $args);
        $from = $this->loadFromInputArray('From', $args);
        $args = [
            'To' => $number,
            'Message' => $this->loadFromInputArray('message', $args, true),
            'From' => $from ? $from : $this->from,
            'Flash' => $isFlash ? "true" : "false",
            'chkMessageId' => $chkMessageID,
        ];
        $respnse = $this->SendArray($args);
        if($respnse)
            return $respnse;
        return false;
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
        return doubleval($this->GetCredit());
    }

    /**
     * @inheritdoc
     */
    public function setLoginData($args = []){
        $this->username = $this->loadFromInputArray('username', $args, true);
        $this->password = $this->loadFromInputArray('password', $args, true);
//        $this->WSID = $this->loadFromInputArray('WSID', $args, true);
    }

    /**
     * Get panel login data.
     * @return array
     */
    protected function getLoginData()
    {
        return [
            'UserName' => $this->username,
            'Password' => $this->password,
//            'WSID' => $this->WSID
        ];
    }

    /**
     * @inheritdoc
     */
    public function sendRequest($method, $params, $requestMethod = 'post')
    {
        $params = array_merge($this->getLoginData(), $params);
        $params = array_merge(['service' => $method], $params);
        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->request(strtoupper($requestMethod), static::$serverAddress, [
                'query' => $params
            ]);
            if ($response->getStatusCode() == 200) {
                $this->response = $response;
                return $response->getBody()->getContents();
            }
        } catch (\Exception $exception) {
            throw $exception;
        }
        return false;
    }
}