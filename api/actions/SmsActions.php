<?php
/**
 * Created by PhpStorm.
 * User: levan
 * Date: 4/13/2020
 * Time: 12:42
 */

namespace api\actions;


use api\models\database\changeDriver;
use api\models\database\Customers;
use api\models\database\DeliveryMethods;
use api\models\database\DriversBalance;
use api\models\database\DriversBalanceDetail;
use api\models\database\OrderActions;
use api\models\database\Orders;
use api\models\database\OrderStatus;
use api\models\database\PaymentMethods;
use api\models\database\Status;
use api\models\database\Timesheet;
use api\models\response\Result;
use Automattic\WooCommerce\Client;
use Yii;
use yii\db\Exception;

use mdm\admin\models\User;
use yii\helpers\Json;

class SmsActions
{
    public static function sendSms ($mobile = null, $text = null) {

        $sender = 'CHOPSTICKS';
        $data = 'key=' . urlencode('976e96b8e7964fbe98785a5981d73a56') . '&destination=' . urlencode($mobile) . '&sender=' . urlencode($sender). '&content=' . urlencode($text).'&urgent=true';
        $url= "http://smsoffice.ge/api/v2/send?".$data;
        $response = file_get_contents($url);
        return json_decode($response); 
    }
    public static function sendSmsCustomer ($mobile = null, $text = null) {

        $sender = 'CHOPSTICKS';
        $data = 'key=' . urlencode('976e96b8e7964fbe98785a5981d73a56') . '&destination=' . urlencode($mobile) . '&sender=' . urlencode($sender). '&content=' . urlencode($text).'&urgent=true';
        $url= "http://smsoffice.ge/api/v2/send?".$data;
        $response = file_get_contents($url);
        return json_decode($response);
    }
}