<?php
namespace api\actions\webertela;


use api\models\database\webetrela\Products;

use api\models\database\Product_categories;
use api\models\database\Products_images;
use api\models\response\ProductsResponse;
use yii\base\Exception;
use yii\helpers\Json;
use api\models\response\Result;



class ProductsActions {


    public static function getListOld($category_id = null){
        return Products::find()->andWhere(['category_id' => $category_id])->orderBy(['weight' => SORT_ASC])->all();
    }


    public static function getList($category_id = null){

        $result = [];
        if ($category_id){

        $sql = "SELECT {{p}}.*,{{m}}.[[s]],{{m}}.[[m]],{{m}}.[[xl]] FROM {{products}} {{p}}
                    LEFT JOIN {{products_images}} {{m}} ON {{m}}.[[procts_id]] = [[p]].[[id]]
                /*WHERE {{p}}.[[status]] = 1 */
                 where {{p}}.[[category_id]] = $category_id AND {{p}}.[[status]] = '1'";

        }
        else {
            $sql = "SELECT {{p}}.*,{{m}}.[[s]],{{m}}.[[m]],{{m}}.[[xl]] FROM {{products}} {{p}}
                    LEFT JOIN {{products_images}} {{m}} ON {{m}}.[[procts_id]] = [[p]].[[id]]
                /*WHERE {{p}}.[[status]] = 1 */
                 where {{p}}.[[status]] = '1'";
        }

        $products = \Yii::$app->db->createCommand($sql)->queryAll(\PDO::FETCH_ASSOC);

        foreach ($products as $row) {
            $result[] = new ProductsResponse($row);
        }

        return $result;




//        return Products::find()->andWhere(['category_id' => $category_id])->orderBy(['weight' => SORT_ASC])->all();
    }

}