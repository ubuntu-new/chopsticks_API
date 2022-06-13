<?php
namespace api\actions;


use api\models\response\Produc_categories_idResponse;
use api\models\database\Products;
use api\models\database\Product_categories;
use yii\base\Exception;
use yii\helpers\Json;
use api\models\response\Result;
use Yii;



class Product_categories_id {
    public static function getList($category_id = null){

        $result = [];
        $sql = "SELECT * FROM products WHERE category_id = $category_id";
        $product_category = \Yii::$app->db->createCommand($sql)->queryAll(\PDO::FETCH_ASSOC);

        foreach ($product_category as $row) {
            $result[] = new Produc_categories_idResponse($row);
        }
        return $result;
    }

}