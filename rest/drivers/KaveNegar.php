<?php
/**
 * Created by VS Code.
 * User: mr-exception
 * Date: 10/1/2018
 * Time: 01:32 AM
 */
namespace aminkt\sms\drivers;

use Exception;
use GuzzleHttp\Client;

/**
 * Class KaveNegar
 * Handle KaveNegar rest api.
 * @see http://kavenegar.com/rest.html KaveNegar documents for method inputs [http://kavenegar.com/rest.html]
 *
 * @method mixed send(array $params = [])
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
class KaveNegar extends AbstractDriver
{
    /** @inheritdoc */
    public static $serverAddress = 'https://api.kavenegar.com/v1/<TOKEN>/sms/<REQ>.json';

    private $token;
    public $from;

    /**
     * Send sms message.
     *
     * @param mixed $args Send sms by prepared args.
     * <code>
     * $args = [
     *      'message'=>'SMS text',
     *      'numbers'=>[
     *          '09120813856',
     *          '09398745687',
     *          ...
     *      ],
     *      'from'=>'23920453' //Sender number. Optional
     * ]
     * </code>
     * @return boolean
     */
    public function sendSms($args = []){
      $numbers = $this->loadFromInputArray('numbers', $args, true);
      $number = implode(',', $numbers);
      $from = $this->loadFromInputArray('from', $args);
      $message = ($this->loadFromInputArray('message', $args, true));
      $args = [
          'receptor' => urlencode($number),
          'message' => urlencode($message),
      ];
      $from && $args['sender'] = $from;
      $respnse = $this->send($args);
      if($respnse)
          return $respnse;
      return false;
    }

    /**
     * @inheritdoc
     */
    public function getCreditNumber($args = []){
      return 5;
    }

    /**
     * @inheritdoc
     */
    public function getCreditPrice($args = []){
      return 100;
    }

    /**
     * @inheritdoc
     */
    public function setLoginData($args = []){
      $this->token = $this->loadFromInputArray('token', $args, true);
      KaveNegar::$serverAddress = str_replace('<TOKEN>', $this->token, KaveNegar::$serverAddress);
    }

    /**
     * Get panel login data.
     * @return array
     */
    protected function getLoginData(){
      return [
        'token' => $this->token
      ];
    }

    /**
     * @inheritdoc
     */
    public function sendRequest($method, $params, $requestMethod = 'post') {
      $address = str_replace('<REQ>', $method, KaveNegar::$serverAddress);
      try {
          $client = new Client();
          $res = $client->request(strtoupper($requestMethod), $address, [
              'query' => $params
          ]);
          if ($res->getStatusCode() == 200) {
            // $this->$response = $res;
            return $res->getBody()->getContents();
          }
      } catch (Exception $exception) {
          throw $exception;
      }
      return false;
    }
}