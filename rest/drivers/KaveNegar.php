<?php
/**
 * Created by VS Code.
 * User: mr-exception
 * Date: 10/1/2018
 * Time: 01:32 AM
 */
namespace aminkt\sms\drivers;

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
     * @inheritdoc
     */
    public function sendSms($args = []){
      $numbers = $this->loadFromInputArray('numbers', $args, true);
      $number = implode(',', $numbers);
      $from = $this->loadFromInputArray('From', $args);
      $args = [
          'To' => $number,
          'Message' => $this->loadFromInputArray('message', $args, true),
          'From' => $from ? $from : $this->from
      ];
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
      $address .= '?receptor=' . urlencode($params['To']) . '&message=' . urlencode($params['Message']);
      try {
          $client = new \GuzzleHttp\Client();
          $res = $client->request('GET', $address, []);
          if ($res->getStatusCode() == 200) {
            // $this->$response = $res;
            return $res->getBody()->getContents();
          }
      } catch (\Exception $exception) {
          throw $exception;
      }
      return false;
    }
}