<?php
namespace api\actions;


use api\models\response\RecipesResponse;
use api\models\database\Product_categories;
use yii\base\Exception;
use yii\helpers\Json;
use api\models\response\Result;



class Product_categoriesActions {
    public static function getList(){

        return Product_categories::find()->where(['status' => '0'])->orderBy(['weight' => SORT_ASC])->all();
    }
}