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
 * @see http://pardaadsms.ir/?Page=WebServiceHelp
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

    /**
     * @inheritdoc
     */
    public function sendSms($args = [])
    {
        // TODO: Implement sendSms() method.
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
}