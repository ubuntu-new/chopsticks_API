<?php
namespace rest\modules\v1\controllers;

use api\actions\AddressBookAction;
use api\actions\InboxAction;
use api\actions\SmsActions;
use api\actions\TasksAction;
use api\actions\UserAction;
use api\models\response\Result;
use Kerox\Push\Adapter\Fcm;
use Kerox\Push\Push;
use rest\controllers\LangController;
use rest\controllers\RestController;
use yii\base\Security;
use yii\imagine\Image;

class SmsController extends LangController {



    // get address bookis
    public function actionSend() {

        $post = \Yii::$app->request->post();

        return SmsActions::sendSms( $post["mobile"],$post["text"]);

    }


}