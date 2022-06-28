<?php
namespace api\actions\webertela;


use api\models\database\webetrela\About;

use yii\base\Exception;
use yii\helpers\Json;
use api\models\response\Result;



class AboutActions {





    public static function getList(){

        $result = About::find()->all();

        return $result;

    }

}