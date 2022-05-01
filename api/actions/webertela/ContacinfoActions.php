<?php
namespace api\actions\webertela;


use api\models\database\webetrela\Contactinfo;

use yii\base\Exception;
use yii\helpers\Json;
use api\models\response\Result;



class ContacinfoActions {





    public static function getList(){

        $result = Contactinfo::find()->one();

        return $result;

    }

}