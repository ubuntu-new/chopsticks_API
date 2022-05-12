<?php
namespace api\actions;


use api\models\database\webetrela\Products;
use api\models\database\Product_categories;
use yii\base\Exception;
use yii\helpers\Json;
use api\models\response\Result;



class ProductActions {

    public static function getList($category_id = null){
        return Products::find()->where(['status' => '0'])->andWhere(['category_id' => $category_id])->orderBy(['weight' => SORT_ASC])->all();
    }

}