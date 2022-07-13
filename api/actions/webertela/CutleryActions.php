<?php
namespace api\actions\webertela;


use api\models\database\webetrela\Cutlery;

use yii\base\Exception;
use yii\helpers\Json;
use api\models\response\Result;



class CutleryActions {





    public static function getList(){

        $result = Cutlery::find()->where(['status' => '0'])->all();

        return $result;

    }

}