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


    public static function getList($url = null){

        $result = [];
        if ($url){

//        $sql = "SELECT {{p}}.*,{{m}}.[[s]],{{m}}.[[m]],{{m}}.[[xl]],{{pc}}.[[url]] FROM {{products}} {{p}}
//                    LEFT JOIN {{products_images}} {{m}} ON {{m}}.[[procts_id]] = [[p]].[[id]]
//                    INNER JOIN {{product_category}} {{pc}} ON {{pc}}.[[id]] = {{p}}.[[category_id]]
//                 where {{pc}}.[[url]] = '$url' AND {{p}}.[[status]] = '1'";

        $sql = "SELECT {{p}}.*,{{m}}.[[filePath]],{{pc}}.[[url]] FROM {{products}} {{p}}
                    LEFT JOIN {{image}} {{m}} ON {{m}}.[[itemId]] = [[p]].[[id]] AND {{m}}.[[modelName]] = 'Products' AND {{m}}.[[isMain]] = '1'
                    INNER JOIN {{product_category}} {{pc}} ON {{pc}}.[[name]] = {{p}}.[[category_name]]
                    WHERE {{pc}}.[[url]] = '$url' AND {{p}}.[[status]] = '1'
                    ORDER BY {{p}}.[[weight]] ASC ";

        }
        else {
            $sql = "SELECT {{p}}.*,{{m}}.[[filePath]],{{pc}}.[[url]] FROM {{products}} {{p}}
                    LEFT JOIN {{image}} {{m}} ON {{m}}.[[itemId]] = [[p]].[[id]] AND {{m}}.[[modelName]] = 'Products' AND {{m}}.[[isMain]] = '1'
                    INNER JOIN {{product_category}} {{pc}} ON {{pc}}.[[name]] = {{p}}.[[category_name]]
                    WHERE  {{p}}.[[status]] = '1'
                    ORDER BY {{p}}.[[weight]] ASC ";
        }

        $products = \Yii::$app->db->createCommand($sql)->queryAll(\PDO::FETCH_ASSOC);

        foreach ($products as $row) {
            $result[] = new ProductsResponse($row);
        }

        return $result;




//        return Products::find()->andWhere(['category_id' => $category_id])->orderBy(['weight' => SORT_ASC])->all();
    }

}